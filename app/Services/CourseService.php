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
    public function list(bool $isPaginated, int $perPage)
    {
        try {
            $courses = $isPaginated
                ? Course::paginate($perPage)
                : Course::all();
            return $courses;
        } catch (Exception $e) {
            Log::info('Error occured during CourseService list method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
            $course = Course::find($id);
            return $course;
        } catch (Exception $e) {
            Log::info('Error occured during CourseService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store(array $data, array $levels)
    {
        DB::beginTransaction();
        try {
            $course = Course::create($data);
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

    public function update(array $data, array $levels, int $id)
    {
        DB::beginTransaction();
        try {
            $course = Course::find($id);
            $course->update($data);
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

    public function delete(int $id)
    {
        try {
            $course = Course::find($id);
            $course->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during CourseService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        } 
    }

    public function getCoursesOfLevel(int $levelId, bool $paginate, int $perPage, array $filters)
    {
        $query = Level::find($levelId)->courses();

        // filters
        $schoolCategoryId = $filters['school_category_id'] ?? false;
        $query->when($schoolCategoryId, function($q) use ($schoolCategoryId, $levelId) {
            return $q->whereHas('school_categories', function($query) use ($schoolCategoryId, $levelId) {
                return $query->where('school_category_id', $schoolCategoryId)->where('level_id', $levelId);
            });
        });

        $courses = $paginate
            ? $query->paginate($perPage)
            : $query->get();
        return $courses;
    }

    public function getCoursesOfSchoolCategory($schoolCategoryId, bool $paginate, int $perPage)
    {
        $query = SchoolCategory::find($schoolCategoryId)->courses()->distinct('course_id');

        $courses = $paginate
            ? $query->paginate($perPage)
            : $query->get();

        return $courses;
    }
}
