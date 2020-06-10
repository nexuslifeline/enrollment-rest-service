<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transcript extends Model
{
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

    public function student()
    {
        return $this->belongsTo('App\Student');
    }

    public function subjects()
    {
        return $this->belongsToMany('App\Subject', 'transcript_subjects', 'transcript_id', 'subject_id')->withTimestamps();
    }

    public function application()
    {
        return $this->belongsTo('App\Application');
    }

    public function admission()
    {
        return $this->belongsTo('App\Admission');
    }

    public function schoolYear()
    {
        return $this->belongsTo('App\SchoolYear');
    }

    public function level()
    {
        return $this->belongsTo('App\Level');
    }

    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    public function semester()
    {
        return $this->belongsTo('App\Semester');
    }

    public function schoolCategory()
    {
        return $this->belongsTo('App\SchoolCategory');
    }

    public function studentCategory()
    {
        return $this->belongsTo('App\StudentCategory');
    }

    public function studentType()
    {
        return $this->belongsTo('App\StudentType');
    }
}
