<?php

namespace Library\Books\UseCases;

use Library\Books\DTOs\BookDto;
use Library\Books\Entities\Book;
use Library\Books\Exceptions\BookAlreadyExistsException;
use Library\Books\Repositories\BooksRepository;

class CreateBookUseCase
{
    private BooksRepository $booksRepository;

    public function __construct(BooksRepository $booksRepository)
    {
        $this->booksRepository = $booksRepository;
    }

    public function __invoke(BookDto $dto): Book
    {
        if ($this->booksRepository->existsByISBN($dto->getIsbn())) {
            throw new BookAlreadyExistsException('ISBN already exists');
        }

        $book = Book::create(
            $dto->getTitle(),
            $dto->getIsbn(),
            $dto->getPages(),
            $dto->getTopic(),
            $dto->getDescription(),
        );

        $this->booksRepository->persist($book);
        $this->booksRepository->flush();

        return $book;
    }
}
