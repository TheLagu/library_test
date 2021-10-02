<?php

namespace Library\Books\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\StatusCode;

class GetBooksController
{
    public function __construct() {
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $response->withStatus(StatusCode::HTTP_OK);
    }
}
