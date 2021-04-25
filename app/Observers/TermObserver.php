<?php

namespace App\Observers;

use App\Term;
use App\AcademicRecord;

class TermObserver
{
    /**
     * Handle the term "created" event.
     *
     * @param  \App\Term  $term
     * @return void
     */
    public function created(Term $term)
    {
        // $academicRecords = AcademicRecord::where('school_category_id', $term->school_category_id)
        // ->where('academic_record_status_id', 3)->get();
        // foreach ($academicRecords as $academicRecord) {
        //     $subjects = $academicRecord->subjects()->get();
        //     $studentGrades = $academicRecord->grades();
        //     foreach ($subjects as $subject) {
        //         $item = [
        //             'subject_id' => $subject['id'],
        //             'personnel_id' => null,
        //             'grade' => 0,
        //             'notes' => ''
        //         ];
        //         $studentGrades->wherePivot('subject_id', $subject['id'])->attach($term->id, $item);
        //     }
        // }
    }

    /**
     * Handle the term "updated" event.
     *
     * @param  \App\Term  $term
     * @return void
     */
    public function updated(Term $term)
    {
        //
    }

    /**
     * Handle the term "deleted" event.
     *
     * @param  \App\Term  $term
     * @return void
     */
    public function deleted(Term $term)
    {
        // $academicRecords = AcademicRecord::where('school_category_id', $term->school_category_id)
        //     ->where('academic_record_status_id', 3)->get();
        // foreach ($academicRecords as $academicRecord) {
        //     $studentGrades = $academicRecord->grades();
        //     $studentGrades->detach($term->id);
        // }
    }

    /**
     * Handle the term "restored" event.
     *
     * @param  \App\Term  $term
     * @return void
     */
    public function restored(Term $term)
    {
        //
    }

    /**
     * Handle the term "force deleted" event.
     *
     * @param  \App\Term  $term
     * @return void
     */
    public function forceDeleted(Term $term)
    {
        //
    }
}
