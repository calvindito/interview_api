<?php

use App\Models\User;

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('token', function() {
    $query = User::first();
    return response()->json(['token' => $query->createToken('users')->accessToken], 200);
});

$router->group(['middleware' => 'auth'], function() use ($router) {
    $router->post('stock_vehicle', 'EndPointController@stockVehicle');
    $router->post('sale', 'EndPointController@sale');
    $router->post('sale_vehicle', 'EndPointController@saleVehicle');
});
