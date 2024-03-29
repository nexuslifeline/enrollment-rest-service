<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

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

    public function grades()
    {
        return $this->belongsToMany('App\GradingPeriod', 'student_grade_periods', 'student_grade_id', 'grading_period_id')
            ->withPivot('grade','academic_record_id');
    }

    public function getGradingPeriodsAttribute()
    {   
        return GradingPeriod::where('school_year_id', $this->section->school_year_id)
            ->where('school_category_id', $this->section->school_category_id)
            ->where('semester_id', $this->section->semester_id)
            ->get();
    }

    public function personnel()
    {
        return $this->belongsTo('App\Personnel');
    }

    public function section()
    {
        return $this->belongsTo('App\Section');
    }

    public function getStudentsAttribute()
    {
        $sectionId = $this->section_id;
        $subjectId = $this->subject_id;
        $enrolled = Config::get('constants.academic_record_status.ENROLLED');
        return Student::leftJoin('academic_records', 'academic_records.student_id', '=', 'students.id')
            ->leftJoin('academic_record_subjects as subjects', 'subjects.academic_record_id', '=', 'academic_records.id')
            ->where('subjects.section_id', $sectionId)
            ->where('subjects.subject_id', $subjectId)
            ->where('academic_records.academic_record_status_id', $enrolled)
            ->with('photo')
            ->get(['first_name', 'middle_name', 'last_name', 'students.id'])
            ->makeHidden(['address','current_address','permanent_address','age']);
        // return Student::whereHas('latestAcademicRecord', function ($q) use ($sectionId, $subjectId) {
        //     return $q->whereHas('subjects', function ($q) use ($sectionId, $subjectId) {
        //         return $q->where('section_id', $sectionId)
        //         ->where('subject_id', $subjectId);
        //     });
        // })
        // ->with('photo')
        // ->get(['first_name', 'middle_name', 'last_name', 'id'])
        // ->makeHidden(['address','current_address','permanent_address','age']);
    }

    public function subject()
    {
        return $this->belongsTo('App\Subject');
    }

    public function scopeFilters($query, $filters)
    {
        //filter by student id
        $studentId = $filters['student_id'] ?? false;
        $query->when($studentId, function ($q) use ($studentId) {
            return $q->where('student_id', $studentId);
        });

        //filter by school year id
        $schoolYearId = $filters['school_year_id'] ?? false;
        $query->when($schoolYearId, function ($q) use ($schoolYearId) {
            return $q->where('school_year_id', $schoolYearId);
        });

        //filter by course id
        $courseId = $filters['course_id'] ?? false;
        $query->when($courseId, function ($q) use ($courseId) {
            return $q->where('course_id', $courseId);
        });

        //filter by level id
        $levelId = $filters['level_id'] ?? false;
        $query->when($levelId, function ($q) use ($levelId) {
            return $q->where('level_id', $levelId);
        });

        //filter by semester id
        $semesterId = $filters['semester_id'] ?? false;
        $query->when($semesterId, function ($q) use ($semesterId) {
            return $q->where('semester_id', $semesterId);
        });

        //filter by personnel id
        $personnelId = $filters['personnel_id'] ?? false;
        $query->when($personnelId, function ($q) use ($personnelId) {
            return $q->where('personnel_id', $personnelId);
        });

        // filter by current user
        $filterByUser = $filters['filter_by_user'] ?? false;
        $query->when($filterByUser, function ($q) {
            return $q->where('personnel_id', Auth::user()->userable->id);
        });

        //filter by status id
        $studentGradeStatusId = $filters['student_grade_status_id'] ?? false;
        $query->when($studentGradeStatusId, function ($query) use ($studentGradeStatusId) {
            if (!is_array($studentGradeStatusId)) {
                return $query->where('student_grade_status_id', $studentGradeStatusId);
            } else {
                return $query->whereIn('student_grade_status_id', $studentGradeStatusId);
            }
        });

        //filter by subject id
        $subjectId = $filters['subject_id'] ?? false;
        $query->when($subjectId, function ($q) use ($subjectId) {
            return $q->where('subject_id', $subjectId);
        });

        //filter by section id
        $sectionId = $filters['section_id'] ?? false;
        $query->when($subjectId, function ($q) use ($sectionId) {
            return $q->where('section_id', $sectionId);
        });

        return $query;
    }
}
