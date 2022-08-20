<?php

namespace App\Services\CurrencyExchange;

use App\Contracts\CurrencyExchangeContract;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class CoinMarketCapExchange implements CurrencyExchangeContract
{
    public static string $BASE_URL = 'https://pro-api.coinmarketcap.com';

    public static function providerName(): string
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
        $endPoint = self::$BASE_URL . '/v2/tools/price-conversion';

        return Http::acceptJson()
            ->withoutVerifying()
            ->withHeaders(self::getHttpHeaders())
            ->withOptions(["verify" => false])
            ->get($endPoint, [
                'amount' => '1',
                'symbol' => $currency,
                'convert' => 'USD'
            ]);
    }

    /**
     * @return array
     */
    public static function getHttpHeaders(): array
    {
        $bearerToken = env('COIN_MARKET_CAP_API_KEY', 'ca2eb74a-459b-40cf-b36e-0e911ba3718c');
        $headers = [
            "Authorization" => "Bearer {$bearerToken}",
            "Cache-Control" => "no-cache",
            "Accepts" => "application/json",
            "X-CMC_PRO_API_KEY" => $bearerToken,
        ];

        return $headers;
    }
}
