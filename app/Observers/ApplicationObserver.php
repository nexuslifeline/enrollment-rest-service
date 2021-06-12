<?php

namespace App\Observers;

use App\Application;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class ApplicationObserver
{

    /**
     * Handle the application "updating" event.
     *
     * @param  \App\Application  $application
     * @return void
     */
    public function updating(Application $application)
    {
        $originalApplication = $application->getOriginal();
        $requestEvaluationStep = Config::get('constants.application_steps.REQUEST_EVALUATION');
        $waitingEvaluationStep = Config::get('constants.application_steps.WAITING_EVALUATION');
        $academicYearApplicationStep = Config::get('constants.application_steps.ACADEMIC_YEAR_APPLICATION');
        $status = Config::get('constants.application_steps.STATUS ');
        $user = Auth::user();
        if ($user->userable_type === 'App\Student') {
            if ($originalApplication->application_step_id === $requestEvaluationStep && $application->application_step_id === $waitingEvaluationStep) {
                $application->date_submitted = Carbon::now();
            }
            if ($originalApplication->application_step_id === $academicYearApplicationStep && $application->application_step_id === $status) {
                $application->date_submitted = Carbon::now();
            }
        }
        
    }
}
