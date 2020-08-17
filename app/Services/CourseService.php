<?php

namespace App\Services;

use App\Course;
use App\Level;
use App\SchoolCategory;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CourseService
{
    public function index(object $request)
    {
        try {
            $perPage = $request->per_page ?? 20;
            $courses = !$request->has('paginate') || $request->paginate === 'true'
                ? Course::paginate($perPage)
                : Course::all();
            return $courses;
        } catch (Exception $e) {
            Log::info('Error occured during CourseService index method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store(object $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('levels');
    
            $course = Course::create($data);
            $levels = $request->levels;
            $items = [];
            foreach ($levels as $level) {
                $items[$level['level_id']] = [
                    'school_category_id' => $level['school_category_id']
                ];
            }
    
            $course->levels()->sync($items);
            DB::commit();
            return $course;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during CourseService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(object $request, Course $course)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('levels');
    
            $course->update($data);
    
            $levels = $request->levels;
            $items = [];
            foreach ($levels as $level) {
                $items[$level['level_id']] = [
                    'school_category_id' => $level['school_category_id']
                ];
            }
    
            $course->levels()->sync($items);
            DB::commit();
            return $course;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during CourseService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete(Course $course)
    {
        try {
            $course->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during CourseService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        } 
    }

    public function getCoursesOfLevel($levelId, object $request)
    {
        $perPage = $request->per_page ?? 20;
        $query = Level::find($levelId)->courses();

        // filters
        $schoolCategoryId = $request->school_category_id ?? false;
        $query->when($schoolCategoryId, function($q) use ($schoolCategoryId, $levelId) {
            return $q->whereHas('school_categories', function($query) use ($schoolCategoryId, $levelId) {
                return $query->where('school_category_id', $schoolCategoryId)->where('level_id', $levelId);
            });
        });

        $courses = !$request->has('paginate') || $request->paginate === 'true'
            ? $query->paginate($perPage)
            : $query->get();
        return $courses;
    }

    public function getCoursesOfSchoolCategory($schoolCategoryId, object $request)
    {
        $perPage = $request->per_page ?? 20;
        $query = SchoolCategory::find($schoolCategoryId)->courses()->distinct('course_id');

        $courses = !$request->has('paginate') || $request->paginate === 'true'
            ? $query->paginate($perPage)
            : $query->get();

        return $courses;
    }
}
