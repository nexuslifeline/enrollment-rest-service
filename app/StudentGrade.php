<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

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
            ->withPivot('grade');
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
        $query->when($studentGradeStatusId, function ($q) use ($studentGradeStatusId) {
            return $q->where('student_grade_status_id', $studentGradeStatusId);
        });

        //filter by subject id
        $subjectId = $filters['subject_id'] ?? false;
        $query->when($subjectId, function ($q) use ($subjectId) {
            return $q->where('subject_id', $subjectId);
        });

        return $query;
    }
}
