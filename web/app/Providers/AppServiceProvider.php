<?php

namespace App\Providers;

use App\Contracts\CurrencyRepositoryContract;
use App\Repositories\CurrencyRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CurrencyRepositoryContract::class, function () {
            return CurrencyRepository::getInstance();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(199);
    }
}
