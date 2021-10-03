<?php

namespace Library\Books\Middlewares;

use Library\Books\Repositories\BooksRepository;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;
use Slim\Http\StatusCode;

class BookEntityMiddleware
{
    private BooksRepository $booksRepository;

    public function __construct(BooksRepository $booksRepository)
    {
        $this->booksRepository = $booksRepository;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        $encodedId = $request->getAttribute('route')->getArgument('id');

        if (!Uuid::isValid($encodedId))
        {
            return $response
                ->withJson(['error' => 'Invalid uuid (' . $encodedId . ')'])
                ->withStatus(StatusCode::HTTP_BAD_REQUEST);
        }

        $book = $this->booksRepository->findOneByEncodedId($encodedId);
        if (is_null($book)) {
            return $response
                ->withJson(['error' => ["Book {$encodedId} does not exists"]])
                ->withStatus(StatusCode::HTTP_BAD_REQUEST);
        }

        $request = $request->withAttribute('book', $book);

        return $next($request, $response);
    }
}