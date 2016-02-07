<?php

namespace Lzakrzewski\tests;

use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\RequestBody;
use Slim\Http\Response;
use Slim\Http\Uri;

abstract class ApiTestCase extends \PHPUnit_Framework_TestCase
{
    /** @var Response */
    private $response;
    /** @var App */
    private $app;

    protected function request($method, $url, array $requestParameters = [])
    {
        $request = $this->prepareRequest($method, $url, $requestParameters);
        $response = new Response();

        $app = $this->app;
        $this->response = $app($request, $response);
    }

    protected function assertThatResponseHasStatus($expectedStatus)
    {
        $this->assertEquals($expectedStatus, $this->response->getStatusCode());
    }

    protected function assertThatResponseHasContentType($expectedContentType)
    {
        $this->assertContains($expectedContentType, $this->response->getHeader('Content-Type'));
    }

    protected function responseData()
    {
        return json_decode((string) $this->response->getBody(), true);
    }

    /** {@inheritdoc} */
    protected function setUp()
    {
        $this->app =  require __DIR__.'/../src/app.php';
    }

    /** {@inheritdoc} */
    protected function tearDown()
    {
        $this->app = null;
        $this->response = null;
    }

    private function prepareRequest($method, $url, array $requestParameters)
    {
        $env = Environment::mock([
            'SCRIPT_NAME' => '/index.php',
            'REQUEST_URI' => $url,
            'REQUEST_METHOD' => $method,
        ]);

        $parts = explode('?', $url);

        if (isset($parts[1])) {
            $env['QUERY_STRING'] = $parts[1];
        }

        $uri = Uri::createFromEnvironment($env);
        $headers = Headers::createFromEnvironment($env);
        $cookies = [];

        $serverParams = $env->all();

        $body = new RequestBody();
        $body->write(json_encode($requestParameters));

        $request = new Request($method, $uri, $headers, $cookies, $serverParams, $body);

        return $request->withHeader('Content-Type', 'application/json');
    }
}
