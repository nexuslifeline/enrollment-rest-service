<?php

namespace App\Services;

use App\AcademicRecord;
use App\Section;
use App\StudentClearance;
use App\StudentClearanceSignatory;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentClearanceService
{
  public function list(bool $isPaginated, int $perPage, array $filters)
  {
    try {
      $query = StudentClearance::with([
        'student', 'academicRecord' => function ($query) {
          return $query->with(['level','course','semester','schoolYear']);
        }
      ]);
      // filters
      // student
      $studentId = $filters['student_id'] ?? false;
      $query->when($studentId, function ($q) use ($studentId) {
        return $q->whereHas('student', function ($query) use ($studentId) {
          return $query->where('student_id', $studentId);
        });
      });
      // school year
      $schoolYearId = $filters['school_year_id'] ?? false;
      $query->when($schoolYearId, function ($q) use ($schoolYearId) {
        return $q->whereHas('academicRecord', function ($query) use ($schoolYearId) {
          return $query->where('school_year_id', $schoolYearId);
        });
      });
      // semester
      $semesterId = $filters['semester_id'] ?? false;
      $query->when($semesterId, function ($q) use ($semesterId) {
        return $q->whereHas('academicRecord', function ($query) use ($semesterId) {
          return $query->where('semester_id', $semesterId);
        });
      });

      // course
      $courseId = $filters['course_id'] ?? false;
      $query->when($courseId, function ($q) use ($courseId) {
        return $q->whereHas('academicRecord', function ($q) use ($courseId) {
          return $q->where('course_id', $courseId);
        });
      });

      // level
      $levelId = $filters['level_id'] ?? false;
      $query->when($levelId, function ($q) use ($levelId) {
        return $q->whereHas('academicRecord', function ($q) use ($levelId) {
          return $q->where('level_id', $levelId);
        });
      });

      // school category
      $schoolCategoryId = $filters['school_category_id'] ?? false;
      $query->when($schoolCategoryId, function ($q) use ($schoolCategoryId) {
        return $q->whereHas('academicRecord', function ($q) use ($schoolCategoryId) {
          return $q->where('school_category_id', $schoolCategoryId);
        });
      });

      // filter by student name
      $criteria = $filters['criteria'] ?? false;
      $query->when($criteria, function ($q) use ($criteria) {
        return $q->whereHas('studentClearance', function ($query) use ($criteria) {
          return $query->whereHas('student', function ($q) use ($criteria) {
            return $q->whereLike($criteria);
          });
        });
      });

      $orderBy = $filters['order_by'] ?? false;
      $query->when($orderBy, function ($q) use ($orderBy, $filters) {
        $sort = $filters['sort'] ?? 'ASC';
        return $q->orderBy($orderBy, $sort);
      });

      $studentClearances = $isPaginated
        ? $query->paginate($perPage)
        : $query->get();
      return $studentClearances;
    } catch (Exception $e) {
      Log::info('Error occured during StudentClearanceService list method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  public function signatoriesList(bool $isPaginated, int $perPage, array $filters)
  {
    try {
      $query = StudentClearanceSignatory::with(['studentClearance' => function($q) {
        return $q->with(['student', 'academicRecord' => function ($q) {
          return $q->with(['level', 'course', 'semester', 'schoolYear']);
        }]);
      }])
      ->whereHas('studentClearance');

      //school year
      $schoolYearId = $filters['school_year_id'] ?? false;
      $query->when($schoolYearId, function ($query) use ($schoolYearId) {
        return $query->whereHas('studentClearance', function ($q) use ($schoolYearId) {
          return $q->whereHas('academicRecord', function ($query) use ($schoolYearId) {
            return $query->where('school_year_id', $schoolYearId);
          });
        });
      });

      //school category
      $schoolCategoryId = $filters['school_category_id'] ?? false;
      $query->when($schoolCategoryId, function ($query) use ($schoolCategoryId) {
        return $query->whereHas('studentClearance', function ($q) use ($schoolCategoryId) {
          return $q->whereHas('academicRecord', function ($query) use ($schoolCategoryId) {
            return $query->where('school_category_id', $schoolCategoryId);
          });
        });
      });

      //course
      $courseId = $filters['course_id'] ?? false;
      $query->when($courseId, function ($query) use ($courseId) {
        return $query->whereHas('studentClearance', function ($q) use ($courseId) {
          return $q->whereHas('academicRecord', function ($query) use ($courseId) {
            return $query->where('course_id', $courseId);
          });
        });
      });

      //semester
      $semesterId = $filters['semester_id'] ?? false;
      $query->when($semesterId, function ($query) use ($semesterId) {
        return $query->whereHas('studentClearance', function ($q) use ($semesterId) {
          return $q->whereHas('academicRecord', function ($query) use ($semesterId) {
            return $query->where('semester_id', $semesterId);
          });
        });
      });

      //level
      $levelId = $filters['level_id'] ?? false;
      $query->when($levelId, function ($query) use ($levelId) {
        return $query->whereHas('studentClearance', function ($q) use ($levelId) {
          return $q->whereHas('academicRecord', function ($query) use ($levelId) {
            return $query->where('level_id', $levelId);
          });
        });
      });

      //section
      $sectionId = $filters['section_id'] ?? false;
      $query->when($sectionId, function ($query) use ($sectionId) {
        return $query->whereHas('studentClearance', function ($q) use ($sectionId) {
          return $q->whereHas('academicRecord', function ($query) use ($sectionId) {
            return $query->where('section_id', $sectionId);
          });
        });
      });

      //personnel
      $personnelId = $filters['personnel_id'] ?? false;
      $query->when($personnelId, function ($q) use ($personnelId) {
          return $q->where('personnel_id', $personnelId);
      });

      // filter by student name
      $criteria = $filters['criteria'] ?? false;
      $query->when($criteria, function ($q) use ($criteria) {
        return $q->whereHas('studentClearance', function ($query) use ($criteria) {
          return $query->whereHas('student', function ($q) use ($criteria) {
            return $q->whereLike($criteria);
          });
        });
      });

      $studentClearances = $isPaginated
        ? $query->paginate($perPage)
        : $query->get();
      return $studentClearances;
    } catch (Exception $e) {
      Log::info('Error occured during StudentClearanceService signatorieslist method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  public function signatoriesUpdate(array $data) 
  {
    DB::beginTransaction();
    try {
      $studentClearanceSignatories = [];
      foreach ($data as $signatory) {
        $studentClearanceSignatory = StudentClearanceSignatory::find($signatory['id']);
        $studentClearanceSignatory->update($signatory);
        if ($signatory['is_cleared']) {
          $studentClearanceSignatory->update([
            'date_cleared' => Carbon::now()
          ]);
        }
        $studentClearanceSignatories[] = $studentClearanceSignatory;
      }
      DB::commit();
      return $studentClearanceSignatories;
    } catch (Exception $e) {
      Log::info('Error occured during StudentClearanceService signatoriesUpdate method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  public function get(int $id)
  {
    try {
      $studentClearance = StudentClearance::find($id);
      $studentClearance->load([
        'student', 'academicRecord' => function ($query) {
          return $query->with(['level', 'course', 'semester', 'schoolYear', 'section']);
        },'signatories'
      ]);
      return $studentClearance;
    } catch (Exception $e) {
      Log::info('Error occured during StudentClearanceService get method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  public function batchStore(array $data, array $signatories)
  {
    DB::beginTransaction();
    try {
      $academicRecords = AcademicRecord::where('section_id', $data['section_id'])->get();
      $studentClearances = [];
      foreach ($academicRecords as $academicRecord) {
        $studentClearance = StudentClearance::updateOrCreate([
          'academic_record_id' => $academicRecord->id,
          'student_id' => $academicRecord->student_id,
        ], [
          'academic_record_id' => $academicRecord->id,
          'student_id' => $academicRecord->student_id,
        ]);

        $items = [];

        foreach ($signatories as $signatory) {
          $items[$signatory['personnel_id']] = [
            'description' => $signatory['description']
          ];
        }

        $studentClearance->signatories()->wherePivot('subject_id', null)->sync($items);

        $items = [];
        if($data['include_instructor']) {
          $instructors = Section::find($data['section_id'])
            ->schedules()->with('subject')->select('personnel_id', 'subject_id')
            ->groupBy('personnel_id')
            ->groupBy('subject_id')
            ->get();
          // return $instructors;

          foreach ($instructors as $instructor) {
            $items[$instructor['subject_id']] = [
              'subject_id' => $instructor['subject_id'],
              'personnel_id' => $instructor['personnel_id'],
              'description' => $instructor['subject']['name'].' '.$instructor['subject']['description']
            ];
          }
        }

        $studentClearance->signatories()->wherePivot('subject_id', '!=', null)->sync($items);
        // return $items;
        
        // return $items;
        
        $studentClearances[] = $studentClearance;
      }
      DB::commit();
      return $studentClearances;
    } catch (Exception $e) {
      DB::rollback();
      Log::info('Error occured during StudentClearanceService storeBatchClearance method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  public function store(array $data, array $signatories)
  {
    DB::beginTransaction();
    try {
      $studentClearance = StudentClearance::create($data);
      DB::commit();
      return $studentClearance;
    } catch (Exception $e) {
      DB::rollback();
      Log::info('Error occured during StudentClearanceService store method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  public function update(int $id, array $data, array $signatories)
  {
    DB::beginTransaction();
    try {

      $studentClearance = StudentClearance::find($id);
      $items = [];
      $instructorItems = [];
      foreach ($signatories as $signatory) {
        if($signatory['subject_id']) {
          $instructorItems[$signatory['subject_id']] = [
            'subject_id' => $signatory['subject_id'],
            'personnel_id' => $signatory['personnel_id'],
            'description' => $signatory['description']
          ];
        } else {
          $items[$signatory['personnel_id']] = [
            'description' => $signatory['description']
          ];
        }
      }
      $studentClearance->signatories()->wherePivot('subject_id', null)->sync($items);
      $studentClearance->signatories()->wherePivot('subject_id', '!=' , null)->sync($instructorItems);

      $studentClearance->update($data);
      DB::commit();
      return $studentClearance;
    } catch (Exception $e) {
      DB::rollback();
      Log::info('Error occured during StudentClearanceService update method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  public function delete(int $id)
  {
    try {
      DB::beginTransaction();
      $studentClearance = StudentClearance::find($id);
      // if billing is soa update student_fee_term is_billed to 0 so you can create it again
      $studentClearance->delete();
      DB::commit();
    } catch (Exception $e) {
      DB::rollback();
      Log::info('Error occured during StudentClearanceService delete method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }
}
