<?php

use Library\Books\Repositories\BooksRepository;
use Library\Books\Middlewares\BookEntityMiddleware;
use Slim\Container;

$container[BookEntityMiddleware::class] = function (Container $c) {
    return new BookEntityMiddleware(
        $c->get(BooksRepository::class)
    );
};

