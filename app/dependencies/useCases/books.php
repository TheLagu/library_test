<?php

use Library\Books\UseCases\CreateBookUseCase;
use Library\Books\Repositories\BooksRepository;
use Library\Books\UseCases\UpdateBookUseCase;
use Slim\Container;

$container[CreateBookUseCase::class] = function (Container $c) {
    return new CreateBookUseCase(
        $c->get(BooksRepository::class)
    );
};

$container[UpdateBookUseCase::class] = function (Container $c) {
    return new UpdateBookUseCase(
        $c->get(BooksRepository::class)
    );
};

