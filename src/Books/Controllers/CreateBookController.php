<?php

namespace Library\Books\Controllers;

use Library\Books\DTOs\BookDto;
use Library\Books\Exceptions\BookAlreadyExistsException;
use Library\Books\UseCases\CreateBookUseCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator;
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

        $errors = $this->validate($params);

        if (!empty($errors)) {
            return $response
                ->withJson(['error' => $errors])
                ->withStatus(StatusCode::HTTP_BAD_REQUEST);
        }

        try {
            $book = $this->useCase->__invoke(BookDto::createFromArray($params));
        } catch (BookAlreadyExistsException $e) {
            return $response
                ->withJson(['error' => [$e->getMessage()]])
                ->withStatus(StatusCode::HTTP_BAD_REQUEST);
        }

        return $response
            ->withJson(['id' => $book->getEncodedId()])
            ->withStatus(StatusCode::HTTP_CREATED);
    }

    private function validate(array $params): array
    {
        //TODO habría que devolver el error concreto
        $errors = [];
        if (!Validator::intVal()->min(1)->notOptional()->notEmpty()->validate($params['pages']?? null)) {
            $errors[] = ['pages: int, min 1, required'];
        }

        if (!Validator::stringType()->length(10, 13)->notEmpty()->notOptional()->validate($params['isbn']?? null)) {
            $errors[] = ['isbn: string, length 10 to 13, required'];
        }

        if (!Validator::stringType()->length(1)->notEmpty()->notOptional()->validate($params['title']?? null)) {
            $errors[] = ['title: string, min length 1, required'];
        }

        if (!Validator::stringType()->length(1)->validate($params['topic']?? null)) {
            $errors[] = ['topic: string, min length 1'];
        }

        if (!Validator::stringType()->length(1)->validate($params['description']?? null)) {
            $errors[] = ['description: string, min length 1'];
        }

        return $errors;
    }
}
