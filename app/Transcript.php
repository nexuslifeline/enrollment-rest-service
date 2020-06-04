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

    public function subjects()
    {
        return $this->belongsToMany('App\Subject', 'transcript_subjects', 'transcript_id', 'subject_id')->withTimestamps();
    }

    public function application()
    {
        return $this->belongsTo('App\StudentApplication', 'student_application_id');
    }

    public function admission()
    {
        return $this->belongsTo('App\StudentAdmission', 'student_admission_id');
    }
}
