<?php

namespace App\Observers;

use App\GradingPeriod;
use App\AcademicRecord;
use App\GradingPeriods;
use App\Services\TranscriptRecordService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class AcademicRecordObserver
{
    private function updateDependecies(AcademicRecord $academicRecord)
    {
        switch ($academicRecord->academic_record_status_id) {
            case Config::get('constants.academic_record_status.ENROLLED'):
                // NOTE: all business logic should be move to service
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
                break;
            // case Config::get('constants.academic_record_status.FINALIZED'):
            //     // link active transcript to academic record registration
            //     $transcriptService = new TranscriptRecordService();
            //     $transcript = $transcriptService->activeFirstOrCreate($academicRecord);
            //     if ($transcript && !$academicRecord->transcript_record_id) {
            //         $academicRecord->update(['transcript_record_id' => $transcript->id]);
            //     }
            //     break;
            default:
                break;
        }
    }
    /**
     * Handle the academic record "creating" event.
     *
     * @param  \App\AcademicRecord  $academicRecord
     * @return void
     */
    public function creating(AcademicRecord $academicRecord)
    {
        $enrolledStatus = Config::get('constants.academic_record_status.ENROLLED');
        if ($academicRecord->academic_record_status_id === $enrolledStatus) {
            $academicRecord->enrolled_date = Carbon::now();
            if ($academicRecord->application) {
                $academicRecord->application->update([
                    'is_completed' => 1,
                    'completed_date' => Carbon::now()
                ]);
            }
        }
    }

    /**
     * Handle the academic record "updated" event.
     *
     * @param  \App\AcademicRecord  $academicRecord
     * @return void
     */
    public function updating(AcademicRecord $academicRecord)
    {
        $enrolledStatus = Config::get('constants.academic_record_status.ENROLLED');
        if ($academicRecord->academic_record_status_id === $enrolledStatus) {
            $academicRecord->enrolled_date = Carbon::now();
            if ($academicRecord->application) {
                $academicRecord->application->update([
                    'is_completed' => 1,
                    'completed_date' => Carbon::now()
                ]);
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
