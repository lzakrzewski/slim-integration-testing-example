<?php

namespace Lucaszz\tests;

class ApiTest extends WebTestCase
{
    /** @test */
    public function it_adds_new_post()
    {
        $this->request('PUT', '/posts/1', ['title' => 'A new blog post', 'content' => 'Hello world']);

        $this->assertThatResponseHasStatus(201);
        $this->assertThatResponseHasContentType('application/json');
        $this->assertArrayHasKey('id', $this->responseData());
    }

    /** @test */
    public function it_has_list_of_posts()
    {
        $this->request('GET', '/posts');

        $this->assertThatResponseHasStatus(200);
        $this->assertThatResponseHasContentType('application/json');
        $this->assertCount(4, $this->responseData());
    }

    /** @test */
    public function it_paginates_list_of_posts()
    {
        $this->request('GET', '/posts?page=2');

        $this->assertThatResponseHasStatus(200);
        $this->assertThatResponseHasContentType('application/json');
        $this->assertCount(2, $this->responseData());
    }
}
