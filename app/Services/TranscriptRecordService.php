<?php

namespace App\Services;

use Exception;
use App\Curriculum;
use App\AcademicRecord;
use App\TranscriptRecord;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;

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

      //not transcript record status id
      $notTranscriptRecordStatusId = $filters['not_transcript_record_status_id'] ?? false;
      $query->when($notTranscriptRecordStatusId, function ($q) use ($notTranscriptRecordStatusId) {
        return $q->where('transcript_record_status_id', '!=', $notTranscriptRecordStatusId);
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

      //criteria
      $criteria = $filters['criteria'] ?? false;
      // return $criteria;
      $query->when($criteria, function ($q) use ($criteria) {
        $q->whereHas('student', function ($query) use ($criteria) {
          // return $query->where('name', 'like', '%' . $criteria . '%')
          //   ->orWhere('student_no', 'like', '%' . $criteria . '%')
          //   ->orWhere('first_name', 'like', '%' . $criteria . '%')
          //   ->orWhere('middle_name', 'like', '%' . $criteria . '%')
          //   ->orWhere('last_name', 'like', '%' . $criteria . '%')
          //   ->orWhere('email', 'like', '%' . $criteria . '%');
          return  $query->whereLike($criteria);
        })
          ->orWhereHas('course', function ($query) use ($criteria) {
            return $query->where('description', 'like', '%' . $criteria . '%');
          })
          ->orWhereHas('level', function ($query) use ($criteria) {
            return $query->where('name', 'like', '%' . $criteria . '%');
          })
          ->orWhereHas('curriculum', function ($query) use ($criteria) {
            return $query->where('name', 'like', '%' . $criteria . '%');
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

  public function update(array $data, array $subjects, int $id)
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

      // $transcriptRecord->requirements()->sync($requirements);

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
        'level',
        'course',
        'curriculum',
        'studentCurriculum',
        'student' => function ($query) {
          $query->with(['address', 'photo']);
        },
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


  public function getLevels(int $id, bool $isPaginated, int $perPage, array $filters)
  {
    try {
      $query = TranscriptRecord::find($id)
        ->levels()
        ->distinct();

      $levels = $isPaginated
        ? $query->paginate($perPage)
        : $query->get();
      return $levels;
    } catch (Exception $e) {
      Log::info('Error occured during TranscriptRecordService getLevels method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  private function makeIndexedArray(string $key, array $source, array $mappers)
  {
    if (Arr::exists($source, $key)) return [];

    $items = [];
    foreach ($source as $item) {
      $values = [];
      foreach ($mappers as $mapper) {
        if (Arr::exists($item, $mapper) && $mapper !== $key) {
          $values[$mapper] = $item[$mapper];
        }
      }
      $items[$item[$key]] = $values;
    }

    return $items;
  }

  private function createPivotInstanceProps($pivot, array $props)
  {
    foreach($props as $key => $value) {
      $pivot->{$key} = $value;
    }
    return $pivot;
  }

  public function updateSubjects(array $subjects, int $id)
  {
    DB::beginTransaction();
    try {
      $transcriptRecord = TranscriptRecord::find($id);

      // this is to make sure other fields in the pivot table will not be deleted
      // we just need to update each record with the provided fields
      $data = $this->makeIndexedArray('subject_id', $subjects, [
        'subject_id',
        'semester_id',
        'is_taken',
        'grade',
        'notes'
      ]);

      $transcriptSubjects = $transcriptRecord->subjects()->get();

      // intead of syncing we will just  update each pivot item to make sure other properties will not be lost
      foreach($transcriptSubjects as $subject) {
        $subjectId = $subject->pivot->subject_id;
        if (Arr::exists($data, $subjectId)) {
          $this->createPivotInstanceProps(
            $subject->pivot,
            $data[$subjectId]
          )->save();
        }
      }

      DB::commit();
      return $transcriptSubjects;
    } catch (Exception $e) {
      DB::rollback();
      Log::info('Error occured during TranscriptRecordService updateSubjects method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  // returns active transcript, if no active transcript found new active transcript will be created and returned
  public function activeFirstOrCreate(int $academicRecordId)
  {
    DB::beginTransaction();
    try {
      $academicRecord = AcademicRecord::find($academicRecordId);

      if (!$academicRecord) {
        Log::warning('No academic record found using id: ' . $academicRecordId);
        return;
      }

      // if there is an active transcript return it
      $transcript = TranscriptRecord::where('student_id', $academicRecord->student_id)
        ->where('transcript_record_status_id', Config::get('constants.transcript_record_status.DRAFT'))
        ->first();

      if ($transcript) return $transcript;

      // if no active transcript, create and return it
      $data =  $academicRecord->only([
        //'curriculum_id',
        //'student_curriculum_id',
        'student_id',
        'school_category_id',
        'level_id',
        'course_id'
      ]);
      $transcript = TranscriptRecord::create($data);

      // get subjects of the curriculum and trim only those needed fields in transcript subjects
      $query = Curriculum::find($academicRecord->curriculum_id)->subjects();
      $subjects = $query->get()->map(function($item) {
          $pivot = $item->pivot;
          return [
            'school_category_id' => $pivot->school_category_id,
            'course_id' => $pivot->course_id,
            'level_id' => $pivot->level_id,
            'semester_id' => $pivot->semester_id,
            'subject_id' => $pivot->subject_id
          ];
        })->toArray();

      $transcript->subjects()->attach($subjects);
      DB::commit();
      return $transcript;
    } catch (Exception $e) {
      DB::rollback();
      Log::info('Error occured during TranscriptRecordService activeFirstOrCreate method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  public function syncCurriculumSubjects(int $transcriptRecordId, int $curriculumId)
  {
    DB::beginTransaction();
    try {
      $query = Curriculum::find($curriculumId)->subjects();
      $subjects = $query->get()->map(function($item) {
        $pivot = $item->pivot;
        return [
          'school_category_id' => $pivot->school_category_id,
          'course_id' => $pivot->course_id,
          'level_id' => $pivot->level_id,
          'semester_id' => $pivot->semester_id,
          'subject_id' => $pivot->subject_id
        ];
      })->toArray();

      $transcriptRecord = TranscriptRecord::find($transcriptRecordId);
      $transcriptQuery = $transcriptRecord->subjects();

      // we just need to delete those subjects without grade
      $this->deleteEmptyGradeSubjects($transcriptRecord);

      // since we did not deleted subjects that has grade or notes in current transcript subjects
      // we need to make sure these subjects will not be attached again
      $subjectsWithoutGrade = $this->removeWithGrade(
        $subjects,
        $transcriptQuery->get()
      );

      $transcriptQuery->attach($subjectsWithoutGrade);
      DB::commit();
      return $transcriptRecord;
    } catch (Exception $e) {
      DB::rollback();
      Log::info('Error occured during TranscriptRecordService syncCurriculumSubjects method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  private function hasData(int $subjectId, $gradedSubjects)
  {
    return $gradedSubjects->some(function ($gradedValue) use($subjectId) {
      $pivot = $gradedValue->pivot;
      return $gradedValue->id === $subjectId && (
        (((int)$pivot->grade) > 0) || $pivot->notes || $pivot->system_notes
      );
    });
  }

  private function removeWithGrade(array $sourceSubjects, $gradedSubjects)
  {
    return Arr::where($sourceSubjects, function ($sourceValue, $key) use($gradedSubjects) {
      return !$this->hasData($sourceValue['subject_id'], $gradedSubjects);
    });
  }

  private function deleteEmptyGradeSubjects(TranscriptRecord $transcriptRecord)
  {
    try {
      $subjects =  $transcriptRecord->subjects()->get();
      foreach($subjects as $subject) {
        $pivot =$subject->pivot;
        $hasData = (((int)$pivot->grade) > 0) || $pivot->notes || $pivot->system_notes;
        if (!$hasData) {
          $transcriptRecord->subjects()->detach($subject->id);
        }
      }
    } catch (Exception $e) {
      Log::info('Error occured during TranscriptRecordService deleteEmptyGradeSubjects method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }


}
