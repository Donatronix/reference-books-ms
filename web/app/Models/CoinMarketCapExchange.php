<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoinMarketCapExchange extends Model
{
    public static string $BASE_URL = 'https://pro-api.coinmarketcap.com';

    public static array $CURRENCIES = [
        'USD',
        'EUR',
        'SOL',
        'BTC',
        'ETH',
    ];

    protected $table = 'coin_market_cap_exchanges';
}
