<?php

namespace App\Api\V1\Controllers;

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
        $baseUrl    = "https://pro-api.coinmarketcap.com/";
        $endpoint   = "cryptocurrency/listings/latest";
        $response = Http:: //withoutVerifying()
            withHeaders(
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
                'limit'         => '20',
                'convert'       => $currencySymbol
            ]);

        if ($response) {
            $is_saved = History::create([
                'currency'  => $currencySymbol,
                'rate'      => $response->data->quote->$currencySymbol->price,
                'time'      => $response->last_updated,
                'provider'  => 'coinmarketcap.com',
                'data'      => $response['data'],
            ]);
        }
        return $is_saved;
    }

    //Get Currencies
    public function currencyRate()
    {
        $allCurrencies = Currency::where('type_id', 2)
            //->where('type.code', 'crypto')
            ->get(['code as currency_code']);
        if ($allCurrencies) {
            foreach ($allCurrencies as $value) {
                $this->logCurrencyLatestRate($value->currency_code);
            }
        }
        return $allCurrencies;
    }
}