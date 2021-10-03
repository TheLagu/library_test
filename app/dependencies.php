<?php

// DIC configuration
$container = $app->getContainer();

require __DIR__ . '/dependencies/controllers/books.php';
require __DIR__ . '/dependencies/repositories/books.php';
require __DIR__ . '/dependencies/doctrine.php';
require __DIR__ . '/dependencies/useCases/books.php';
require __DIR__ . '/dependencies/middlewares/books.php';
