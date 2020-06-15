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
    protected $appends = [];
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

    public function studentFees()
    {
        return $this->hasMany('App\StudentFee');
    }

    public function getActiveAdmissionAttribute()
    {
        $completedStatus = 5;
        return $this->admission()
            ->where('application_status_id', '!=', $completedStatus)
            ->where('student_id', $this->id)
            ->latest()
            ->first();
    }

    public function getActiveApplicationAttribute()
    {
        $completedStatus = 5;
        return $this->applications()
            ->where('application_status_id', '!=', $completedStatus)
            ->where('student_id', $this->id)
            ->latest()
            ->first();
    }

    public function getTranscriptAttribute()
    {
        $transcript = $this->transcripts();

        $application = $this->active_application ?? false;
        $admission = $this->active_admission ?? false;
        $transcript->when($application, function($query) use ($application){
          return $query->where('application_id', $application['id']);
        });
        $transcript->when($admission, function($query) use ($admission){
          return $query->where('admission_id', $admission['id']);
        });

        return $transcript->first();
    }
}
