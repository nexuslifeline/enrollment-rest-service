<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admission extends Model
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

    /* Get the student that owns the student previous education */
    public function student()
    {
        return $this->belongsTo('App\Student');
    }

    public function transcript()
    {
        return $this->hasOne('App\Transcript');
    }

    public function admissionStep()
    {
        return $this->hasOne('App\AdmissionStep');
    }

    public function schoolYear()
    {
        return $this->hasOne('App\SchoolYear');
    }

    public function files()
    {
        return $this->hasMany('App\AdmissionFile');
    }
}
