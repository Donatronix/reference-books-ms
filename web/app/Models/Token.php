<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Sumra\SDK\Traits\UuidTrait;

class Token extends Model
{
    use HasFactory, UuidTrait;

    protected $fillable = [
        'name', 'address', 'icon', 'symbol'
    ];
}
