<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $user_id
 * @property int $asset_id
 * @mixin Builder
 */
class UserAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'asset_id',
    ];

    public $timestamps = false;
}
