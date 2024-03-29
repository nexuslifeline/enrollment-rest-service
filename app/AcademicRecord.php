<?php

namespace App;

use App\Scopes\SchoolCategoryScope;
use App\Traits\OrderingTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class AcademicRecord extends Model
{
    use SoftDeletes, OrderingTrait;
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
        return $this->belongsToMany('App\Subject', 'academic_record_subjects', 'academic_record_id', 'subject_id')->withPivot('section_id','is_dropped')->withTimestamps();
    }

    public function application()
    {
        return $this->hasOne('App\Application');
    }

    // public function admission()
    // {
    //     return $this->hasOne('App\Admission');
    // }

    public function evaluation()
    {
        return $this->hasOne('App\Evaluation');
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
        return $this->belongsToMany('App\StudentGrade', 'student_grade_periods', 'academic_record_id', 'student_grade_id')
        ->withPivot('grade','grading_period_id');
    }

    public function curriculum()
    {
        return $this->belongsTo('App\Curriculum');
    }

    public function studentCurriculum()
    {
        return $this->belongsTo('App\Curriculum', 'student_curriculum_id');
    }

    public function transcriptRecord()
    {
        return $this->belongsTo('App\TranscriptRecord');
    }

    public function billings()
    {
        return $this->hasMany('App\Billing');
    }

    public function getHasInitialBillingAttribute()
    {
        $initialBillingType = Config::get('constants.billing_type.INITIAL_FEE');
        // Log::info($this->billings->where('billing_type_id', $initialBillingType) ? true : false);
        return $this->billings->where('billing_type_id', $initialBillingType)->count() > 0;
    }
}
