<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserResources extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wood',
        'stone',
        'iron',
        'iron_ingot',
    ];

    public $timestamps = false;
}
