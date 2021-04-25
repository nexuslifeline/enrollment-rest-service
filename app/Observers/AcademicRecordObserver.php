<?php

namespace App\Observers;

use App\AcademicRecord;
use App\GradingPeriod;
use App\GradingPeriods;

class AcademicRecordObserver
{
    /**
     * Handle the academic record "created" event.
     *
     * @param  \App\AcademicRecord  $academicRecord
     * @return void
     */
    public function created(AcademicRecord $academicRecord)
    {
        if ($academicRecord->academic_record_status_id === 3) {
            $subjects = $academicRecord->subjects()->get();
            $gradingPeriods = GradingPeriod::where('school_category_id', $academicRecord->school_category_id)
                ->where('school_year_id', $academicRecord->school_year_id)
                ->where('semester_id', $academicRecord->semester_id)
                ->get()
                ->pluck('id');
            foreach ($subjects as $subject) {
                $studentGrades = $academicRecord->grades();
                $items = [];
                foreach ($gradingPeriods as $gradingPeriod) {
                    $items[$gradingPeriod] = [
                        'subject_id' => $subject['id'],
                        'personnel_id' => null,
                        'grade' => 0,
                        'notes' => ''
                    ];
                }
                $studentGrades->wherePivot('subject_id', $subject['id'])->sync($items);
            }
        }
    }

    /**
     * Handle the academic record "updated" event.
     *
     * @param  \App\AcademicRecord  $academicRecord
     * @return void
     */
    public function updated(AcademicRecord $academicRecord)
    {
        if ($academicRecord->academic_record_status_id === 3) {
            $subjects = $academicRecord->subjects()->get();
            $gradingPeriods = GradingPeriod::where('school_category_id', $academicRecord->school_category_id)
                ->where('school_year_id', $academicRecord->school_year_id)
                ->where('semester_id', $academicRecord->semester_id)
                ->get()
                ->pluck('id');
            foreach ($subjects as $subject) {
                $studentGrades = $academicRecord->grades();
                $items = [];
                foreach ($gradingPeriods as $gradingPeriod) {
                    $items[$gradingPeriod] = [
                        'subject_id' => $subject['id'],
                        'personnel_id' => null,
                        'grade' => 0,
                        'notes' => ''
                    ];
                }
                $studentGrades->wherePivot('subject_id', $subject['id'])->sync($items);
            }
        }
    }

    /**
     * Handle the academic record "deleted" event.
     *
     * @param  \App\AcademicRecord  $academicRecord
     * @return void
     */
    public function deleted(AcademicRecord $academicRecord)
    {
        //
    }

    /**
     * Handle the academic record "restored" event.
     *
     * @param  \App\AcademicRecord  $academicRecord
     * @return void
     */
    public function restored(AcademicRecord $academicRecord)
    {
        //
    }

    /**
     * Handle the academic record "force deleted" event.
     *
     * @param  \App\AcademicRecord  $academicRecord
     * @return void
     */
    public function forceDeleted(AcademicRecord $academicRecord)
    {
        //
    }
}
