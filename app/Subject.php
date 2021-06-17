<?php

namespace App;

use App\Section;
use App\Scopes\SchoolCategoryScope;
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

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new SchoolCategoryScope);
    }

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
        return $this->belongsToMany('App\Course', 'level_subjects', 'subject_id', 'course_id');
    }

    public function semesters()
    {
        return $this->belongsToMany('App\Semester', 'level_subjects', 'subject_id', 'semester_id');
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

    public function schedules()
    {
        return $this->hasMany('App\SectionSchedule');
    }

    public function getSectionAttribute()
    {
        return Section::find($this->pivot->section_id);
    }

    public function getSectionScheduleAttribute()
    {
        $sectionSchedule =  Section::with(['schedules' => function ($query) {
            return $query->with('personnel', 'section')->where('section_id', $this->pivot->section_id)
                ->where('subject_id', $this->id);
        }])->find($this->pivot->section_id);

        return $sectionSchedule->schedules ?? null;
    }

    // public function grades()
    // {
    //     return $this->belongsToMany('App\Term', 'student_grades', 'subject_id', 'term_id')->withPivot('grade');
    // }

    public function getIsAllowedAttribute()
    {
        return $this->attributes['is_allowed'];
    }

    public function setIsAllowedAttribute($value)
    {
        $this->attributes['is_allowed'] = $value;
    }

    public function getSchoolYearAttribute()
    {
        return $this->attributes['school_year'];
    }

    public function setSchoolYearAttribute($value)
    {
        $this->attributes['school_year'] = $value;
    }

    public function getLevelAttribute()
    {
        return Level::find($this->pivot->level_id);
    }

    public function getSemesterAttribute()
    {
        return Semester::find($this->pivot->semester_id);
    }
}
