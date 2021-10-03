<?php

namespace Library\Books\UseCases;

use Library\Books\DTOs\BookDto;
use Library\Books\Repositories\BooksRepository;

class GetBooksUseCase
{
    private BooksRepository $booksRepository;

    public function __construct(BooksRepository $booksRepository)
    {
        $this->booksRepository = $booksRepository;
    }

    public function __invoke(BookDto $dto, ?int $page, ?int $limit, ?array $sorting): array
    {
        $books = $this->booksRepository->findBooks(
            $dto,
            $page,
            $limit,
            $sorting
        );

        return $books;
    }
}
