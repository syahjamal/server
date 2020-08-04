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

// Generate Application Key
$router->get('/key', function(){
    return str_random(32);
});

//Post register & login
$router->post("/register", "AuthController@register");
$router->post("/login", "AuthController@login");

//Create & Get Data User
$router->post("/create", "DataUserController@create");
$router->get("/users", "DataUserController@index");
$router->get("/users/{id}", "DataUserController@show");

//Update Data
$router->put('/users/{id}', 'DataUserController@update');

//Delete
$router->delete("users/{id}", "DataUserController@destroy");