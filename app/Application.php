<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
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

    // /* Get the student that owns the student previous education */
    // public function student()
    // {
    //     return $this->belongsTo('App\Student');
    // }

    public function academicRecord()
    {
        return $this->belongsTo('App\AcademicRecord');
    }

    // public function applicationStep()
    // {
    //     return $this->hasOne('App\ApplicationStep');
    // }

    // public function schoolYear()
    // {
    //     return $this->hasOne('App\SchoolYear');
    // }
}
