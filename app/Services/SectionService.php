<?php

namespace App\Services;

use App\Section;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SectionService
{
    public function list(bool $isPaginated, int $perPage, array $filters)
    {
        try {
          $query = Section::with(['schoolYear','schoolCategory','level','course','semester']);
  
          $schoolYearId = $filters['school_year_id'] ?? false;        
          $query->when($schoolYearId, function($q) use ($schoolYearId) {
              return $q->where('school_year_id', $schoolYearId);
          });
  
          $schoolCategoryId = $filters['school_category_id'] ?? false;        
          $query->when($schoolCategoryId, function($q) use ($schoolCategoryId) {
              return $q->where('level_id', $schoolCategoryId);
          });
  
          $levelId = $filters['level_id'] ?? false;        
          $query->when($levelId, function($q) use ($levelId) {
              return $q->where('level_id', $levelId);
          });
  
          $courseId = $filters['course_id'] ?? false;        
          $query->when($courseId, function($q) use ($courseId) {
              return $q->where('course_id', $courseId);
          });
  
          $semesterId = $filters['semester_id'] ?? false;        
          $query->when($semesterId, function($q) use ($semesterId) {
              return $q->where('semester_id', $semesterId);
          });
  
          $sections = $isPaginated
              ? $query->paginate($perPage)
              : $query->get();
          
          return $sections;
        } catch (Exception $e) {
            Log::info('Error occured during SectionService list method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
            $section = Section::find($id);
            $section->load(['schoolYear','schoolCategory','level','course','semester','schedules']);
            return $section;
        } catch (Exception $e) {
            Log::info('Error occured during SectionService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store(array $data, array $schedules)
    {
        DB::beginTransaction();
        try {
            $section = Section::create($data);
    
            if ($schedules) {
                $section->schedules()->delete();
                foreach ($schedules as $schedule) {
                    $section->schedules()->create($schedule);
                }            
                // $section->schedules()->sync($schedules);
            }
            
            //   $section->load(['department', 'schoolCategory']);
            $section->load(['schoolYear','schoolCategory','level','course','semester']);
            DB::commit();
            return $section;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SectionService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, array $schedules, int $id)
    {
        DB::beginTransaction();
        try {
            $section = Section::find($id);
            $section->update($data);

            if ($schedules) {
                $section->schedules()->delete();
                foreach ($schedules as $schedule) {
                    $section->schedules()->create($schedule);
                }            
                // $section->schedules()->sync($schedules);
            }

            $section->load(['schoolYear','schoolCategory','level','course','semester']);
            DB::commit();
            return $section;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SectionService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id)
    {
        try {
            $section = Section::find($id);
            $section->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SectionService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getSectionsOfSubject(bool $isPaginated, int $perPage, array $filters, int $subjectId) {
        try {

            $query = Section::with(['schoolYear','schoolCategory','level','course','semester']);


            $schoolYearId = $filters['school_year_id'] ?? false;
            $query->when($schoolYearId, function($q) use ($schoolYearId) {
                return $q->where('school_year_id', $schoolYearId);
            });

            $query->whereHas('schedules', function($q) use ($subjectId) {
                return $q->where('subject_id', $subjectId);
            });

            $query->with(['schedules' => function($q) use ($subjectId) {
                $q->where('subject_id', $subjectId);
                return $q->with(['personnel']);
            }]);

            $sections = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();

          return $sections;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SectionService get sections of subject method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}
