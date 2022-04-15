<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiningLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'item_type',
        'resources',
    ];
}
