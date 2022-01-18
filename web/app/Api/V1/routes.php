<?php

/**
 * @var Laravel\Lumen\Routing\Router $router
 */
$router->group([
    'prefix' => env('APP_API_VERSION', ''),
    'namespace' => '\App\Api\V1\Controllers',
    'middleware' => 'checkUser'
], function ($router) {

    $router->group(['prefix' => 'currencies'], function ($router) {
        $router->get('/', 'CurrenciesController@index');
        $router->post('/{id:[\d]+}/update-status', 'CurrenciesController@updateStatus');
    });

    $router->get('/currencies', 'CurrenciesController@reference');

    /**
     * ADMIN PANEL
     */
    $router->group([
        'prefix' => 'admin',
        'namespace' => 'Admin',
        'middleware' => 'checkAdmin'
    ], function ($router) {

        $router->group(['prefix' => 'currencies'], function ($router) {
            $router->post('/', 'CurrenciesController@store');
        });

    });
});
