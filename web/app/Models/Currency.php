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
 * @property string $symbol
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
        'icon',
        'rate',
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
}
