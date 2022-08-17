<?php

namespace App\Api\V1\Controllers\Public;

use App\Api\V1\Controllers\Controller;
use App\Models\Currency;
use App\Models\CurrencyType;
use App\Models\ExchangeRate;
use App\Services\CurrencyExchange\CoinMarketCapExchange;
use Illuminate\Support\Carbon;

class ExchangeRateController extends Controller
{
    public function index()
    {
        $response = null;
        $log = [];

        try {
            // Get all currencies codes
            $getStringCurrencies = Currency::where('type_id', CurrencyType::CRYPTO)->where('status', true)->get(['code']);

            // Clear old rates
            ExchangeRate::truncate();

            // Send request
            foreach ($getStringCurrencies as $currency) {
                $response = CoinMarketCapExchange::getExchangeRate($currency->code);

                // Get Provider name
                $provider = CoinMarketCapExchange::providerName();

                // log request
                $log[] = $this->logHistory($response, $provider);
            }

            return response()->jsonApi([
                'title' => 'Get Coin Market Cap',
                'message' => "Successfully get Coin Market Cap exchange rate",
                'data' => $log
            ], 200);
        } catch (\Exception $e) {
            return response()->jsonApi([
                'title' => 'Get Coin Market Cap',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    // log history
    public function logHistory($response = [], $provider = null)
    {
        $rate = [];
        $time = [];
        $is_saved = [];

        if ($response) {
            try {
                foreach ($response['data'] as $value) {
                    foreach ($value['quote'] as $val) {
                        $rate[] = (isset($val['price']) ? $val['price'] : '0');
                        $time[] = (isset($val['last_updated']) ? $val['last_updated'] : '');
                    }

                    $is_saved = ExchangeRate::updateOrCreate(['currency' => $value['symbol']], [
                        'currency_name' => $value['name'],
                        'currency' => $value['symbol'],
                        'rate' => $rate[0],
                        'time' => $time[0],
                        'last_updated' => Carbon::parse($time[0]),
                        'provider' => $provider,
                        'data' => json_encode($response),
                        'symbol' => $value['symbol']
                    ]);
                }
            } catch (\Exception $e) {
                return response()->jsonApi([
                    'title' => 'Error occurred when Logging Coin Market Cap exchange rate',
                    'message' => $e->getMessage()
                ], 400);
            }
        }

        return $is_saved;
    }
}
