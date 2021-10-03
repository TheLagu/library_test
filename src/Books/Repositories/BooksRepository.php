<?php

namespace Library\Books\Repositories;

use Doctrine\ORM\EntityRepository;
use Library\Books\Entities\Book;

class BooksRepository extends EntityRepository
{
    public function existsByISBN(string $isbn): bool
    {
        return $this->count(['isbn' => $isbn]) > 0;
    }

    public function findOneByEncodedId(string $encodedId): ?Book
    {
        return $this->findOneBy(['encoded_id' => $encodedId]);
    }

    public function persist(Book $book): void
    {
        $this->_em->persist($book);
    }

    public function flush(): void
    {
        $this->_em->flush();
    }

    public function remove(Book $book): void
    {
        $this->_em->remove($book);
    }
}