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

/** @var \Laravel\Lumen\Routing\Router $router */
$router->get('/', function () use ($router) {
    return 'Example application rule based checkout.';
});

// get list of rules
$router->get('/rules/',         'Rules\Index@index');

// get specific rule
$router->get('/rules/{id}',     'Rules\Get@index');

// create new rule
$router->post(  '/rules/',      'Rules\Post@index');

// update specific rule
$router->put(   '/rules/{id}',  'Rules\Put@index');

// delete rule
$router->delete('/rules/{id}',  'Rules\Delete@index');

// perform checkout
$router->post('/checkout/',     'Checkout\Post@index');
