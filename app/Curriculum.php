<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Curriculum extends Model
{
    use SoftDeletes;
    protected $table = 'curriculums';
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at',
        'deleted_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function schoolCategory() 
    {
        return $this->belongsTo('App\SchoolCategory');
    }

    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    public function level()
    {
        return $this->belongsTo('App\Level');
    }

    public function subjects()
    {
        return $this->belongsToMany(
            'App\Subject', 
            'level_subjects', 
            'curriculum_id',
            'subject_id'
        )->withPivot(['level_id','semester_id'])->withTimestamps();
    }

    public function prerequisites()
    {
        return $this->belongsToMany(
            'App\Subject',
            'curriculum_prerequisites',
            'curriculum_id',
            'prerequisite_subject_id'
        )->withPivot(['subject_id'])->withTimestamps();
    }
}
