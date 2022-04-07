<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $permission
 * @property string $key
 * @property string $type
 * @property string $active
 * @property string $auth_type
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

    public function stats(){
        return $this->hasOne('App\Models\User\UserStats');
    }

    public function wallet(){
        return $this->hasOne('App\Models\User\UserWallet');
    }

}
