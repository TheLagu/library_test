<?php

use Library\Books\Controllers\GetBooksController;
use Library\Shared\Middlewares\UserAuthorizationMiddleware;
use Library\Books\Controllers\CreateBookController;

$app->group('/books', function () use ($app): void {
    $app->get('', GetBooksController::class);

    $app->post('', CreateBookController::class);
    //TODO No he puesto ningún tipo de autenticación
});
