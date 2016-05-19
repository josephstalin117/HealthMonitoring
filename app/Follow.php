<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model {
    protected $table = 'follows';

    protected $fillable = ['user_id', 'follow_user_id', 'auth'];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function follow_user(){
        return $this->belongsTo('App\User','follow_user_id');
    }
}
