<?php

use Library\Books\Controllers\CreateBookController;
use Library\Books\Controllers\DeleteBookController;
use Library\Books\Controllers\GetBooksController;
use Library\Books\Controllers\UpdateBookController;
use Library\Books\UseCases\CreateBookUseCase;
use Library\Books\UseCases\DeleteBookUseCase;
use Library\Books\UseCases\GetBooksUseCase;
use Library\Books\UseCases\UpdateBookUseCase;
use Slim\Container;

$container[CreateBookController::class] = function (Container $c) {
    return new CreateBookController(
        $c->get(CreateBookUseCase::class)
    );
};

$container[UpdateBookController::class] = function (Container $c) {
    return new UpdateBookController(
        $c->get(UpdateBookUseCase::class)
    );
};

$container[DeleteBookController::class] = function (Container $c) {
    return new DeleteBookController(
        $c->get(DeleteBookUseCase::class)
    );
};

$container[GetBooksController::class] = function (Container $c) {
    return new GetBooksController(
        $c->get(GetBooksUseCase::class)
    );
};