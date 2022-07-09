<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Api\V1\Controllers\Public\CoinMarketCapExchangeHistoryController;

class GetCurrencyExchangeRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currencies:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command get currency exchange rate and log to history table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //Make an API Call to coinMarketCap and log the exchange rate to History.
        $getMethod = new CoinMarketCapExchangeHistoryController;
        $getMethod->index();
    }
}