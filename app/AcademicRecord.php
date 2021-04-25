<?php

namespace App;

use App\Scopes\SchoolCategoryScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicRecord extends Model
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

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new SchoolCategoryScope);
    }

    public function student()
    {
        return $this->belongsTo('App\Student');
    }

    public function subjects()
    {
        return $this->belongsToMany('App\Subject', 'academic_record_subjects', 'academic_record_id', 'subject_id')->withPivot('section_id')->withTimestamps();
    }

    public function application()
    {
        return $this->belongsTo('App\Application');
    }

    public function admission()
    {
        return $this->belongsTo('App\Admission');
    }

    public function studentFee()
    {
        return $this->hasOne('App\StudentFee');
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

    public function section()
    {
        return $this->belongsTo('App\Section');
    }

    public function getNameAttribute()
    {
        return "{$this->student->first_name} {$this->student->middle_name} {$this->student->last_name}";
    }

    public function grades()
    {
        return $this->belongsToMany('App\GradingPeriod', 'student_grades', 'academic_record_id', 'grading_period_id')->withPivot('subject_id','grade');
    }
}
