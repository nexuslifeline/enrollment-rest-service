<?php

namespace App\Observers;

use App\StudentFee;
use App\Term;

class StudentFeeObserver
{
    /**
     * Handle the student fee "created" event.
     *
     * @param  \App\StudentFee  $studentFee
     * @return void
     */
    public function created(StudentFee $studentFee)
    {
        //
    }

    /**
     * Handle the student fee "updated" event.
     *
     * @param  \App\StudentFee  $studentFee
     * @return void
     */
    public function updated(StudentFee $studentFee)
    {
        if ($studentFee->student_fee_status_id === 2) {
            $studentFee->recomputeTerms();
        }
    }

    /**
     * Handle the student fee "deleted" event.
     *
     * @param  \App\StudentFee  $studentFee
     * @return void
     */
    public function deleted(StudentFee $studentFee)
    {
        //
    }

    /**
     * Handle the student fee "restored" event.
     *
     * @param  \App\StudentFee  $studentFee
     * @return void
     */
    public function restored(StudentFee $studentFee)
    {
        //
    }

    /**
     * Handle the student fee "force deleted" event.
     *
     * @param  \App\StudentFee  $studentFee
     * @return void
     */
    public function forceDeleted(StudentFee $studentFee)
    {
        //
    }
}
