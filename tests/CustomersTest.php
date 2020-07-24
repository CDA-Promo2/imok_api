<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class CustomersTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test d'accès aux clients en ignorant le middleware
     */
    public function testShowAllCustomersWithoutMiddleware()
    {
        $this->withoutMiddleware();

        $response = $this->get('api/customers');
//        dd($this->response->getContent());
        $response->assertResponseStatus(200);
    }

    /**
     * Test d'accès aux clients en étant connecté
     */
    public function testShowAllCustomers()
    {
        // Utilise la factory présente dans database/factories/ModelFactory.php afin de générer un utilisateur
        $user = factory('App\Models\Employee')->make();

        // actingAs : méthode permettant de s'indentifier avec un utilisateur
        $response = $this->actingAs($user)
            ->get('/api/estates');
        $response->assertResponseStatus(200);
    }

    /**
     * Test d'accès aux clients sans être conne cté
     */
    public function testShowAllCustomersWithoutLogin()
    {
        $response = $this->get('api/customers');
        $response->assertResponseStatus(401);
    }

    /**
     * Test de création d'un nouveau client
     */
    public function testCreateCustomer()
    {
        // On récupère les attributs d'un bien généré par la factory
        $customer = (factory('App\Models\Customer')->make())->getAttributes();
        $user = factory('App\Models\Employee')->make();

        // actingAs : méthode permettant de s'indentifier avec un utilisateur
        $this->actingAs($user)
            ->json('POST', 'api/customers', $customer)
            ->seeJson(["message" => "CREATED"]);
    }
}
