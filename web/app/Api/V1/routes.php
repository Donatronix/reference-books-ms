<?php

/**
 * @var Laravel\Lumen\Routing\Router $router
 */
$router->group([
    'prefix' => env('APP_API_VERSION', ''),
    'namespace' => '\App\Api\V1\Controllers',
    'middleware' => 'checkUser'
], function ($router) {
    /**
     * Currencies for clients
     */
    $router->group(['prefix' => 'currencies'], function ($router) {
        $router->get('/', 'CurrencyController@index');
        $router->get('reference', 'CurrencyController@reference');
        $router->get('rate', 'CurrencyController@getRate');
        $router->get('getCurrencies', 'CurrencyController@getCurrencies');
    });

    /**
     * ADMIN PANEL
     */
    $router->group([
        'prefix' => 'admin',
        'namespace' => 'Admin',
        'middleware' => 'checkAdmin'
    ], function ($router) {
        /**
         * Currencies Management
         */
        $router->group(['prefix' => 'currencies'], function ($router) {
            $router->post('/', 'CurrencyController@store');
            $router->post('/{id:[\d]+}/update-status', 'CurrencyController@updateStatus');
        });
    });
});

