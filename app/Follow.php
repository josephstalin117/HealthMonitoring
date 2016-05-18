<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model {
    protected $table = 'follows';

    protected $fillable = ['user_id', 'follow_id', 'auth'];

    public function user() {
        return $this->hasOne('App\User');
    }

    public function follow_user(){
        return $this->hasOne('App\User','follow_id');
    }
}
