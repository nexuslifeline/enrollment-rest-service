<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolCategory extends Model
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

    public function courses()
    {
        return $this->belongsToMany('App\Course', 'level_courses', 'school_category_id','course_id');
    }

    public function levels()
    {
        return $this->hasMany('App\Level');
    }
}
