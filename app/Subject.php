<?php

namespace App;

use App\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    //
    use SoftDeletes;
    protected $guarded = [];

    public function levels()
    {
        return $this->belongsToMany('App\Level', 'level_subjects', 'subject_id','level_id');
    }

    public function courses()
    {
        return $this->belongsToMany('App\Course', 'level_subjects', 'subject_id','course_id');
    }

    public function semesters()
    {
        return $this->belongsToMany('App\Semester', 'level_subjects', 'subject_id','semester_id');
    }
}
