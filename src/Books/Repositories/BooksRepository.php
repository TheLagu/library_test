<?php

namespace Library\Books\Repositories;

use Doctrine\ORM\EntityRepository;
use Library\Books\Entities\Book;
use Library\Books\Exceptions\BookNotFoundException;

class BooksRepository extends EntityRepository
{
    public function existsByISBN(string $isbn): bool
    {
        return $this->count(['isbn' => $isbn]) > 0;
    }

    public function findOneByEncodedId(string $encodedId): ?Book
    {
        return $this->findOneBy(['encodedId' => $encodedId]);
    }

    public function persist(Book $book): void
    {
        $this->_em->persist($book);
    }

    public function flush(): void
    {
        $this->_em->flush();
    }
}