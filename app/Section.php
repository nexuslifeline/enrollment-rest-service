<?php

namespace App;

use App\Traits\OrderingTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    //
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

    public function schoolYear()
    {
        return $this->belongsTo('App\SchoolYear');
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

    public function semester()
    {
        return $this->belongsTo('App\Semester');
    }

    public function schedules()
    {
        // return $this->belongsToMany(
        //     'App\Subject',
        //     'section_schedules',
        //     'section_id',
        //     'subject_id'
        // )->withPivot([
        //     'day_id',
        //     'personnel_id',
        //     'start_time',
        //     'end_time',
        //     'is_lab',
        //     'remarks'
        // ])->withTimestamps();
        return $this->hasMany('App\SectionSchedule');
    }

    public function getSubjectsAttribute()
    {
        return $this->schedules()->distinct()->with('subject')->get(['subject_id'])->pluck('subject');
    }

}
