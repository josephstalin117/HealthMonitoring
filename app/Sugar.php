<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sugar extends Model {
    //
    protected $table = 'sugars';

    protected $fillable = ['sugar', 'user_id'];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
