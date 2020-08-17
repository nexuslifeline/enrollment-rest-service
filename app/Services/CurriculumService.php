<?php

namespace App\Services;

use App\Curriculum;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CurriculumService
{
    public function index(object $request)
    {
        try {
            $perPage = $request->per_page ?? 20;
            $query = Curriculum::with(['schoolCategory', 'course', 'level']);
    
            // filters
            $schoolCategoryId = $request->school_category_id ?? false;
            $query->when($schoolCategoryId, function($q) use ($schoolCategoryId) {
                return $q->where('school_category_id', $schoolCategoryId);
            });
    
            $courseId = $request->course_id ?? false;
            $query->when($courseId, function($q) use ($courseId) {
                return $q->where('course_id', $courseId);
            });
    
            $levelId = $request->level_id ?? false;
            $query->when($levelId && !$courseId, function($q) use ($levelId) {
                return $q->where('level_id', $levelId);
            });
    
            $active = $request->active ?? false;
            $query->when($active, function($q) use ($active) {
                return $q->where('active', $active);
            });
    
            $subjects = $request->subjects ?? false;
            $query->when($subjects, function($q) use ($request) {
                return $q->with(['subjects' => function ($query) use ($request) {
                    $semesterId = $request->semester_id ?? false;
                    $query->when($semesterId, function($q) use ($semesterId) {
                        return $q->where('semester_id', $semesterId);
                    });
                    $levelId = $request->level_id ?? false;
                    $query->when($levelId, function($q) use ($levelId) {
                        return $q->where('level_id', $levelId);
                    });
                }]);
            });
    
            $curriculums = !$request->has('paginate') || $request->paginate === 'true'
                ? $query->paginate($perPage)
                : $query->get();
            return $curriculums;
        } catch (Exception $e) {
            Log::info('Error occured during CurriculumService index method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store(object $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('subjects', 'prerequisites');

            $curriculum = Curriculum::create($data);
            
            if ($request->has('subjects')) {
                $subjects = $request->subjects;
                $items = [];
                foreach ($subjects as $subject) {
                    $items[$subject['subject_id']] = [
                        'course_id' => $request->course_id,
                        'school_category_id' => $request->school_category_id,
                        'level_id' => $subject['level_id'],
                        'semester_id' => $subject['semester_id']
                    ];
    
                    if ($request->has('prerequisites')) {
                        $prerequisites = $request->prerequisites;
                        $prerequisiteItems = [];
                        foreach ($prerequisites as $prerequisite) {
                            if ($subject['subject_id'] === $prerequisite['subject_id']) {
                                $prerequisiteItems[$prerequisite['prerequisite_subject_id']] = [
                                    'subject_id' => $prerequisite['subject_id'],
                                ];
                            }
                        }
                        $curriculum->prerequisites()
                        ->wherePivot('subject_id', $subject['subject_id'])
                        ->sync($prerequisiteItems);
                    }
                }
                $curriculum->subjects()->sync($items);
            }
    
            if ($request->active) {
              $curriculums = Curriculum::where('school_category_id', $request->school_category_id)
              ->where('course_id', $request->course_id)
              ->where('level_id', $request->level_id)
              ->where('id', '!=', $curriculum->id);
              $curriculums->update([
                'active' => 0
              ]);
            }   
    
            $curriculum->load(['schoolCategory', 'course', 'level']);
            DB::commit();
            return $curriculum;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during CurriculumService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(object $request, Curriculum $curriculum)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('subjects', 'prerequisites');

            $curriculum->update($data);
    
            if ($request->has('subjects')) {
                $subjects = $request->subjects;
                $items = [];
                foreach ($subjects as $subject) {
                    $items[$subject['subject_id']] = [
                        'course_id' => $request->course_id,
                        'school_category_id' => $request->school_category_id,
                        'level_id' => $subject['level_id'],
                        'semester_id' => $subject['semester_id']
                    ];
    
                    if ($request->has('prerequisites')) {
                        $prerequisites = $request->prerequisites;
                        $prerequisiteItems = [];
                        foreach ($prerequisites as $prerequisite) {
                            if ($subject['subject_id'] === $prerequisite['subject_id']) {
                                $prerequisiteItems[$prerequisite['prerequisite_subject_id']] = [
                                    'subject_id' => $prerequisite['subject_id'],
                                ];
                            }
                        }
                        $curriculum->prerequisites()
                        ->wherePivot('subject_id', $subject['subject_id'])
                        ->sync($prerequisiteItems);
                    }
                }
                $curriculum->subjects()->sync($items);
            }
            
            if ($request->active) {
              $curriculums = Curriculum::where('school_category_id', $request->school_category_id)
              ->where('course_id', $request->course_id)
              ->where('level_id', $request->level_id)
              ->where('id', '!=', $curriculum->id);
              $curriculums->update([
                'active' => 0
              ]);
            }   
            $curriculum->load(['schoolCategory', 'course', 'level']);
            DB::commit();
            return $curriculum;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during CurriculumService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete(Curriculum $curriculum)
    {
        try {
            $curriculum->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during CurriculumService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        } 
    }
}
