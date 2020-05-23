<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentAddress extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    /* Get the student that owns the student address */
    public function student()
    {
        return $this->belongsTo('App\Student');
    }
}
