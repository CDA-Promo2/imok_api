<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->get('/');

        $this->assertEquals(
            $this->app->version(), $this->response->getContent()
        );
    }

    public function testCustomers()
    {
//        $this->withoutMiddleware();

//        $response = $this->call('GET', 'api/customers/');
//        $this->assertEquals(401, $response->status());

        $response = $this->get('api/customers');
//        dd($this->response->getContent());
        $response->assertResponseStatus(401);
    }
}
