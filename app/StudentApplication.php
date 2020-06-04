<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentApplication extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    /* Get the student that owns the student previous education */
    public function student()
    {
        return $this->belongsTo('App\Student');
    }

    public function transcript()
    {
        return $this->hasOne('App\Transcript');
    }

    public function applicationStep()
    {
        return $this->hasOne('App\ApplicationStep');
    }

    public function schoolYear()
    {
        return $this->hasOne('App\SchoolYear');
    }
}
