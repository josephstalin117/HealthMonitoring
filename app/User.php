<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon;

class User extends Authenticatable {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function age() {
        $dateNow = Carbon::now();
        return ($dateNow->diffInYears(Carbon::parse($this->attributes['birth'])));
    }

    public function profile() {
        return $this->hasOne('App\Profile');
    }

    public function pressure() {
        return $this->hasMany('App\Pressure');
    }

    public function sugar() {
        return $this->hasMany('App\Sugar');
    }

    public function follow() {
        return $this->hasMany('App\Follow');
    }

    public function followed() {
        return $this->hasMany('App\Follow', 'id', 'follow_user_id');
    }

    public function message() {
        return $this->hasMany('App\Follow');
    }

    public function receive_message() {
        return $this->hasMany('App\Follow', 'id', 'to_user_id');
    }
}
