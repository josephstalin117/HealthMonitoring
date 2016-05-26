<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Line extends Model {
    //
    protected $table = 'lines';

    protected $fillable = ['line', 'type', 'name'];

}
