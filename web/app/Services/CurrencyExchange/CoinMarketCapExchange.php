<?php

namespace App\Services\CurrencyExchange;

use App\Contracts\CurrencyExchangeContract;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class CoinMarketCapExchange implements CurrencyExchangeContract
{
    /**
     * @param string $currency
     * @param string $convertTo //comma-separated fiat or cryptocurrency symbols to convert the source amount to.
     *
     * @return array
     * @throws GuzzleException
     */
    public static function getExchangeRate(string $currency, string $convertTo): array
    {
        $endPoint = '/v2/tools/price-conversion';
        $baseApiUrl = \App\Models\CoinMarketCapExchange::$BASE_URL;
        $url = $baseApiUrl . $endPoint;

        $parameters = [
            'start' => '1',
            'limit' => '5000',
            'symbol' => $currency,
            'convert' => $convertTo,
        ];

        $qs = http_build_query($parameters); // query string encode the parameters
        $request = "{$url}?{$qs}"; // create the request URL


        $client = new Client(self::getHttpHeaders());
        $response = $client->get($request, ['verify' => false]);

        $resp['statusCode'] = $response->getStatusCode();
        $resp['bodyContents'] = $response->getBody()->getContents();
        return $resp;
    }

    /**
     * @return array
     */
    public static function getHttpHeaders(): array
    {
        $bearerToken = env('COINMARKETCAP_API_KEY');
        return [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$bearerToken}",
            ],
        ];
    }
}
