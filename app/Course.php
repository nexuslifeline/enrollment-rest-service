<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    //
    use SoftDeletes;
    protected $guarded = ['id'];

    public function levels()
    {
        return $this->belongsToMany('App\Level', 'level_courses', 'course_id','level_id');
    }

    public function school_categories()
    {
        return $this->belongsToMany('App\SchoolCategory', 'level_courses', 'course_id','school_category_id');
    }
}
