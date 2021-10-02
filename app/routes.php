<?php

use Library\Books\Controllers\GetBooksController;

$app->group('/books', function () use ($app): void {
    $app->get('', GetBooksController::class);
    //TODO No he puesto ningún tipo de autenticación
});
