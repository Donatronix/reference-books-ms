<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model CurrencyType
 *
 * @package App\Models
 */
class CurrencyType extends Model
{
    use HasFactory;

    /**
     * Currency types
     */
    const FIAT = 1;
    const CRYPTO = 2;
    const VIRTUAL = 3;

    /**
     * Currency types array
     *
     * @var int[]
     */
    public static $types = [
        self::FIAT,
        self::CRYPTO,
        self::VIRTUAL
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'code'
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * Get the currencies relation
     *
     * @return HasMany
     */
    public function currencies(): HasMany
    {
        return $this->hasMany(Currency::class);
    }
}
