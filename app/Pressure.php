<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Pressure extends Model {
    protected $table = 'pressures';

    protected $fillable = ['high', 'low','user_id'];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
