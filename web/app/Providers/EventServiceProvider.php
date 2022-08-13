<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        /**
         * Get Currency List Request
         */
        'GetCurrencyRequest' => [
            'App\Listeners\GetCurrencyRequestListener',
        ],

        'CreateCurrency' => [
            'App\Listeners\CreateCurrencyListener',
        ],
        //
        'ProductCreate' => [
            'App\Listeners\ProductCreateListener'
        ],
    ];

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
