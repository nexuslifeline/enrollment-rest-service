<?php

namespace App;

use App\Subject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends Model
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

    /* Get the school category that owns the level */
    public function schoolCategory()
    {
        return $this->belongsTo('App\SchoolCategory');
    }

    public function subjects()
    {
        return $this->belongsToMany('App\Subject', 'level_subjects', 'level_id','subject_id')->withTimestamps();
    }

    public function courses()
    {
        return $this->belongsToMany('App\Course', 'level_courses', 'level_id','course_id')->withTimestamps();;
    }
}
