<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->group(['prefix' => 'api'], function () use ($router) {

    /**
     * ROUTES FOR CUSTOMERS
     */
    $router->get('customers','CustomerController@getAll');
    $router->get('customers/{id}', 'CustomerController@getOneById');
    $router->get('customers/find/{arg}', 'CustomerController@getWhere');
    $router->post('customers','CustomerController@create');
});
