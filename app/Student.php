<?php

namespace App;

use App\User;
use App\StudentFamily;
use App\StudentAddress;
use App\StudentPreviousEducation;
use App\StudentPhoto;
use App\Evaluation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Student extends Model
{
    use SoftDeletes;
    protected $guarded = ['id', 'name']; //added name on guarded to prevent updating, coz we already have name attrib
    protected $appends = ['name', 'age', 'current_address', 'permanent_address', 'latest_academic_record', 'latest_manual_academic_record'];
    protected $hidden = [
        'created_at',
        'deleted_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function files()
    {
        return $this->hasMany('App\StudentFile');
    }

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

    public function photo()
    {
        return $this->hasOne('App\StudentPhoto');
    }

    public function applications()
    {
        return $this->hasMany('App\Application');
    }

    public function admission()
    {
        return $this->hasOne('App\Admission');
    }

    public function academicRecords()
    {
        return $this->hasMany('App\AcademicRecord');
    }

    public function transcriptRecords() {
        return $this->hasMany('App\TranscriptRecord');
    }

    public function evaluation()
    {
        return $this->hasOne('App\Evaluation');
    }

    public function evaluations()
    {
        return $this->hasMany('App\Evaluation');
    }

    public function studentFees()
    {
        return $this->hasMany('App\StudentFee');
    }

    public function getActiveAdmissionAttribute()
    {
        $completedStatus = 7;
        return $this->admission()
            ->where('application_status_id', '!=', $completedStatus)
            ->where('student_id', $this->id)
            ->latest()
            ->first();
    }

    public function getActiveApplicationAttribute()
    {
        $completedStatus = 7;
        return $this->applications()
            ->where('application_status_id', '!=', $completedStatus)
            ->where('student_id', $this->id)
            ->latest()
            ->first();
    }

    public function getAcademicRecordAttribute()
    {
        $academicRecord = $this->academicRecords();

        $application = $this->active_application ?? false;
        $admission = $this->active_admission ?? false;
        $academicRecord->when($application, function ($query) use ($application) {
            return $query->where('application_id', $application['id']);
        });
        $academicRecord->when($admission, function ($query) use ($admission) {
            return $query->where('admission_id', $admission['id']);
        });

        return $academicRecord->first();
    }

    public function getLatestAcademicRecordAttribute()
    {
        return $this->academicRecords()->where('academic_record_status_id', 3)->latest()->first();
    }

    public function getLatestManualAcademicRecordAttribute()
    {
        return $this->academicRecords()->where('is_manual', 1)->latest()->first();
    }
    
    public function getActiveTranscriptRecordAttribute()
    {
        $draftStatus = 1; // draft transcript status
        return $this->transcriptRecords()->where('transcript_record_status_id', $draftStatus)->latest()->first();
    }

    public function getNameAttribute()
    {
        return ucfirst($this->first_name) . ($this->middle_name ? ' ' . ucfirst($this->middle_name) . ' ' : ' ') . ucfirst($this->last_name);
    }

    public function getAgeAttribute()
    {
        return Carbon::parse($this->attributes['birth_date'])->age;
    }

    public function getCurrentAddressAttribute()
    {
        if ($this->address) {
            $houseNoStreet = ucfirst($this->address->current_house_no_street);
            $barangay = ucfirst($this->address->current_barangay);
            $city = ucfirst($this->address->current_city_town);
            $province = ucfirst($this->address->current_province);
            $country = ucfirst($this->address->currentCountry->name);
            $collection = collect([$houseNoStreet, $barangay, $city, $province, $country])->implode(', ');
            return $collection;
        }
        return null;
    }

    public function getPermanentAddressAttribute()
    {
        if ($this->address) {
            $houseNoStreet = ucfirst($this->address->permanent_house_no_street);
            $barangay = ucfirst($this->address->permanent_barangay);
            $city = ucfirst($this->address->permanent_city_town);
            $province = ucfirst($this->address->permanent_province);
            $country = ucfirst($this->address->permanentCountry->name);
            $collection = collect([$houseNoStreet, $barangay, $city, $province, $country])->implode(', ');
            return $collection;
        }
        return null;
    }
}
