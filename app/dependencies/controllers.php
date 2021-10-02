<?php

use Library\Books\Controllers\GetBooksController;
use Slim\Container;

$container[GetBooksController::class] = function (Container $c) {
    return new GetBooksController();
};