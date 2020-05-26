<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolCategory extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function courses()
    {
        return $this->belongsToMany('App\Course', 'level_courses', 'school_category_id','course_id');
    }

    public function levels()
    {
        return $this->belongsToMany('App\Level', 'level_courses', 'school_category_id','level_id');
    }
}
