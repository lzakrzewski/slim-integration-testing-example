<?php

require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

$app = new \Slim\App;

//List of posts
$app->get('/posts', function (ServerRequestInterface $request, ResponseInterface $response) {
    $posts = [
        [
            'title' => 'Example post 1',
            'content' => 'Aliquam erat volutpat.',
        ],
        [
            'title' => 'Example post 2',
            'content' => 'Vestibulum suscipit nulla quis orci.',
        ],
        [
            'title' => 'Example post 3',
            'content' => 'Phasellus magna.',
        ],
        [
            'title' => 'Example post 4',
            'content' => 'Sed augue ipsum, egestas nec, vestibulum et, malesuada adipiscing, dui.',
        ]
    ];

    $query = $request->getQueryParams();

    if (isset($query['page']) && $query['page'] >= 1) {
        $page = $query['page'];

        $postsPerPage = 2;

        $offset = ($page-1) * $postsPerPage;
        $length = $postsPerPage;

        $posts = array_slice($posts, $offset, $length);
    }

    $responseBody = $response->getBody();
    $responseBody->write(json_encode($posts));

    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200)
        ->withBody($responseBody);
});

//Add new post
$app->put('/posts/{postId}', function (ServerRequestInterface $request, ResponseInterface $response, $args = []) {
    $postId = $args['postId'];

    $responseBody = $response->getBody();
    $responseBody->write(json_encode(['id' => $postId]));

    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201)
        ->withBody($responseBody);
});

return $app;