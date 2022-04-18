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
class UserInventory extends Model
{
    use HasFactory;

    protected $table = 'user_inventory';

    protected $fillable = [
        'user_id',
        'item_id',
        'asset_id',
    ];

    public $timestamps = false;
}
