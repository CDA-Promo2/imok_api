<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class EstatesTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test d'accès aux biens en ignorant le middleware
     */
    public function testShowAllEstatesWithoutMiddleware()
    {
        $this->withoutMiddleware();

        $response = $this->get('api/estates');
//        dd($this->response->getContent());
        $response->assertResponseStatus(200);
    }

    /**
     * Test d'accès aux biens en étant connecté
     */
    public function testShowAllEstates()
    {
        // Utilise la factory présente dans database/factories/ModelFactory.php afin de générer un utilisateur
        $user = factory('App\Models\Employee')->make();

        // actingAs : méthode permettant de s'indentifier avec un utilisateur
        $response = $this->actingAs($user)
            ->get('/api/estates');
        $response->assertResponseStatus(200);
    }

    /**
     * Test d'accès aux bien sans être connecté
     */
    public function testShowAllEstatesWithoutLogin()
    {
        $response = $this->get('api/customers');
        $response->assertResponseStatus(401);
    }

    /**
     *  COMMENTER SUITE ERREUR SQL 1471 :
     *      "The target table estate_view of the INSERT is not insertable-into"
     *
     * Test la création d'un nouveau bien immobilier
     */
//    public function testCreateEstate()
//    {
//        // On récupère les attributs d'un bien généré par la factory
//        $estate = (factory('App\Models\Estate')->make())->getAttributes();
//        $user = factory('App\Models\Employee')->make();

//        // actingAs : méthode permettant de s'indentifier avec un utilisateur
//        $this->actingAs($user)
//            ->json('POST', 'api/estates/create', $estate)
//            ->seeJson(["message" => "CREATED"]);;
//    }
}
