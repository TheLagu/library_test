<?php

namespace Library\Books\Controllers;

use Library\Books\DTOs\BookDto;
use Library\Books\UseCases\GetBooksUseCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator;
use Slim\Http\StatusCode;

class GetBooksController
{
    private GetBooksUseCase $useCase;

    public function __construct(GetBooksUseCase $useCase)
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

        $books = $this->useCase->__invoke(
            BookDto::createFromArray($params),
            $params['query_page']?? null,
            $params['query_limit']?? null,
            $params['query_sorting']?? null,
        );

        return $response
            ->withJson($books)
            ->withStatus(StatusCode::HTTP_OK);
    }

    private function validate(array $params): array
    {
        //TODO habría que devolver el error concreto
        $errors = [];
        if (array_key_exists('query_page', $params) && !Validator::intVal()->min(0)->notEmpty()->validate($params['query_page'])) {
            $errors[] = ['query_page: int, min 1'];
        }

        if (array_key_exists('query_limit', $params) && !Validator::stringType()->length(1)->notEmpty()->validate($params['query_limit'])) {
            $errors[] = ['query_limit: int, min 1'];
        }

        return $errors;
    }
}
