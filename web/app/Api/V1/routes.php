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
     *
     * level with free access to the endpoint
     */
    $router->group([
        'namespace' => 'Public'
    ], function ($router) {
        /**
         * Currency type for clients
         */
        $router->group([
            'prefix' => 'currencyType'
        ], function ($router) {
            $router->get('/', 'CurrencyTypeController@index');
            $router->get('/{id}', 'CurrencyTypeController@show');
        });

        /**
         * Currencies exchange rates from CoinMarketCap
         */
        $router->group([
            'prefix' => 'coinmarketcap'
        ], function ($router) {
            $router->get('/exchange-rates', 'ExchangeRateController@index');
        });

    });

    /**
     * USER APPLICATION PRIVATE ACCESS
     *
     * Application level for users
     */
    $router->group([
        'namespace' => 'Application',
        'middleware' => 'checkUser'
    ], function ($router) {

        /**
         * Currencies for clients
         */
        $router->group([
            'prefix' => 'currencies'
        ], function ($router) {
            $router->get('/', 'CurrencyController@index');
            $router->get('rates', 'CurrencyController@getRates');
            $router->get('rates/{currency}', 'CurrencyController@getCurrencyRate');
            $router->get('tokens', 'CurrencyController@tokens');
        });

        /**
         * Tokens
         */
        // $router->group([
        //     'prefix' => 'tokens'
        // ], function ($router) {
        //     $router->get('/', 'Admin\CurrencySettingController@index');
        // });
    });

    /**
     * ADMIN PANEL ACCESS
     *
     * Admin | Super admin access level (E.g CEO company)
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
         * Currency type for clients
         */
        $router->group([
            'prefix' => 'currencyType'
        ], function ($router) {
            $router->delete('/', 'CurrencyTypeController@destroy');
            $router->put('/{id}', 'CurrencyTypeController@update');
            $router->post('/', 'CurrencyTypeController@store');
        });

        /**
         * Currencies Admin Management
         */
        $router->group([
            'prefix' => 'currencies'
        ], function ($router) {
            $router->get('/', 'CurrencyController@index');
            $router->post('/', 'CurrencyController@store');
            $router->post('/{id:[\d]+}/update-status', 'CurrencyController@updateStatus');

            /**
             * Settings
             *
             */
            $router->group(['prefix' => 'settings'], function ($router) {
                $router->get('/', 'CurrencySettingController@index');
                $router->post('/', 'CurrencySettingController@store');
                $router->put('{id}', 'CurrencySettingController@update');
                $router->delete('{id}', 'CurrencySettingController@destroy');
            });
        });
    });

    //Get Coin market cap exchange rate
    $router->get('/currency-rate', 'LogExchangeRateController@index');

    //Get Coin market cap exchange rate
    $router->group([
        'namespace' => 'Public',
        'prefix' => 'currency/exchange'
    ], function ($router) {
        $router->get('/rate', 'ExchangeRateController@index');
    });

    /**
     * WEBHOOKS
     *
     * Access level of external / internal software services
     */
    $router->group([
        'prefix' => 'webhooks',
        'namespace' => 'Webhooks'
    ], function ($router) {
        //
    });
});
