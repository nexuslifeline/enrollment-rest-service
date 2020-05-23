<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends Model
{
    //
    use SoftDeletes;
    protected $guarded=[];

    /* Get the school category that owns the level */
    public function student()
    {
        return $this->belongsTo('App\Student');
    }
}
