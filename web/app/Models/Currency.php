<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Currency
 *
 * @package App\Models
 */
class Currency extends Model
{
    use HasFactory;

    const SUMRA_DIVITS = 1;

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
        self::TYPE_CRYPTO
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'symbol',
        'type',
        'status'
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
}
