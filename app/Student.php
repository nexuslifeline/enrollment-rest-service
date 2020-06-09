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
    protected $appends = ['active_admission', 'active_application', 'transcript'];
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
        $submittedStatus = 4;
        return $this->admission()
            ->with(['files'])
            ->where('application_status_id', $pendingStatus)
            ->orWhere('application_status_id', $submittedStatus)
            ->where('student_id', $this->id)
            ->latest()->first();
    }

    public function getActiveApplicationAttribute($value)
    {
        $pendingStatus = 2;
        $submittedStatus = 4;
        return $this->applications()
            ->where('application_status_id', $pendingStatus)
            ->orWhere('application_status_id', $submittedStatus)
            ->where('student_id', $this->id)
            ->latest()->first();
    }

    public function getTranscriptAttribute($value)
    {
        $transcript = $this->transcripts()
            ->with(['schoolYear', 'level', 'course', 'semester', 'schoolCategory', 'studentCategory', 'studentType', 'subjects']);

        $application = $this->getActiveApplicationAttribute(2);
        $admission = $this->getActiveAdmissionAttribute(2);
        $transcript->when($application, function($query) use ($application){
          return $query->where('application_id', $application['id']);
        });
        $transcript->when($admission, function($query) use ($admission){
          return $query->where('admission_id', $admission['id']);
        });

        return $transcript->first();
    }

}
