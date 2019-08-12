<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

/**
 * @method static create(array $array)
 * @property mixed username
 * @property string name
 * @property string password
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $connection = "manage";

    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'name', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    public function services()
    {
        if($this->id == 1 and $this->name == 'admin'){
            return $this->hasMany(Service::class)->orWhere('id','>','0');
        }
        return $this->hasMany(Service::class);
    }
}
