<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_id',
        'active',
        'location',
        'mining_time',
        'max_strength',
        'efficiency',
        'wear',
    ];
}
