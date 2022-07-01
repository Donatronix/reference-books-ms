<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Api\V1\Controllers\CurrencyExchange;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use ReflectionException;

class CoinMarketCapController extends Controller
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws ReflectionException
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $coinMarketCap          = CurrencyExchange::getInstance('CoinMarketCap');
            $currencyExchangeRate   = $coinMarketCap->getExchangeRate($request->currency);
            return response()->jsonApi([
                'type'      => 'success',
                'title'     => 'Get Coin Market Cap',
                'message'   => "Get Coin Market Cap",
                'data'      => $currencyExchangeRate
            ], 200);
        } catch (\Exception $e) {
            return response()->jsonApi([
                'type'      => 'danger',
                'title'     => 'Get Coin Market Cap',
                'message'   => $e->getMessage(),
                'data'      => []
            ], 400);
        }
    }
}