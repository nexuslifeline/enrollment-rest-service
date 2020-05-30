<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transcript extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function subjects()
    {
        return $this->belongsToMany('App\Subject', 'transcript_subjects', 'transcript_id', 'subject_id')->withTimestamps();
    }

    public function application()
    {
        return $this->belongsTo('App\StudentApplication', 'student_application_id');
    }
}
