<?php

namespace App\Model\Locations;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * @author Mauricio
 * @property integer $id;
 * @property string $name;
 * @property string $code;
 * @property MetaLocation $country
 * @property string $unicode_decimal
 * @property double $rate
 */
class Currency extends Model
{
    protected $fillable = [
        'code',
        'name',
        'unicode_decimal'
    ];

    /**
     *
     * @param array $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function setCountry($country)
    {
        $this->country()->associate($country);
    }

    public function country()
    {
        return $this->belongsTo(MetaLocation::class, 'country_iso', 'iso');
    }

    public function getSymbol()
    {
        if ($this->unicode_decimal == '$') {
            return str_replace('D', '$', $this->code);
        }

        return $this->unicode_decimal;
    }

    /**
     *
     * @param Currency $currency
     */
    public function getRate($currency)
    {
        $rate = 1 / $currency->rate;
        $rate = $rate * $this->rate;

        return $rate;
    }

    /**
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
}
