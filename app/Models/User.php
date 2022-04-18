<?php

namespace App\Models;

use App\Models\User\UserInventory;
use App\Models\User\UserResources;
use App\Models\User\UserStats;
use App\Models\User\UserWallet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $permission
 * @property string $key
 * @property string $type
 * @property string $active
 * @property string $auth_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read UserStats|null $stats
 * @property-read int|null $tokens_count
 * @property-read UserWallet|null $wallet
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereActive($value)
 * @method static Builder|User whereAuthType($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereKey($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePermission($value)
 * @method static Builder|User whereType($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @mixin Builder
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'permission', 'key', 'type', 'active', 'auth_type',];

    /**
     * Return user stats.
     *
     * @return HasOne
     */
    public function stats(): HasOne
    {
        return $this->hasOne(UserStats::class);
    }

    /**
     * Return user balance.
     *
     * @return HasOne
     */
    public function wallet(): HasOne
    {
        return $this->hasOne(UserWallet::class);
    }

    /**
     * Return user resources.
     *
     * @return HasOne
     */
    public function resources(): HasOne
    {
        return $this->hasOne(UserResources::class);
    }

    /**
     * Return user inventory.
     *
     * @return HasMany
     */
    public function inventory(): HasMany
    {
        return $this->hasMany(UserInventory::class);
    }

}
