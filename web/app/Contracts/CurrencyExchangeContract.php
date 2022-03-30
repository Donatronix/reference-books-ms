<?php

namespace App\Contracts;

use GuzzleHttp\Exception\GuzzleException;

interface CurrencyExchangeContract
{
    /**
     * @return string[]
     */
    public static function getHttpHeaders(): array;

    /**
     * @param string $currency
     * @param string $convertTo //comma-separated fiat or cryptocurrency symbols to convert the source amount to.
     *
     * @return array
     * @throws GuzzleException
     */
    public static function getExchangeRate(string $currency, string $convertTo): array;
}
