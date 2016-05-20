<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model {
    protected $table = 'messages';

    protected $fillable = ['user_id','to_user_id', 'content'];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function to_user() {
        return $this->belongsTo('App\User', 'to_user_id');
    }
}
