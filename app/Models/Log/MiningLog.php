<?php

namespace App\Models\Log;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiningLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'location',
        'item_id',
        'item_asset_id',
        'end_time',
    ];
}
