<?php

namespace App\Services\CurrencyExchange;

use App\Contracts\CurrencyExchangeContract;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class CoinMarketCapExchange implements CurrencyExchangeContract
{
    /**
     * @param string $currency
     *
     * @return Response
     */
    public static function getExchangeRate(string $currency): Response
    {
        $endPoint = '/v2/tools/price-conversion';
        $baseApiUrl = \App\Models\CoinMarketCapExchange::$BASE_URL;
        $url = $baseApiUrl . $endPoint;

        $parameters = [
            'start' => '1',
            'limit' => '5000',
            'amount' => '1',
            'id' => '1',
            'symbol' => $currency,
        ];


        return Http::acceptJson()->withHeaders(self::getHttpHeaders())->get($url, $parameters);
    }

    /**
     * @return array
     */
    public static function getHttpHeaders(): array
    {
        $bearerToken = env('COIN_MARKET_CAP_API_KEY');
        return [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$bearerToken}",
                'X-CMC_PRO_API_KEY: ' . $bearerToken,
            ],
        ];
    }
}
