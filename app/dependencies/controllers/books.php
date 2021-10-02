<?php

use Library\Books\Controllers\GetBooksController;
use Library\Books\Controllers\CreateBookController;
use Library\Books\UseCases\CreateBookUseCase;
use Slim\Container;

$container[GetBooksController::class] = function (Container $c) {
    return new GetBooksController();
};
$container[CreateBookController::class] = function (Container $c) {
    return new CreateBookController(
        $c->get(CreateBookUseCase::class)
    );
};