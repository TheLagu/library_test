<?php

namespace Library\Shared\Middlewares;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class UserAuthorizationMiddleware
{
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        // TODO Comprobar que es el usuario correcto
        return $next($request, $response);
    }
}
