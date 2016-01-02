<?php

require __DIR__.'/../vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

//List of posts
$app->get('/posts', function (ServerRequestInterface $request, ResponseInterface $response) {
    return $response;
});

return $app;