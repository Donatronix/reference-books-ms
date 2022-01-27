<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Currency
 *
 * @package App\Models
 *
 * @property integer $id
 * @property string $title
 * @property string $code
 * @property string $symbol
 * @property string $icon
 * @property double $rate
 * @property integer $type
 * @property boolean $status
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
     * Currency statuses array
     *
     * @var int[]
     */
    public static array $statuses = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE
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

    /**
     * Get the post that owns the comment.
     */
    public function type()
    {
        return $this->belongsTo(CurrencyType::class);
    }
}
