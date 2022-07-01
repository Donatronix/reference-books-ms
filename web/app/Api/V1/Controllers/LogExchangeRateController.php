<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Api\V1\Controllers\CoinMarketCapExchangeHistoryController as LogHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use ReflectionException;
use App\Services\CurrencyExchange\CoinMarketCapExchange;
use App\Models\Currency;



class LogExchangeRateController extends Controller
{
    /**
     *
     * @return JsonResponse
     */
    //tregger request to coinmarketcap.com
    public function index(): JsonResponse
    {
        try {
            //Get all currencies
            $logHistory = new LogHistory;
            $getStringCurrencies = $this->currencySymbols(2);

            //Send request
            $response = CoinMarketCapExchange::getExchangeRate($getStringCurrencies);
            //Get Provider name
            $provider = CoinMarketCapExchange::providerName();

            //log request
            $log = $logHistory->logExchangeRate($response, $provider);
            //return response
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
        return $log;
    }


    //Get all Currencies
    public function currencySymbols($currency_type_id = 2): String
    {
        $getAllSymbols  = null;
        $currencyArray  = [];

        $allCurrencies = Currency::where('type_id', $currency_type_id)->get(['code as currency_code']);
        if ($allCurrencies) {
            foreach ($allCurrencies as $value) {
                $currencyArray[] = $value->currency_code; //get all currencies symbols
            }
            $getAllSymbols = implode(', ', $currencyArray); //add comman - returns string
        }
        return $getAllSymbols;
    }
}