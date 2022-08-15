<?php

namespace App\Api\V1\Controllers\Public;

use App\Api\V1\Controllers\Controller;
use App\Models\ExchangeRate as History;
use App\Services\CurrencyExchange\CoinMarketCapExchange;
use App\Models\Currency;
use Illuminate\Support\Carbon;

class ExchangeRateController extends Controller
{
    //tregger request to coinmarketcap.com
    public function index()
    {
        $response = null;
        $log = [];
        try {
            //Get all currencies
            $getStringCurrencies = $this->getAllCurrencies();
            // $getStringCurrencies = $this->currencySymbols(1);

            // clear old rates
            History::truncate();

            //Send request
            foreach ($getStringCurrencies as $currency) {
                $response = CoinMarketCapExchange::getExchangeRate($currency->currency_code); //'usd', 'EUR', 'UAH'
                //Get Provider name
                $provider = CoinMarketCapExchange::providerName();
                //log request
                $log[] = $this->logHistory($response, $provider);
            }
            return response()->jsonApi([
                'type'      => 'success',
                'title'     => 'Get Coin Market Cap',
                'message'   => "Successfully get Coin Market Cap exchange rate",
                'data'      => $log
            ], 200);
        } catch (\Exception $e) {
            return response()->jsonApi([
                'type'      => 'danger',
                'title'     => 'Get Coin Market Cap',
                'message'   => $e->getMessage(),
                'data'      => []
            ], 400);
        }
    }

    //Get all Currencies
    public function currencySymbols($currency_type_id = 2)
    {
        $getAllSymbols  = null;
        $currencyArray  = [];
        $allCurrencies = Currency::where('type_id', $currency_type_id)->get(['code as currency_code']);
        // if ($allCurrencies) {
        //     foreach ($allCurrencies as $value) {
        //         $currencyArray[] = $value->currency_code; //get all currencies symbols
        //     }
        //     $getAllSymbols = implode(', ', $currencyArray); //add comman - returns string
        // }
        return $allCurrencies; //$getAllSymbols;
    }

    //Get all Currencies
    public function getAllCurrencies()
    {
        $allCurrencies = Currency::get(['code as currency_code']);
        return $allCurrencies;
    }

    //log history
    public function logHistory($response = [], $provider = null)
    {
        $rate = [];
        $time = [];
        $is_saved = [];
        if ($response) {
            try {
                    foreach ($response['data'] as $value) {
                        foreach ($value['quote'] as $val) {
                            $rate[] = (isset($val['price']) ? $val['price'] : '0');
                            $time[] = (isset($val['last_updated']) ? $val['last_updated'] : '');
                        }
                        $is_saved = History::updateOrCreate(['currency' => $value['symbol']], [
                            'currency_name'     => $value['name'],
                            'currency'          => $value['symbol'],
                            'rate'              => $rate[0],
                            'time'              => $time[0],
                            'last_updated'      => Carbon::parse($time[0]),
                            'provider'          => $provider,
                            'data'              => json_encode($response),
                            'symbol'            => $value['symbol']
                        ]);
                }
            } catch (\Exception $e) {
                return response()->jsonApi([
                    'type'      => 'danger',
                    'title'     => 'Error occurred when Logging Coin Market Cap exchange rate',
                    'message'   => $e->getMessage(),
                    'data'      => []
                ], 400);
            }
        }
        return $is_saved;
    }
}
