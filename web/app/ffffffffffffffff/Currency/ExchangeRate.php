<?php

namespace App\Model\Currency;

use App\Model\Concerns\HasCurrency;
use Illuminate\Database\Eloquent\Model;

/**
 *
 * @author Mauricio
 * @property int $rate
 * @property Currency $currency
 *
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
