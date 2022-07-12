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
        //
    });
    /**
     * Currencies exchange rates from CoinMarketCap
     */
    $router->group([
        'namespace' => 'Public',
        'prefix' => 'coinmarketcap'
    ], function ($router) {
        $router->get('/exchange-rates', 'CoinMarketCapController@index');
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
            $router->get('rate', 'CurrencyController@getRate');
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
     * Admin / super admin access level (E.g CEO company)
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
        $router->get('/rate', 'CoinMarketCapExchangeHistoryController@index');
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
