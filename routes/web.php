<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->post('/login', 'AuthController@authenticate');
$router->post('/logout', 'AuthController@logout');

$router->group(['prefix' => '/users'], function () use ($router) {
    $router->post('/', 'UserController@store');
    $router->group(['middleware' => ['auth:api']], function () use ($router) {
        $router->group(['middleware' => ['checkrole:admin']], function () use ($router) {
            $router->get('/', 'UserController@list');
            $router->post('/restore/{id}', 'UserController@restore');
        });
        $router->get('/{id}', 'UserController@list');
        $router->put('/', 'UserController@update');
        $router->delete('/{id}', 'UserController@destroy');
        $router->post('/changePassword', 'UserController@changePassword');
    });
});

$router->group(['prefix' => '/estabelecimento'], function () use ($router) {
    $router->group(['middleware' => ['auth:api']], function () use ($router) {
        $router->group(['middleware' => ['checkrole:admin']], function () use ($router) {
            $router->post('/', 'EstabelecimentoController@store');
            $router->put('/{id}', 'EstabelecimentoController@update');
            $router->delete('/{id}', 'EstabelecimentoController@destroy');
        });
        $router->get('/', 'EstabelecimentoController@index');
        $router->get('/{estabelecimentoId}', 'EstabelecimentoController@index');
    });
});

$router->group(['prefix' => '/nota'], function () use ($router) {
    $router->get('/estabelecimento/{id}', 'NotaController@index');
    $router->get('/estabelecimento', 'NotaController@index');
    $router->get('/estabelecimento/{id}/geral', 'NotaController@notaGeral');
    $router->group(['middleware' => ['auth:api','checkrole:cliente']], function () use ($router) {
        $router->post('/', 'NotaController@storeOrUpdate');
        $router->delete('/{id}', 'NotaController@destroy');
    });
});
