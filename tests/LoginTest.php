<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * TEST CONNEXION
     */
    public function testLogin()
    {
        $parameters = [
            'mail' => 'armand.deziel@imok.fr',
            'password' => 'armand',
        ];

        /**
         * Test simplement le status de la réponse
         */
//        $response = $this->post('api/auth/login',$parameters);
//        $response->assertResponseStatus(200);

        /**
         * Vérifie si le JSON renvoyé correspond
         */

        $this->json('POST', 'api/auth/login', $parameters)
            ->seeJsonStructure([
                "access_token",
                "token_type",
                "expires_in",
                "user" => [
                    "id",
                    "firstname",
                    "lastname",
                    "phone",
                    "mail",
                    "id_roles",
                    "first_connection",
                    "active"
                ]
            ])
        ;
    }

    public function testFailLogin()
    {
        $parameters = [
            'mail' => 'faux.mail@imok.fr',
            'password' => 'pasLeBonMdp',
        ];

        $this->json('POST', 'api/auth/login', $parameters)
            ->seeJson(["error" => "Unauthorized"]);
    }

    public function testLogout()
    {
        $parameters = [
            'mail' => 'armand.deziel@imok.fr',
            'password' => 'armand',
        ];
        $this->post('api/auth/login',$parameters);

        $this->json('POST', 'api/auth/logout')
            ->seeJson(["message" => "Successfully logged out"]);
    }
}
