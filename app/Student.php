<?php

namespace App;

use App\User;
use Carbon\Carbon;
use App\Evaluation;
use App\StudentPhoto;
use App\StudentFamily;
use App\StudentAddress;
use Illuminate\Support\Arr;
use App\StudentPreviousEducation;
use App\Traits\OrderingTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class Student extends Model
{
    use SoftDeletes, OrderingTrait;
    protected $guarded = ['id', 'name', 'current_address']; //added name on guarded to prevent updating, coz we already have name attrib
    protected $appends = ['name', 'age', 'current_address', 'permanent_address'];
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

    public function transcriptRecords()
    {
        return $this->hasMany('App\TranscriptRecord');
    }

    // public function getEvaluationAttribute()
    // {
    //     $completedStatus = 5;
    //     //return $this->hasOne('App\Evaluation');
    //     return  $this->evaluations()->where('evaluation_status_id', '!=', $completedStatus)->latest()->first();
    // }

    // public function getActiveEvaluationAttribute()
    // {
    //     // Note! should
    //     $enrolledStatus = Config::get('constants.academic_record_status.ENROLLED'); // Note! should be move in constants
    //     $closedStatus = Config::get('constants.academic_record_status.CLOSED');

    //     return $this->evaluations()
    //         ->whereHas('academicRecord', function ($q) use ($enrolledStatus, $closedStatus) {
    //             return $q->whereNotIn('academic_record_status_id', [$enrolledStatus, $closedStatus]);
    //         })
    //         // ->with('academicRecord')
    //         ->where('student_id', $this->id)
    //         ->latest()
    //         ->first();
    // }

    public function evaluations()
    {
        return $this->hasMany('App\Evaluation');
    }

    public function studentFees()
    {
        return $this->hasMany('App\StudentFee');
    }

    // public function getActiveAdmissionAttribute()
    // {
    //     $enrolledStatus = Config::get('constants.academic_record_status.ENROLLED'); // Note! should be move in constants
    //     return $this->admission()
    //         ->whereHas('academicRecord', function ($q) use ($enrolledStatus) {
    //             return $q->where('academic_record_status_id', '!=', $enrolledStatus);
    //         })
    //         ->with('academicRecord')
    //         ->where('student_id', $this->id)
    //         ->latest()
    //         ->first();
    // }

    // public function getActiveApplicationAttribute()
    // {
    //     $enrolledStatus = Config::get('constants.academic_record_status.ENROLLED'); // Note! should be move in constants
    //     $closedStatus = Config::get('constants.academic_record_status.CLOSED');
    //     return $this->applications()
    //         ->whereHas('academicRecord', function($q) use($enrolledStatus, $closedStatus) {
    //             return $q->whereNotIn('academic_record_status_id', [$enrolledStatus, $closedStatus]);
    //         })
    //         // ->with('academicRecord')
    //         ->where('student_id', $this->id)
    //         // ->where('is_completed', 0)
    //         ->latest()
    //         ->first();
    // }

    // public function getHasOpenApplicationAttribute()
    // {
    //     return $this->active_application ? true : false;
    // }

    public function getHasOpenAcademicRecordAttribute()
    {
        $enrolledStatus = Config::get('constants.academic_record_status.ENROLLED');
        $closedStatus = Config::get('constants.academic_record_status.CLOSED');

        $latestAcademicRecord = $this->latestAcademicRecord()->first();
        return $latestAcademicRecord && !in_array($latestAcademicRecord->academic_record_status_id, [$enrolledStatus, $closedStatus]) ? true : false ;
    }

    public function latestAcademicRecord()
    {
        // return $this->hasOne('App\AcademicRecord')->latest()->first();
        return $this->hasOne('App\AcademicRecord')->latest();
    }

    // public function getLatestAcademicRecordAttribute()
    // {
    //     $academicRecord = $this->academicRecords();
    //     $academicRecord->with([
    //         'level',
    //         'course',
    //         'semester',
    //         'evaluation' => function ($q) {
    //             return $q->with('lastSchoolLevel');
    //         },
    //         'application',
    //         'section',
    //         'studentType',
    //         'studentFee',
    //         'schoolCategory',
    //         'transcriptRecord' => function ($q) {
    //             return $q->with('curriculum');
    //         },
    //         'schoolYear'
    //     ]);
    //     return $academicRecord->latest()->first();
    // }

    // public function getHasInitialBillingAttribute()
    // {
    //     $academicRecord = $this->latest_academic_record->append('has_initial_billing');
    //     return $academicRecord->has_initial_billing;
    // }

    public function getIsEnrolledInActiveSyAttribute()
    {
        $latestAcademicRecord = $this->latestAcademicRecord()->first();
        return $latestAcademicRecord->schoolYear->is_active ? true : false;
    }

    // public function getActiveAcademicRecordAttribute()
    // {
    //     $enrolledStatus = Config::get('constants.academic_record_status.ENROLLED');
    //     return $this->academicRecords()->where('academic_record_status_id', $enrolledStatus)->with(['level', 'course', 'semester', 'schoolYear'])->latest()->first();
    // }

    // public function getLatestManualAcademicRecordAttribute()
    // {
    //     $enrolledStatus = Config::get('constants.academic_record_status.ENROLLED');
    //     return $this->academicRecords()->where('is_manual', 1)->where('academic_record_status_id', '!=', $enrolledStatus)->latest()->first();
    // }

    // public function getActiveTranscriptRecordAttribute()
    // {
    //     $draftStatus = Config::get('constants.transcript_record_status.DRAFT'); // draft transcript status
    //     $pendingStatus = Config::get('constants.transcript_record_status.PENDING'); // pending transcript status
    //     return $this->transcriptRecords()
    //         ->where('transcript_record_status_id', $draftStatus)
    //         ->orWhere('transcript_record_status_id', $pendingStatus)
    //         ->latest()
    //         ->first();
    // }

    public function getNameAttribute()
    {
        return ucfirst($this->first_name) . ($this->middle_name ? ' ' . ucfirst($this->middle_name) . ' ' : ' ') . ucfirst($this->last_name);
    }

    public function getAgeAttribute()
    {
        return Arr::exists($this->attributes, 'birth_date')
            ? Carbon::parse($this->attributes['birth_date'])->age
            : 0;
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

    // public function getRequirementPercentageAttribute()
    // {
    //     $academicRecord = $this->getLatestAcademicRecordAttribute();
    //     $percentage = 0;
    //     if ($academicRecord) {
    //         $count = Requirement::where('school_category_id', $academicRecord->school_category_id)->count();
    //         $studentRequirements = $this->requirements()->wherePivot('school_category_id', $academicRecord->school_category_id)->count();
    //         if ($count > 0) {
    //             $percentage = number_format(($studentRequirements / $count) * 100, 2);
    //         }
    //     }
    //     return $percentage;
    // }

    public function requirements()
    {
        return $this->belongsToMany(
            'App\Requirement',
            'student_requirements',
            'student_id',
            'requirement_id'
        )->withPivot('school_category_id', 'is_submitted')->withTimestamps();
    }

    public function scopeWhereLike($query, $value) {
        return $query->where('name', 'like', '%' . $value . '%')
            ->orWhere('first_name', 'like', '%' . $value . '%')
            ->orWhere('middle_name', 'like', '%' . $value . '%')
            ->orWhere('student_no', 'like', '%' . $value . '%')
            ->orWhere('last_name', 'like', '%' . $value . '%')
            ->orWhere('email', 'like', '%' . $value . '%')
            ->orWhereRaw('CONCAT(first_name, " ", coalesce(concat(middle_name, " "),""), last_name) like' .  "'%" .  $value  . "%'");
    }

    public function studentGrades()
    {
        return $this->hasMany('App\StudentGrade');
    }

    public function getIsPromoteCandidateAttribute()
    {
        $latestAcademicRecord =$this->latestAcademicRecord()->first(); 
        if (!$latestAcademicRecord) {
            return false;
        }

        $enrolledStatus = Config::get('constants.academic_record_status.ENROLLED');
        if (!$latestAcademicRecord->academic_record_status !== $enrolledStatus) {
            return false;
        }

        // Note! add other criteria here
        // ie. completed curriculum, no failing grades, clearance, etc.

        return true;
    }

    public function getPromoteLevelAttribute()
    {
        if (!$this->is_promote_candidate) {
            return null;
        }

        $latestAcademicRecord =$this->latestAcademicRecord()->first();

        $currentLevelId = $latestAcademicRecord->level_id;
        if (!$currentLevelId) {
            return null;
        }

        $currentSemesterId = $latestAcademicRecord->semester_id;
        $semesters = $currentSemesterId
            ? collect(Config::get('constants.semesters'))->where('id', '>', $currentSemesterId)
            : null;
        $levels = collect(Config::get('constants.levels'));

        if (!$semesters) {
            return $levels->where('id', '>', $currentLevelId)->first();
        }

        // check if next semester has subjects in curriculum
        $curriculum = $latestAcademicRecord->transcriptRecord->curriculum;

        if (!$curriculum) {
            return null;
        }

        $subjects = $curriculum->subjects();
        if (!$subjects) {
            return null;
        }

        $subjectsCount = $subjects->where('level_id', $currentLevelId)
            ->whereIn('semester_id', $semesters->pluck('id'))
            ->count();

        if ($subjectsCount === 0) {
            return $levels->where('id', '>', $currentLevelId)->first();
        }

    }

    public function getPromoteSemesterAttribute()
    {
        if (!$this->is_promote_candidate) {
            return null;
        }

        $latestAcademicRecord =$this->latestAcademicRecord()->first();
        $currentLevelId = $latestAcademicRecord->level_id;
        if (!$currentLevelId) {
            return null;
        }

        $currentSemesterId = $latestAcademicRecord->semester_id;
        if (!$currentSemesterId) {
            return null;
        }

        $semesters = collect(Config::get('constants.semesters'));
        if ($this->promote_level) {
            return $semesters->first();
        }

        $availableSems = $semesters->where('id', '>', $currentSemesterId);
        $curriculum = $latestAcademicRecord->transcriptRecord->curriculum;
        if (!$curriculum) {
            return null;
        }

        $subjects = $curriculum->subjects();
        if (!$subjects) {
            return null;
        }

        $semesterId = $subjects->where('level_id', $currentLevelId)
            ->whereIn('semester_id', $availableSems->pluck('id'))
            ->min('semester_id');
        return $availableSems->where('id', $semesterId)->first();
    }
}
