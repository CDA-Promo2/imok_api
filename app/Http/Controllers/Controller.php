<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * @OA\Swagger(
 *    @OA\Info(
 *      title="IMOK Api",
 *      version="0.1.0",
 *      description = "This API is meant to be used along with the IMOK_Mobile and IMOK_Desktop apps. All routes require you to be authenticated, so your first step is to send your credentials to api/auth/login and get a bearer token.",
 *    )
 *    @OA\Server(
 *         description="API BASE PATH",
 *         url="http://imok.am.manusien-ecolelamanu.fr/imok_api/current/public/api"
 *    )
 * )
 * @OA\SecurityScheme(
 *   securityScheme="api_key",
 *   type="apiKey",
 *   in="header",
 *   name="JWT",
 *   description= "JWT Auth Token"
 * )
 */
class Controller extends BaseController
{
    //
}
