<?php

namespace Tests\Unit\Books;

use DateTimeImmutable;
use Library\Books\DTOs\BookDto;
use Library\Books\Entities\Book;
use Library\Books\Exceptions\BookAlreadyExistsException;
use Library\Books\Repositories\BooksRepository;
use Library\Books\UseCases\CreateBookUseCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateBookUseCaseTest extends TestCase
{
    private MockObject $booksRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->booksRepository = $this->createMock(BooksRepository::class);
    }

    private function createSut(): CreateBookUseCase
    {
        return new CreateBookUseCase(
            $this->booksRepository
        );
    }

    public function testBookAlreadyExists(): void
    {
        $this->booksRepository
            ->expects($this->once())
            ->method('existsByISBN')
            ->with('fake_isbn')
            ->willReturn(true);

        $bookDto = BookDto::createFromArray([
            'isbn' => 'fake_isbn'
        ]);

        $this->expectException(BookAlreadyExistsException::class);

        $this->createSut()->__invoke($bookDto);
    }

    public function testSuccess(): void
    {
        $this->booksRepository
            ->expects($this->once())
            ->method('existsByISBN')
            ->with('fake_isbn')
            ->willReturn(false);

        $bookDto = BookDto::createFromArray([
            'isbn' => 'fake_isbn',
            'title' => 'fake_title',
            'pages' => 123,
            'topic' => 'fake_topic',
            'description' => 'fake_description',
        ]);

        $this->booksRepository
            ->expects($this->once())
            ->method('persist')
            ->with($this->callback(function(Book $book) use ($bookDto) {
                $this->assertSame($book->getTopic(), $bookDto->getTopic());
                $this->assertSame($book->getTitle(), $bookDto->getTitle());
                $this->assertSame($book->getIsbn(), $bookDto->getIsbn());
                $this->assertSame($book->getPages(), $bookDto->getPages());
                $this->assertSame($book->getDescription(), $bookDto->getDescription());
                $this->assertSame((new DateTimeImmutable())->format('Y-m-d'), ($book->getCreatedAt())->format('Y-m-d'));
                $this->assertNotEmpty($book->getEncodedId());

                return true;
            }));

        $this->booksRepository
            ->expects($this->once())
            ->method('flush');

        $this->createSut()->__invoke($bookDto);
    }
}