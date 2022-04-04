<?php

namespace App\Models\Log;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWalletLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'quantity',
    ];
}
