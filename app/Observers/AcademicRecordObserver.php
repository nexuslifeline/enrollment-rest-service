<?php

namespace App\Observers;

use App\AcademicRecord;
use App\Services\StudentGradeService;
use App\Services\TermService;
use App\Term;

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
        //
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
            $terms = Term::where('school_category_id', $academicRecord->school_category_id)
                ->where('school_year_id', $academicRecord->school_year_id)
                ->where('semester_id', $academicRecord->semester_id)
                ->get()
                ->pluck('id');
            $studentGrades = $academicRecord->student->grades();
            foreach ($subjects as $subject) {
                $studentGrade = $studentGrades->create([
                    'section_id' => $subject['pivot']['section_id'],
                    'subject_id' => $subject['id']
                ]);
                $studentGrade->details()->sync($terms);
                // $items = [];
                // foreach ($terms as $term) {
                //     $items[$term['term_id']] = [
                //         'personnel_id' => null,
                //         'grade' => 0,
                //         'notes' => ''
                //     ];
                // }
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
