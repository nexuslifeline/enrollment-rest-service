<?php

namespace App;

use App\Subject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends Model
{
    //
    use SoftDeletes;
    protected $guarded = [];

    /* Get the school category that owns the level */
    public function student()
    {
        return $this->belongsTo('App\Student');
    }

    public function subjects()
    {
        return $this->belongsToMany('App\Subject', 'level_subjects', 'level_id','subject_id');
    }

    public function courses()
    {
        return $this->belongsToMany('App\Course', 'level_courses', 'level_id','course_id');
    }
}
