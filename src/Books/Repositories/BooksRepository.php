<?php

namespace Library\Books\Repositories;

use Doctrine\ORM\EntityRepository;
use Library\Books\DTOs\BookDto;
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

    public function findBooks(BookDto $dto, ?int $page, ?int $limit, ?array $sorting = ['id' => 'DESC']): array
    {
        $criteria = $this->getCriteria($dto);

        return $this->findBy(
            $criteria,
            !is_null($sorting)? $sorting: ['id' => 'DESC'],
            !is_null($limit)? $limit: 10,
            !is_null($page)? $page - 1 : 0,
        );
    }

    private function getCriteria(BookDto $dto): array
    {
        $criteria = [];

        if (!is_null($dto->getTitle())) {
            $criteria['title'] = $dto->getTitle();
        }
        if (!is_null($dto->getIsbn())) {
            $criteria['isbn'] = $dto->getIsbn();
        }
        if (!is_null($dto->getPages())) {
            $criteria['pages'] = $dto->getPages();
        }
        if (!is_null($dto->getTopic())) {
            $criteria['topic'] = $dto->getTopic();
        }
        if (!is_null($dto->getDescription())) {
            $criteria['description'] = $dto->getDescription();
        }
        if (!is_null($dto->getEncodedId())) {
            $criteria['encoded_id'] = $dto->getEncodedId();
        }

        return $criteria;
    }
}