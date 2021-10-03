<?php

use Library\Books\Controllers\GetBooksController;
use Library\Books\Controllers\CreateBookController;
use Library\Books\Controllers\UpdateBookController;
use Library\Books\Middlewares\BookEntityMiddleware;

$app->group('/books', function () use ($app): void {
    $app->get('', GetBooksController::class);

    $app->post('', CreateBookController::class);

    $app->put('/{id}', UpdateBookController::class)
        ->add(BookEntityMiddleware::class);
    //TODO No he puesto ningún tipo de autenticación
});
