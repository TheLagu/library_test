<?php

namespace Library\Books\Controllers;

use Library\Books\DTOs\BookDto;
use Library\Books\Exceptions\BookAlreadyExistsException;
use Library\Books\UseCases\CreateBookUseCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\StatusCode;

class CreateBookController
{
    private CreateBookUseCase $useCase;

    public function __construct(CreateBookUseCase $useCase)
    {
        $this->useCase = $useCase;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getParams();
        //Validar parámetros

        try {
            $book = $this->useCase->__invoke(BookDto::createFromArray($params));
        } catch (BookAlreadyExistsException $e) {
            return $response
                ->withJson($e->getMessage())
                ->withStatus(StatusCode::HTTP_BAD_REQUEST);
        }

        return $response
            ->withJson(['id' => $book->getEncodedId()])
            ->withStatus(StatusCode::HTTP_CREATED);
    }
}
