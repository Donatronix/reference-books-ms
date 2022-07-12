<?php

namespace App\Services\CurrencyExchange;

use App\Contracts\CurrencyExchangeContract;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use PhpParser\Node\Expr\Cast\String_;

class CoinMarketCapExchange implements CurrencyExchangeContract
{

    public static function providerName(): String
    {
        return "coinmarketcap";
    }

    /**
     * @param string $currency
     *
     * @return Response
     */

    public static function getExchangeRate($currency = null): Response
    {

        $baseUrl    = \App\Models\ExchangeRate::$BASE_URL;
        $endPoint = '/v2/tools/price-conversion';
        $response = Http::acceptJson()
            ->withoutVerifying()
            ->withHeaders(self::getHttpHeaders())
            ->withOptions(["verify" => false])
            ->get($baseUrl . $endPoint, [
                'amount'        => '1',
                'symbol'        => $currency,
                'convert'       => 'USD'
            ]);
        return $response;
    }

    /**
     * @return array
     */
    public static function getHttpHeaders(): array
    {
        $bearerToken = env('COIN_MARKET_CAP_API_KEY', 'ca2eb74a-459b-40cf-b36e-0e911ba3718c');
        $headers = [
            "Authorization"     => "Bearer {$bearerToken}",
            "Cache-Control"     => "no-cache",
            "Accepts"           => "application/json",
            "X-CMC_PRO_API_KEY" => $bearerToken,
        ];
        return $headers;
    }
}
