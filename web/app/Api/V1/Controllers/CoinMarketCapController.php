<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Services\CurrencyExchange\CurrencyExchange;
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
    public static function index(Request $request): JsonResponse
    {
        $coinMarketCap = CurrencyExchange::getInstance('CoinMarketCap');

        $response = $coinMarketCap->getExchangeRate($request->currency);

        return response()->json([
            'data' => $response,
        ], 200);
    }


}
