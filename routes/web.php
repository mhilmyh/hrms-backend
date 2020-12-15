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
    // Dashboard
    $router->group(['prefix' => 'dashboard'], function () use ($router) {
        $router->get('/', ['uses' => 'GeneralController@dashboard']);
        $router->delete('/rating', ['uses' => 'GeneralController@reset']);
    });

    // Auth
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('/login', ['uses' => 'AuthController@login']);
        $router->post('/logout', ['uses' => 'AuthController@logout']);
        $router->post('/register', ['uses' => 'AuthController@register']);
        $router->get('/user', ['uses' => 'AuthController@user']);
        $router->get('/notification', ['uses' => 'AuthController@notification']);
        $router->delete('/notification/{id}', ['uses' => 'AuthController@clear']);
    });

    // Company
    $router->group(['prefix' => 'company'], function () use ($router) {
        $router->get('/', ['uses' => 'CompanyController@index']);
        $router->get('/offices', ['uses' => 'CompanyController@offices']);
        $router->get('/departments', ['uses' => 'CompanyController@departments']);
        $router->post('/{identifier}', ['uses' => 'CompanyController@create']);
        $router->put('/{identifier}', ['uses' => 'CompanyController@update']);
        $router->delete('/{identifier}/{id}', ['uses' => 'CompanyController@delete']);
    });

    // Image
    $router->group(['prefix' => 'image'], function () use ($router) {
        $router->post('/profile', ['uses' => 'ImageController@profile']);
        $router->post('/', ['uses' => 'ImageController@create']);
        $router->delete('/', ['uses' => 'ImageController@delete']);
    });

    // Timesheet
    $router->group(['prefix' => 'timesheet'], function () use ($router) {
        $router->get('/', ['uses' => 'TimesheetController@index']);
        $router->put('/approve/{id}', ['uses' => 'TimesheetController@approve']);
        $router->post('/', ['uses' => 'TimesheetController@create']);
        $router->delete('/clear', ['uses' => 'TimesheetController@clear']);
        $router->delete('/{id}', ['uses' => 'TimesheetController@delete']);
    });

    // User
    $router->group(['prefix' => 'user'], function () use ($router) {
        $router->get('/', ['uses' => 'UserController@index']);
        $router->put('/{id}', ['uses' => 'UserController@update']);
        $router->delete('/{id}', ['uses' => 'UserController@delete']);
    });
});
