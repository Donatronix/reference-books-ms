<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sumra\SDK\Traits\HasCurrency;

/**
 * @property int $rate
 * @property Currency $currency
 */
class ExchangeRate extends Model
{
    use HasCurrency;

    /**
     *
     * @param array $attributes
     * @return void
     */
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
    }

    public function getRate()
    {
        return $this->rate;
    }
}
