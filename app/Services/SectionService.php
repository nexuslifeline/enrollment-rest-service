<?php

namespace App\Services;

use App\Section;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SectionService
{
    public function index(object $request)
    {
        try {
          $perPage = $request->per_page ?? 20;
          $query = Section::with(['schoolYear','schoolCategory','level','course','semester']);
  
          $schoolYearId = $request->school_year_id ?? false;        
          $query->when($schoolYearId, function($q) use ($schoolYearId) {
              return $q->where('school_year_id', $schoolYearId);
          });
  
          $schoolCategoryId = $request->school_category_id ?? false;        
          $query->when($schoolCategoryId, function($q) use ($schoolCategoryId) {
              return $q->where('level_id', $schoolCategoryId);
          });
  
          $levelId = $request->level_id ?? false;        
          $query->when($levelId, function($q) use ($levelId) {
              return $q->where('level_id', $levelId);
          });
  
          $courseId = $request->course_id ?? false;        
          $query->when($courseId, function($q) use ($courseId) {
              return $q->where('course_id', $courseId);
          });
  
          $semesterId = $request->semester_id ?? false;        
          $query->when($semesterId, function($q) use ($semesterId) {
              return $q->where('semester_id', $semesterId);
          });
  
          $sections = !$request->has('paginate') || $request->paginate === 'true'
              ? $query->paginate($perPage)
              : $query->get();
          
          return $sections;
        } catch (Exception $e) {
            Log::info('Error occured during SectionService index method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store(object $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('schedules');
      
            $section = Section::create($data);
    
            if ($request->has('schedules')) {
                $schedules = $request->schedules;
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

    public function update(object $request, Section $section)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('schedules');
          
            $section->update($data);

            if ($request->has('schedules')) {
                $schedules = $request->schedules;
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

    public function delete(Section $section)
    {
        try {
            $section->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SectionService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        } 
    }
}
