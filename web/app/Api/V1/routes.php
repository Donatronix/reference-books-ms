<?php

/**
 * @var Laravel\Lumen\Routing\Router $router
 */
$router->group([
    'prefix' => env('APP_API_VERSION', ''),
    'namespace' => '\App\Api\V1\Controllers',
    'middleware' => 'checkUser',
], function ($router) {
    /**
     * Internal access
     */
    $router->group([
        'middleware' => 'checkUser'
    ], function ($router) {
        /**
         * Currencies for clients
         */
        $router->group(['prefix' => 'currencies'], function ($router) {
            $router->get('/', 'CurrencyController@index');
            $router->get('rate', 'CurrencyController@getRate');
            // $router->get('codes', 'CurrencyController@codes');
        });
    });

    /**
     * Currencies exchange rates from CoinMarketCap
     */
    $router->group(['prefix' => 'coinmarketcap'], function ($router) {
        $router->get('/exchange-rates', 'CoinMarketCapController@index');

    });

    /**
     * ADMIN PANEL
     */
    $router->group([
        'prefix' => 'admin',
        'namespace' => 'Admin',
        'middleware' => [
            'checkUser',
            'checkAdmin'
        ]
    ], function ($router) {
        /**
         * Currencies Admin Management
         */
        $router->group(['prefix' => 'currencies'], function ($router) {
            $router->get('/', 'CurrencyController@index');
            $router->post('/', 'CurrencyController@store');
            $router->post('/{id:[\d]+}/update-status', 'CurrencyController@updateStatus');
        });
    });
});
