<?php

namespace App\Console;

use App\Console\Commands\GetCurrencyExchangeRate;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //GetCurrencies::class,
        Commands\GetCurrencyExchangeRate::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        // Run
        $schedule->command('currencies:create')
            ->hourly();
        //->everyMinute();
        //->runInBackground();
        //->emailOutputTo('support@ultainfinity.com');
    }

    /**
     * Get the timezone that should be used by default for scheduled events.
     *
     * @return string
     */
    protected function scheduleTimezone(): string
    {
        return 'Europe/London';
    }
}