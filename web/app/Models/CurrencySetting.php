<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Sumra\SDK\Traits\UuidTrait;

class CurrencySetting extends Model
{
    use HasFactory, UuidTrait;

    protected $fillable = [
        'currency_id', 'address'];

    /**
     * Get the currency associated with the CurrencySetting
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function currency()
    {
        return $this->hasOne(Currency::class);
    }
}
