<?php

namespace App\Services;

use App\Application;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApplicationService
{
  public function requestEvaluation(int $applicationId)
  {
    DB::beginTransaction();
    try {
      $application = Application::find($applicationId);
      $student = $application->student;
      $evaluation = $student->evaluation;
      $academicRecord = $student->academicRecord;
      $evaluationPendingStatus = Config::get('constants.academic_record_status.EVALUATION_PENDING');

      $academicRecord->update([
        'academic_record_status_id' => $evaluationPendingStatus
      ]);

      $evaluation->update([
        'submitted_date' => Carbon::now()
      ]);
      DB::commit();
      return $application;
    } catch (Exception $e) {
      DB::rollBack();
      Log::info('Error occured during ApplicationService requestEvaluation method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  public function submit(int $applicationId)
  {
    DB::beginTransaction();
    try {
      $application = Application::find($applicationId);
      $student = $application->student;
      $evaluation = $student->evaluation;
      $academicRecord = $student->academicRecord;
      $enlistmentPendingStatus = Config::get('constants.academic_record_status.ENLISTMENT_PENDING');

      $academicRecord->update([
        'academic_record_status_id' => $enlistmentPendingStatus
      ]);

      $application->update([
        'applied_date' => Carbon::now()
      ]);

      DB::commit();
      return $application;
    } catch (Exception $e) {
      DB::rollBack();
      Log::info('Error occured during ApplicationService submit method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }
}