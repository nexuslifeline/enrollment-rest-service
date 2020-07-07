<?php

namespace App;

use App\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    //
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at',
        'deleted_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function levels()
    {
        return $this->belongsToMany(
          'App\Level', 
          'level_subjects', 
          'subject_id',
          'level_id'
        );
    }

    public function courses()
    {
        return $this->belongsToMany('App\Course', 'level_subjects', 'subject_id','course_id');
    }

    public function semesters()
    {
        return $this->belongsToMany('App\Semester', 'level_subjects', 'subject_id','semester_id');
    }

    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    public function schoolCategory()
    {
        return $this->belongsTo('App\SchoolCategory');
    }

    public function prerequisites()
    {
        return $this->belongsToMany(
            'App\Subject',
            'curriculum_prerequisites',
            'subject_id',
            'prerequisite_subject_id'
        )->withTimestamps();
    }
}
