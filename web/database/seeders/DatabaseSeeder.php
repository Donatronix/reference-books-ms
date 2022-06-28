<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use App\Api\V1\Controllers\CoinMarketCapExchangeHistoryController;



class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */


    // public function makeReq()
    // {
    //     $capRate = new CoinMarketCapExchangeHistoryController;

    //     $response = $capRate->currencyRate();
    //     return $response;
    // }

    public function run(): void
    {

        $this->call([
            /**
             * Currencies
             */
            CurrencyTypesTableSeeder::class,
            CurrenciesTableSeeder::class

            //$this->makeReq(),
        ]);


        // Seeds for local and staging
        if (App::environment(['local', 'staging'])) {
            //
        }
    }
}