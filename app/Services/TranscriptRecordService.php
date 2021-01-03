<?php

namespace App\Services;

use App\TranscriptRecord;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class TranscriptRecordService
{
  public function list(bool $isPaginated, int $perPage, array $filters)
  {
    try {
      $query = TranscriptRecord::with([
        'level',
        'course',
        'curriculum',
        'studentCurriculum',
        'student' => function ($query) {
          $query->with(['address', 'photo']);
        }
      ]);

      //school category id
      $schoolCategoryId = $filters['school_category_id'] ?? false;
      $query->when($schoolCategoryId, function ($q) use ($schoolCategoryId) {
        $q->whereHas('schoolCategory', function ($query) use ($schoolCategoryId) {
          return $query->where('school_category_id', $schoolCategoryId);
        });
      });

      //transcript record status id
      $transcriptRecordStatusId = $filters['transcript_record_status_id'] ?? false;
      $query->when($transcriptRecordStatusId, function ($q) use ($transcriptRecordStatusId) {
        return $q->where('transcript_record_status_id', $transcriptRecordStatusId);
      });

      //course id
      $courseId = $filters['course_id'] ?? false;
      $query->when($courseId, function ($q) use ($courseId) {
        $q->whereHas('course', function ($query) use ($courseId) {
          return $query->where('course_id', $courseId);
        });
      });

      //level id
      $levelId = $filters['level_id'] ?? false;
      $query->when($levelId, function ($q) use ($levelId) {
        $q->whereHas('level', function ($query) use ($levelId) {
          return $query->where('level_id', $levelId);
        });
      });

      $transcriptRecords = $isPaginated
        ? $query->paginate($perPage)
        : $query->get();
      return $transcriptRecords;
    } catch (Exception $e) {
      Log::info('Error occured during TranscriptRecordService list method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  public function update(array $data, array $subjects, array $requirements, int $id)
  {
    DB::beginTransaction();
    try {
      $transcriptRecord = TranscriptRecord::find($id);
      // return $transcriptRecord;
      $transcriptRecord->update($data);

      if ($subjects) {
        $items = [];
        foreach ($subjects as $subject) {
          $items[$subject['subject_id']] = [
            'level_id' => $subject['level_id'],
            'semester_id' => $subject['semester_id'],
            'is_taken' => $subject['is_taken'],
            'grade' => $subject['grade'],
            'notes' => $subject['notes']
          ];
        }
        // return $items;
        $transcriptRecord->subjects()->sync($items);
      }

      $transcriptRecord->requirements()->sync($requirements);

      DB::commit();
      return $transcriptRecord;
    } catch (Exception $e) {
      DB::rollback();
      Log::info('Error occured during TranscriptRecordService update method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  public function get(int $id)
  {
    try {
      $transcriptRecord = TranscriptRecord::find($id);
      $transcriptRecord->load([
        'subjects' => function ($query) {
          $query->with('prerequisites');
        },
      ]);
      return $transcriptRecord;
    } catch (Exception $e) {
      Log::info('Error occured during TranscriptRecordService get method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }
}
