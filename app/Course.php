<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
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

    public function levels()
    {
        return $this->belongsToMany('App\Level', 'level_courses', 'course_id','level_id');
    }

    public function schoolCategories()
    {
        return $this->belongsToMany('App\SchoolCategory', 'level_courses', 'course_id','school_category_id');
    }

    public function scopeWhereLike($query, $value)
    {
        return $query->where('courses.name', 'like', '%' . $value . '%')
            ->orWhere('courses.description', 'like', '%' . $value . '%')
            ->orWhere('courses.major', 'like', '%' . $value . '%');
    }
}
