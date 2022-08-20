<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExchangeRate extends Model
{
    use HasFactory;
    use SoftDeletes;

    public static array $CURRENCIES = [
        'USD',
        'EUR',
        'SOL',
        'BTC',
        'ETH',
    ];

    protected $fillable = [
        'currency',
        'currency_name',
        'rate',
        'time',
        'coin_market_cap_id',
        'provider',
        'data',
        'symbol',
        'coin_market_cap_id',
        'price',
        'last_updated',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function getResponseAttribute($res){
        return json_decode($res);
    }
}
