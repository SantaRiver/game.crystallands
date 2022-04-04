<?php

namespace App\Models\Log;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiningRewardLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'mining_log_id',
        'status',
        'reward',
        'resource',
    ];
}
