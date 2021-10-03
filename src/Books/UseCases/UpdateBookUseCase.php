<?php

namespace Library\Books\UseCases;

use Library\Books\DTOs\BookDto;
use Library\Books\Entities\Book;
use Library\Books\Exceptions\BookAlreadyExistsException;
use Library\Books\Repositories\BooksRepository;

class UpdateBookUseCase
{
    private BooksRepository $booksRepository;

    public function __construct(BooksRepository $booksRepository)
    {
        $this->booksRepository = $booksRepository;
    }

    public function __invoke(Book $book, BookDto $dto): Book
    {
        if ($this->bookAlreadyExists($book, $dto)) {
            throw new BookAlreadyExistsException('ISBN already exists');
        }

        // Se podría pasar el dto a la entidad
        $book->update(
            $dto->getTitle(),
            $dto->getIsbn(),
            $dto->getPages(),
            $dto->getTopic(),
            $dto->getDescription(),
        );

        $this->booksRepository->flush();

        return $book;
    }

    private function bookAlreadyExists(Book $book, BookDto $dto): bool
    {
        if (!is_null($dto->getIsbn())) {
            return false;
        }

        if ($book->getIsbn() === $dto->getIsbn()) {
            return false;
        }

        if (!$this->booksRepository->existsByISBN($dto->getIsbn())) {
            return false;
        }

        return true;
    }
}
