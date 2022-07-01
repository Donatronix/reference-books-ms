<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoinMarketCapExchangeHistory extends Model
{
    use HasFactory;
    use SoftDeletes;

    public static string $BASE_URL = 'https://pro-api.coinmarketcap.com';

    protected $fillable = [
        'currency',
        'rate',
        'time',
        'coin_market_cap_id',
        'provider',
        'data',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}