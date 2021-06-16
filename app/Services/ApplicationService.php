<?php

namespace App\Services;

use App\Application;
use App\Level;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApplicationService
{
  public function requestEvaluation(array $data, array $academicRecordData, int $applicationId)
  {
    DB::beginTransaction();
    try {
      $application = Application::find($applicationId);
      $academicRecord = $application->academicRecord;
      $evaluation = $academicRecord->evaluation;
      $evaluationPendingStatus = Config::get('constants.academic_record_status.EVALUATION_PENDING');

      $level = Level::find($academicRecordData['level_id']);
      $academicRecordData['academic_record_status_id'] = $evaluationPendingStatus;
      $academicRecordData['school_category_id'] = $level->school_category_id;
      $academicRecord->update($academicRecordData);

      $data['submitted_date'] = Carbon::now();
      $evaluation->update($data);

      // if ($academicRecord->application) {
      //   $evaluationReview = Config::get('constants.onboarding_step.EVALUATION_IN_REVIEW');
      //   $academicRecord->application->update([
      //       'application_step_id' => $evaluationReview
      //   ]);
      // }

      DB::commit();
      return $evaluation->load('academicRecord');
    } catch (Exception $e) {
      DB::rollBack();
      Log::info('Error occured during ApplicationService requestEvaluation method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  public function submit(array $data, array $subjects, int $applicationId)
  {
    DB::beginTransaction();
    try {
      $application = Application::find($applicationId);
      $academicRecord = $application->academicRecord;
      $enlistmentPendingStatus = Config::get('constants.academic_record_status.ENLISTMENT_PENDING');
      
      $data['academic_record_status_id'] = $enlistmentPendingStatus;
      $academicRecord->update($data);

      $items = [];
      foreach ($subjects as $subject) {
        $items[$subject['subject_id']] = [
          'section_id' => $subject['section_id']
        ];
      }

      $academicRecord->subjects()->sync($items);

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