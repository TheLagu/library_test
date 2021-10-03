<?php

use Library\Books\UseCases\CreateBookUseCase;
use Library\Books\Repositories\BooksRepository;
use Library\Books\UseCases\UpdateBookUseCase;
use Library\Books\UseCases\DeleteBookUseCase;
use Library\Books\UseCases\GetBooksUseCase;
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

$container[DeleteBookUseCase::class] = function (Container $c) {
    return new DeleteBookUseCase(
        $c->get(BooksRepository::class)
    );
};

$container[GetBooksUseCase::class] = function (Container $c) {
    return new GetBooksUseCase(
        $c->get(BooksRepository::class)
    );
};

