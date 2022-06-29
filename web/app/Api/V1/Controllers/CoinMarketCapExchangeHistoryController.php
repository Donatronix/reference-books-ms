<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use App\Models\CoinMarketCapExchangeHistory as History;
use App\Models\Currency;



class CoinMarketCapExchangeHistoryController extends Controller
{

    public function sendRequestToCoinMarketCap($crytoSymbols = 'btc')
    {
        $is_saved   = null;
        $response   = null;
        $baseUrl    = "https://pro-api.coinmarketcap.com/";
        $endpoint   = "v1/cryptocurrency/price-performance-stats/latest";

        try {
            $response = Http::withoutVerifying()
                ->withHeaders(
                    [
                        "Authorization" => "ca2eb74a-459b-40cf-b36e-0e911ba3718c",
                        "Cache-Control" => "no-cache",
                        "Accepts" => "application/json",
                        "X-CMC_PRO_API_KEY" => "ca2eb74a-459b-40cf-b36e-0e911ba3718c",
                    ]
                )
                ->withOptions(["verify" => false])
                ->get($baseUrl . $endpoint, [
                    'start'         => '1',
                    'limit'         => '100',
                    'time_period'   => '24h',
                    'symbol'        => $crytoSymbols,
                ]);
            //log response
            $this->logHistory($response);
        } catch (\Throwable $e) {
            $response = $e->getMessage();
        }
        return $response;
    }


    //log history
    public function logHistory($data = [])
    {
        if ($data) {
            foreach ($data as $value) {
                $is_saved = History::create([
                    'name'      => $value->name,
                    'currency'  => $value->symbol,
                    'rate'      => $value->periods->quote->price_change,
                    'time'      => $value->last_updated,
                    'provider'  => 'coinmarketcap.com',
                    'data'      => $value,
                ]);
            }
        }
    }


    //Get Currencies
    public function currencySymbols()
    {
        $response       = null;
        $getAllSymbols  = null;
        $currencyArray  = [];
        try {
            $allCurrencies = Currency::where('type_id', 2)->get(['code as currency_code']);
            if ($allCurrencies) {
                foreach ($allCurrencies as $value) {
                    $currencyArray[] = $value->currency_code; //get all currencies symbols
                }
                $getAllSymbols = implode(', ', $currencyArray); //add comman
                $response = $this->sendRequestToCoinMarketCap($getAllSymbols); //send an API request
            }
        } catch (\Throwable $e) {
            $response = $e->getMessage();
        }

        return $response;
    }
}