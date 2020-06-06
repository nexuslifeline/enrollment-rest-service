<?php

namespace App;

use App\User;
use App\StudentFamily;
use App\StudentAddress;
use App\StudentPreviousEducation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $appends = ['active_admission', 'active_application'];
    protected $hidden = [
        'created_at',
        'deleted_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function user()
    {
        return $this->morphOne('App\User', 'userable');
    }

    public function address()
    {
        return $this->hasOne('App\StudentAddress');
    }

    public function family()
    {
        return $this->hasOne('App\StudentFamily');
    }

    public function education()
    {
        return $this->hasOne('App\StudentPreviousEducation');
    }

    public function applications()
    {
        return $this->hasMany('App\Application');
    }

    public function admission()
    {
        return $this->hasOne('App\Admission');
    }

    public function transcripts()
    {
        return $this->hasMany('App\Transcript');
    }

    public function getActiveAdmissionAttribute($value)
    {
        $pendingStatus = 2;
        return $this->admission()
            ->with('transcript')
            ->where('application_status_id', $pendingStatus)
            ->first();
    }

    public function getActiveApplicationAttribute($value)
    {
        $pendingStatus = 2;
        return $this->applications()
            ->with('transcript')
            ->where('application_status_id', $pendingStatus)
            ->first();
    }
}
