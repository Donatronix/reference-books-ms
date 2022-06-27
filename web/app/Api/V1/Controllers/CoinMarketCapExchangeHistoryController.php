<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use App\Models\CoinMarketCapExchangeHistory as History;
use App\Models\Currency;



class CoinMarketCapExchangeHistoryController extends Controller
{
    public function logCurrencyLatestRate($currencySymbol = null)
    {
        $is_saved   = null;
        $sandboxUrl = "https://sandbox-api.coinmarketcap.com/v1/cryptocurrency/listings/latest";
        $liveUrl    = "https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest";

        $response   = Http::withHeaders([
            'Accepts'           => 'application/json',
            'X-CMC_PRO_API_KEY' => env('COIN_MARKET_CAP_API_KEY', 'ca2eb74a-459b-40cf-b36e-0e911ba3718c'),
        ])->get($liveUrl, [
            'start'         => '1',
            'limit'         => '5000',
            'convert'       => $currencySymbol, //'USD'
        ]);
        if ($response) {
            $is_saved = History::create([
                'currency'  => $currencySymbol,
                'rate'      => $response->quote->$currencySymbol->price,
                'time'      => $response->last_updated,
                'provider'  => 'coinmarketcap.com',
                'data'      => $response,
            ]);
        }
        return $is_saved;
    }

    //Get Currencies
    public function CurrencyRate()
    {
        $allCurrencies = Currency::with('type')
            ->where('type.code', 'crypto')
            ->get(['currencies.code as currency_code']);
        if ($allCurrencies) {
            foreach ($allCurrencies as $value) {
                $this->logCurrencyLatestRate($value->currency_code);
            }
        }
        return;
    }
}