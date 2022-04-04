<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_asset_id',
    ];
}
