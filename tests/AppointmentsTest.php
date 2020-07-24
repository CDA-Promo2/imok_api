<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class AppointmentsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test d'accès aux rendez-vous en ignorant le middleware
     */
    public function testShowAllAppointmentsWithoutMiddleware()
    {
        $this->withoutMiddleware();

        $response = $this->get('api/appointments');
//        dd($this->response->getContent());
        $response->assertResponseStatus(200);
    }

    /**
     * Test d'accès aux rendez-vous en étant connecté
     */
    public function testShowAllAppointments()
    {
        // Utilise la factory présente dans database/factories/ModelFactory.php afin de générer un utilisateur
        $user = factory('App\Models\Employee')->make();

        // actingAs : méthode permettant de s'indentifier avec un utilisateur
        $response = $this->actingAs($user)
            ->get('/api/appointments');
        $response->assertResponseStatus(200);
    }

    /**
     * Test d'accès aux rendez-vous sans être connecté
     */
    public function testShowAllAppointmentsWithoutLogin()
    {
        $response = $this->get('api/appointments');
        $response->assertResponseStatus(401);
    }

    /**
     * Test de création d'un nouveau rendez-vous
     */
    public function testCreateAppointment()
    {
        // On récupère les attributs d'un rendez-vous généré par la factory
        $appointment = (factory('App\Models\Appointment')->make())->getAttributes();
        $user = factory('App\Models\Employee')->make();

        // actingAs : méthode permettant de s'indentifier avec un utilisateur
        $this->actingAs($user)
            ->json('POST', 'api/appointments', $appointment)
            ->seeJson(["message" => "CREATED"]);
    }
}
