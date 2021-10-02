<?php

use Library\Shared\Middlewares\UserAuthorizationMiddleware;
use Slim\Container;

$container[UserAuthorizationMiddleware::class] = function (Container $c) {
    return new UserAuthorizationMiddleware();
};
