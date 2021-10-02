<?php

use Doctrine\ORM\EntityManager;
use Library\Books\Repositories\BooksRepository;
use Library\Books\Entities\Book;
use Slim\Container;


$container[BooksRepository::class] = function (Container $c) {
    return $c->get(EntityManager::class)->getRepository(Book::class);
};
