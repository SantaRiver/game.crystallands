<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemAssets extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'asset_id',
        'current_strength',
    ];
}
