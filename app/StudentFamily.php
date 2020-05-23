<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentFamily extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    /* Get the student that owns the student family */
    public function student()
    {
        return $this->belongsTo('App\Student');
    }
}
