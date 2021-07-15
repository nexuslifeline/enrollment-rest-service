<?php

namespace App\Observers;

use App\Term;
use Exception;
use App\TranscriptRecord;
use Illuminate\Support\Facades\Log;
use App\Services\TranscriptRecordService;

class TranscriptRecordObserver
{
    /**
     * Handle the transcript record "created" event.
     *
     * @param  \App\TranscriptRecord  $transcriptRecord
     * @return void
     */
    public function created(TranscriptRecord $transcriptRecord)
    {
        //
    }

    /**
     * Handle the transcript record "updated" event.
     *
     * @param  \App\TranscriptRecord  $transcriptRecord
     * @return void
     */
    public function updated(TranscriptRecord $transcriptRecord)
    {
        try {
            // if curriculum changed and subjects are not locked
            // we will sync the subjects from the transcript record
            // we will do nothing if subjects are locked
            if ($transcriptRecord->curriculum_id && !$transcriptRecord->is_subjects_locked) {
                $transcriptService = new TranscriptRecordService();
                $transcriptService->syncCurriculumSubjects(
                    $transcriptRecord->id,
                    $transcriptRecord->curriculum_id
                );
            }
        } catch (Exception $e) {
            Log::info('Error occured during TranscriptRecordObserver updated event call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle the transcript record "deleted" event.
     *
     * @param  \App\TranscriptRecord  $transcriptRecord
     * @return void
     */
    public function deleted(TranscriptRecord $transcriptRecord)
    {
        //
    }

    /**
     * Handle the transcript record "restored" event.
     *
     * @param  \App\TranscriptRecord  $transcriptRecord
     * @return void
     */
    public function restored(TranscriptRecord $transcriptRecord)
    {
        //
    }

    /**
     * Handle the transcript record "force deleted" event.
     *
     * @param  \App\TranscriptRecord  $transcriptRecord
     * @return void
     */
    public function forceDeleted(TranscriptRecord $transcriptRecord)
    {
        //
    }
}
