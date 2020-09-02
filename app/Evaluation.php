<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evaluation extends Model
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

    public function files()
    {
        return $this->hasMany('App\EvaluationFile');
    }

    public function student()
    {
        return $this->belongsTo('App\Student');
    }

    public function studentCategory()
    {
        return $this->belongsTo('App\StudentCategory');
    }

    public function lastSchoollevel()
    {
        return $this->belongsTo('App\Level', 'last_school_level_id');
    }

    public function level()
    {
        return $this->belongsTo('App\Level');
    }

    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    public function curriculum()
    {
        return $this->belongsTo('App\Curriculum');
    }

    public function studentCurriculum()
    {
        return $this->belongsTo('App\Curriculum', 'student_curriculum_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(
            'App\Subject',
            'evaluation_subjects',
            'evaluation_id',
            'subject_id'
        )->withPivot([
          'level_id',
          'semester_id',
          'grade',
          'notes',
          'is_taken'
        ])->withTimestamps();
    }
}
