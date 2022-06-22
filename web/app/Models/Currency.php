<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    use SoftDeletes;

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
        'type_id',
        'sort',
        'status'
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'type_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get the post that owns the comment.
     */
    public function type()
    {
        return $this->belongsTo(CurrencyType::class);
    }
}
