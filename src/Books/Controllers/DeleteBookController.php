<?php

namespace Library\Books\Controllers;

use Library\Books\UseCases\DeleteBookUseCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\StatusCode;

class DeleteBookController
{
    private DeleteBookUseCase $useCase;

    public function __construct(DeleteBookUseCase $useCase)
    {
        $this->useCase = $useCase;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->useCase->__invoke($request->getAttribute('book'));

        return $response
            ->withStatus(StatusCode::HTTP_NO_CONTENT);
    }
}
