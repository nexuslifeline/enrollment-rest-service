<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentGrade extends Model
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

    public function student() {
        return $this->belongsTo('App\Student');
    }

    public function details() {
        return $this->belongsToMany('App\Term', 'student_grade_details', 'student_grade_id', 'term_id')->withPivot('grade');
    }
}
