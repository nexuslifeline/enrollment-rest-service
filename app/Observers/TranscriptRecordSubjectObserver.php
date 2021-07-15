<?php

namespace App\Observers;

use App\Term;
use Exception;
use App\TranscriptRecordSubject;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\Services\TranscriptRecordService;

class TranscriptRecordSubjectObserver
{
    /**
     * Handle the transcript record "created" event.
     *
     * @param  \App\TranscriptRecordSubject  $transcriptRecordSubject
     * @return void
     */
    public function created(TranscriptRecordSubject $transcriptRecordSubject)
    {
        //
    }

    /**
     * Handle the transcript record "updated" event.
     *
     * @param  \App\TranscriptRecordSubject  $transcriptRecordSubject
     * @return void
     */
    public function updated(TranscriptRecordSubject $transcriptRecordSubject)
    {
        try {
            $hasGrade = (int)$transcriptRecordSubject->grade > 0;

            if (!$hasGrade) return;

            $transcriptId = $transcriptRecordSubject->transcript_record_id;
            $transcriptService = new TranscriptRecordService();

            // if all subjects are taken and without failing grades, we need to mark the status as FINALIZED/COMPLETED
            if ($transcriptService->hasFailedOrNotTakenSubjects($transcriptId)) {
                $data = ['transcript_record_status_id' => Config::get('constants.transcript_record_status.DRAFT') ];
            } else {
                $data = ['transcript_record_status_id' => Config::get('constants.transcript_record_status.FINALIZED')];
            }
            $transcriptService->update($data, [], $transcriptId);
        } catch (Exception $e) {
            Log::info('Error occured during TranscriptRecordSubjectObserver updated event call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle the transcript record "deleted" event.
     *
     * @param  \App\TranscriptRecordSubject  $transcriptRecordSubject
     * @return void
     */
    public function deleted(TranscriptRecordSubject $transcriptRecordSubject)
    {
        //
    }

    /**
     * Handle the transcript record "restored" event.
     *
     * @param  \App\TranscriptRecordSubject  $transcriptRecordSubject
     * @return void
     */
    public function restored(TranscriptRecordSubject $transcriptRecordSubject)
    {
        //
    }

    /**
     * Handle the transcript record "force deleted" event.
     *
     * @param  \App\TranscriptRecordSubject  $transcriptRecordSubject
     * @return void
     */
    public function forceDeleted(TranscriptRecordSubject $transcriptRecordSubject)
    {
        //
    }
}
