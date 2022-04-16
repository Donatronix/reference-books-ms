<?php

namespace App\Contracts;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Client\Response;

interface CurrencyExchangeContract
{
    /**
     * @return string[]
     */
    public static function getHttpHeaders(): array;

    /**
     * @param string $currency
     *
     * @return Response
     * @throws GuzzleException
     */
    public static function getExchangeRate(string $currency): Response;
}
