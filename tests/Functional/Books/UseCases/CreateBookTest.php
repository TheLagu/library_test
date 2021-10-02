<?php

namespace Tests\Functional\Books;

use DateTimeImmutable;
use Library\Books\DTOs\BookDto;
use Library\Books\Entities\Book;
use Library\Books\Exceptions\BookAlreadyExistsException;
use Library\Books\Repositories\BooksRepository;
use Library\Books\UseCases\CreateBookUseCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\StatusCode;
use Tests\Functional\FunctionalTestCase;

class CreateBookTest extends FunctionalTestCase
{
    public function invalidParamsProvider(): array
    {
        $allErrors = [
            'error' =>
                [['pages: int, min 1, required'],
                    ['isbn: string, length 10 to 13, required'],
                    ['title: string, min length 1, required'],
                    ['topic: string, min length 1'],
                    ['description: string, min length 1'],]
        ];

        return [
            [[], $allErrors],
            [
                [
                    'isbn' => null,
                    'title' => null,
                    'pages' => null,
                    'topic' => null,
                    'description' => null,
                ],
                $allErrors
            ],
            [
                [
                    'isbn' => '123456789',
                    'title' => '',
                    'pages' => 0,
                    'topic' => '',
                    'description' => '',
                ],
                $allErrors
            ]
        ];
    }

    /**
     * @dataProvider invalidParamsProvider
     */
    public function testInvalidParams(array $params, array $errorMessage): void
    {
        $response = $this->request(
            'POST',
            '/books',
            [
                'body' => $params,
            ],
        );

        $body = json_decode($response->getBody(), true);
        $this->assertSame(StatusCode::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertSame($errorMessage, $body);
    }

    public function testBookAlreadyExists(): void
    {
        $this->loadFixtures(['Books/testBookAlreadyExists']);
        $response = $this->request(
            'POST',
            '/books',
            [
                'body' => [
                    'isbn' => '0123456789',
                    'title' => 'Alicia en el país de las maravillas',
                    'pages' => 287,
                    'topic' => 'fantasia',
                    'description' => 'Alicia dentro de un laberinto...',
                ],
            ],
        );

        $body = json_decode($response->getBody(), true);
        $this->assertSame(StatusCode::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertSame(['error' => 'ISBN already exists'], $body);
    }

    public function testSuccess(): void
    {
        $response = $this->request(
            'POST',
            '/books',
            [
                'body' => [
                    'isbn' => '1111111111111',
                    'title' => 'Alicia en el país de las maravillas',
                    'pages' => 287,
                    'topic' => 'fantasia',
                    'description' => 'Alicia dentro de un laberinto...',
                ],
            ],
        );

        $body = json_decode($response->getBody(), true);
        $this->assertSame(StatusCode::HTTP_CREATED, $response->getStatusCode());

        /** @var Book $book */
        $book = $this->entityManager
            ->getRepository(Book::class)
            ->findOneBy(['encoded_id' => $body['id']]);
        $this->assertNotNull($book);
        $this->assertSame($book->getTopic(), 'fantasia');
        $this->assertSame($book->getTitle(), 'Alicia en el país de las maravillas');
        $this->assertSame($book->getIsbn(), '1111111111111');
        $this->assertSame($book->getPages(), 287);
        $this->assertSame($book->getDescription(), 'Alicia dentro de un laberinto...');
        $this->assertSame((new DateTimeImmutable())->format('Y-m-d'), ($book->getCreatedAt())->format('Y-m-d'));
        $this->assertNotEmpty($book->getEncodedId());

    }
}