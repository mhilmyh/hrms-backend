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

$router->group(['prefix' => 'api'], function () use ($router) {
    // Auth
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('/login', ['uses' => 'AuthController@login']);
        $router->post('/logout', ['uses' => 'AuthController@logout']);
        $router->get('/user', ['uses' => 'AuthController@user']);
        $router->post('/register', ['uses' => 'AuthController@register']);
    });

    // User
    $router->group(['prefix' => 'user'], function () use ($router) {
        $router->get('/', ['uses' => 'UserController@index']);
        $router->put('/edit', ['uses' => 'UserController@update']);
        $router->delete('/delete', ['uses' => 'UserController@delete']);
    });

    // Timesheet
    $router->group(['prefix' => 'timesheet'], function () use ($router) {
        $router->get('/', ['uses' => 'TimesheetController@index']);
        $router->post('/', ['uses' => 'TimesheetController@create']);
        $router->delete('/', ['uses' => 'TimesheetController@delete']);
    });

    // Company
    $router->group(['prefix' => 'company'], function () use ($router) {
        $router->get('/', ['uses' => 'CompanyController@index']);
        $router->post('/', ['uses' => 'CompanyController@create']);
        $router->put('/', ['uses' => 'CompanyController@update']);
        $router->delete('/', ['uses' => 'CompanyController@delete']);
    });

    // Images
    $router->group(['prefix' => 'image'], function () use ($router) {
        $router->post('/create', ['uses' => 'ImageController@create']);
        $router->delete('/delete', ['uses' => 'ImageController@delete']);
    });
});
