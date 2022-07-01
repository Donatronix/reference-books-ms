<?php

namespace App\Api\V1\Controllers\Public;

use App\Api\V1\Controllers\Controller;
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
    public function index(Request $request): JsonResponse
    {
        $coinMarketCap = CurrencyExchange::getInstance('CoinMarketCap');

        $response = $coinMarketCap->getExchangeRate($request->currency);

        return response()->json([
            'data' => $response,
        ], 200);
    }
}