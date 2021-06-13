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
  public function requestEvaluation(array $data, int $applicationId, int $levelId)
  {
    DB::beginTransaction();
    try {
      $application = Application::find($applicationId);
      $academicRecord = $application->academicRecord;
      $evaluation = $academicRecord->evaluation;
      $evaluationPendingStatus = Config::get('constants.academic_record_status.EVALUATION_PENDING');

      $academicRecord->update([
        'academic_record_status_id' => $evaluationPendingStatus,
        'level_id' => $levelId
      ]);
      $data['submitted_date'] = Carbon::now();
      $evaluation->update($data);

      DB::commit();
      return $evaluation->load('academicRecord');
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
      $academicRecord = $application->academicRecord;
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