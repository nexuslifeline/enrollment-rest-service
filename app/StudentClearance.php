<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentClearance extends Model
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

    public function signatories()
    {
        return $this->belongsToMany('App\Personnel', 'student_clearance_signatories', 'student_clearance_id', 'personnel_id')
            ->withPivot('is_cleared','date_cleared','remarks');
    }

    public function student()
    {
        return $this->belongsTo('App\Student');
    }

    public function academicRecord()
    {
        return $this->belongsTo('App\AcademicRecord');
    }
}
