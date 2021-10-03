<?php

namespace Library\Books\UseCases;

use Library\Books\Entities\Book;
use Library\Books\Repositories\BooksRepository;

class DeleteBookUseCase
{
    private BooksRepository $booksRepository;

    public function __construct(BooksRepository $booksRepository)
    {
        $this->booksRepository = $booksRepository;
    }

    public function __invoke(Book $book): Book
    {
        $this->booksRepository->remove($book);
        $this->booksRepository->flush();

        return $book;
    }
}
