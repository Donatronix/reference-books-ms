<?php

namespace App\Api\V1\Controllers\Public;

use Illuminate\Http\Request;
use App\Api\V1\Controllers\Controller;
use App\Models\CoinMarketCapExchangeHistory as History;
use Illuminate\Http\JsonResponse;
use App\Services\CurrencyExchange\CoinMarketCapExchange;
use App\Models\Currency;



class CoinMarketCapExchangeHistoryController extends Controller
{
    //tregger request to coinmarketcap.com
    public function index()
    {
        $response = [];
        try {
            //Get all currencies
            $getStringCurrencies = $this->currencySymbols(1);
            //Send request
            foreach ($getStringCurrencies as $currency) {
                $response = CoinMarketCapExchange::getExchangeRate($currency->currency_code); //'usd', 'EUR', 'UAH'
            }
            //Get Provider name
            $provider = CoinMarketCapExchange::providerName();
            //log request
            $log = $this->logHistory($response, $provider);
            return response()->jsonApi([
                'type'      => 'success',
                'title'     => 'Get Coin Market Cap',
                'message'   => "Get Coin Market Cap",
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



    //log history
    public function logHistory($response = [], $provider = null)
    {
        $rate = [];
        $time = [];
        if ($response) {
            foreach ($response['data'] as $value) {
                foreach ($value['quote'] as $val) {
                    $rate[] = $val['price'];
                    $time[] = $val['last_updated'];
                }
                $is_saved = History::create([
                    'name'      => $value['name'],
                    'currency'  => $value['symbol'],
                    'rate'      => $rate[0],
                    'time'      => $time[0],
                    'provider'  => $provider,
                    'data'      => $response['data'],
                ]);
            }
        }
    }
}