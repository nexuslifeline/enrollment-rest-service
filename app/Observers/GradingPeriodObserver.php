<?php

namespace App\Observers;

use App\AcademicRecord;
use App\GradingPeriod;

class GradingPeriodObserver
{
    /**
     * Handle the grading period "created" event.
     *
     * @param  \App\GradingPeriod  $gradingPeriod
     * @return void
     */
    public function created(GradingPeriod $gradingPeriod)
    {
        $academicRecords = AcademicRecord::where('school_category_id', $gradingPeriod->school_category_id)
        ->where('academic_record_status_id', 3)->get();
        foreach ($academicRecords as $academicRecord) {
            $subjects = $academicRecord->subjects()->get();
            $studentGrades = $academicRecord->grades();
            foreach ($subjects as $subject) {
                $item = [
                    'subject_id' => $subject['id'],
                    'personnel_id' => null,
                    'grade' => 0,
                    'notes' => ''
                ];
                $studentGrades->wherePivot('subject_id', $subject['id'])->attach($gradingPeriod->id, $item);
            }
        }
    }

    /**
     * Handle the grading period "updated" event.
     *
     * @param  \App\GradingPeriod  $gradingPeriod
     * @return void
     */
    public function updated(GradingPeriod $gradingPeriod)
    {
        //
    }

    /**
     * Handle the grading period "deleted" event.
     *
     * @param  \App\GradingPeriod  $gradingPeriod
     * @return void
     */
    public function deleted(GradingPeriod $gradingPeriod)
    {
        // $academicRecords = AcademicRecord::where('school_category_id', $gradingPeriod->school_category_id)
        //     ->where('academic_record_status_id', 3)->get();
        // foreach ($academicRecords as $academicRecord) {
        //     $studentGrades = $academicRecord->grades();
        //     $studentGrades->detach($gradingPeriod->id);
        // }
    }

    /**
     * Handle the grading period "restored" event.
     *
     * @param  \App\GradingPeriod  $gradingPeriod
     * @return void
     */
    public function restored(GradingPeriod $gradingPeriod)
    {
        //
    }

    /**
     * Handle the grading period "force deleted" event.
     *
     * @param  \App\GradingPeriod  $gradingPeriod
     * @return void
     */
    public function forceDeleted(GradingPeriod $gradingPeriod)
    {
        //
    }
}
