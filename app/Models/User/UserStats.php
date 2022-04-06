<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStats extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'health',
        'hunger',
        'energy',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
