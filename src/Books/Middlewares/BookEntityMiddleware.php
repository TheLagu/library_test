<?php

namespace Library\Books\UseCases;

use Library\Books\Exceptions\BookNotFoundException;
use Library\Books\Repositories\BooksRepository;
use Library\Shared\Exceptions\UuidNotValidException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;

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
            throw new UuidNotValidException('Invalid uuid ' . $encodedId);
        }

        $book = $this->booksRepository->findOneByEncodedId($encodedId);
        if (is_null($book)) {
            throw new BookNotFoundException("Book {$encodedId} does not exists");
        }

        return $next($request, $response);
    }
}