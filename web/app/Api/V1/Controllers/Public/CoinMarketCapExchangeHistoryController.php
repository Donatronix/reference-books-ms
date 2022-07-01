<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\Models\CoinMarketCapExchangeHistory as History;
use Illuminate\Http\JsonResponse;
use App\Services\CurrencyExchange\CoinMarketCapExchange;



class CoinMarketCapExchangeHistoryController extends Controller
{

    public function logExchangeRate($exchangeRateResponse = null, $provider = null)
    {
        //Log exchange rate response
        return $this->logHistory($exchangeRateResponse, $provider);
    }


    //log history
    public function logHistory($data = [], $provider = null)
    {
        if ($data) {
            foreach ($data as $value) {
                $is_saved = History::create([
                    'name'      => $value->name,
                    'currency'  => $value->symbol,
                    'rate'      => $value->price_change,
                    'time'      => $value->last_updated,
                    'provider'  => $provider,
                    'data'      => $value,
                ]);
            }
        }
    }
}