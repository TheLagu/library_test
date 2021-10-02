<?php

use Library\Books\UseCases\CreateBookUseCase;
use Library\Books\Repositories\BooksRepository;
use Slim\Container;

$container[CreateBookUseCase::class] = function (Container $c) {
    return new CreateBookUseCase(
        $c->get(BooksRepository::class)
    );
};

