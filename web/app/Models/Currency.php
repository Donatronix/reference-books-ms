<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Currency
 *
 * @package App\Models
 *
 * @property integer $id;
 * @property string $name;
 * @property string $code;
 * @property MetaLocation $country
 * @property string $unicode_decimal
 * @property double $rate
 */
class Currency extends Model
{
    use HasFactory;

    /**
     * Currency status
     */
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * Currency types
     */
    const TYPE_FIAT = 1;
    const TYPE_CRYPTO = 2;
    const TYPE_VIRTUAL = 3;

    /**
     * Currency statuses array
     *
     * @var int[]
     */
    public static array $statuses = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE
    ];

    /**
     * Currency types array
     *
     * @var int[]
     */
    public static $types = [
        self::TYPE_FIAT,
        self::TYPE_CRYPTO,
        self::TYPE_VIRTUAL
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'code',
        'symbol',
        'rate',
        'type',
        'status',
        'unicode_decimal'
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get codes list
     *
     * @param $query
     * @return mixed
     */
    public function scopeCodes($query)
    {
        return $query->select('code', 'id')->get()->map(function ($object) {
            return mb_strtolower($object->code);
        })->flip();
    }
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
