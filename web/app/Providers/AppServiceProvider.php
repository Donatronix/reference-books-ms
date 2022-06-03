<?php

namespace App\Providers;

use App\Contracts\CurrencyRepositoryContract;
use App\Repositories\CurrencyRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CurrencyRepositoryContract::class, function(){
            return CurrencyRepository::getInstance();
        });
    }

    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
