<?php

namespace App;

use App\Scopes\SchoolCategoryScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TranscriptRecord extends Model
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

    public function schoolCategory()
    {
        return $this->belongsTo('App\SchoolCategory');
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

    public function evaluations()
    {
        return $this->hasMany('App\Evaluation');
    }

    public function subjects()
    {
        return $this->belongsToMany(
            'App\Subject',
            'transcript_record_subjects',
            'transcript_record_id',
            'subject_id'
        )->withPivot([
            'level_id',
            'semester_id',
            'grade',
            'notes',
            'is_taken'
        ])->withTimestamps();
    }

    public function levels()
    {
        return $this->belongsToMany(
            'App\Level',
            'transcript_record_subjects',
            'transcript_record_id',
            'level_id'
        );
    }
}
