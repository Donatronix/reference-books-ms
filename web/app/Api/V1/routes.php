<?php


/**
 * @var Laravel\Lumen\Routing\Router $router
 */
$router->group([
    'prefix' => env('APP_API_VERSION', ''),
    'namespace' => '\App\Api\V1\Controllers'
], function ($router) {
    /**
     * PUBLIC ACCESS
     */
    /**
     * Currencies exchange rates from CoinMarketCap
     */
    $router->group([
        'prefix' => 'coinmarketcap'
    ], function ($router) {
        $router->get('/exchange-rates', 'CoinMarketCapController@index');
    });

    /**
     * USER APPLICATION ACCESS
     */
    $router->group([
        'middleware' => 'checkUser'
    ], function ($router) {
        /**
         * Currencies for clients
         */
        $router->group([
            'prefix' => 'currencies'
        ], function ($router) {
            $router->get('/', 'CurrencyController@index');
            $router->get('rate', 'CurrencyController@getRate');
            // $router->get('codes', 'CurrencyController@codes');
        });
    });

    /**
     * ADMIN PANEL ACCESS
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
        $router->group([
            'prefix' => 'currencies'
        ], function ($router) {
            $router->get('/', 'CurrencyController@index');
            $router->post('/', 'CurrencyController@store');
            $router->post('/{id:[\d]+}/update-status', 'CurrencyController@updateStatus');
        });
    });


    //Get Coin market cap exchange rate
    $router->get('/currency-rate', 'LogExchangeRateController@index');
});