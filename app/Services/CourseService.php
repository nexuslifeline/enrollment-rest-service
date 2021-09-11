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
    public function list(bool $isPaginated, int $perPage, array $filters)
    {
        try {
            $levelId = $filters['level_id'] ?? null;
            $schoolCategoryId = $filters['school_category_id'] ?? null;

            $query = Course::when($levelId, function($q) use ($levelId) {
                return $q->whereHas('levels', function($q) use ($levelId) {
                    return $q->where('level_id', $levelId);
                });
            });

            $query->when($schoolCategoryId, function ($q) use ($schoolCategoryId) {
                return $q->whereHas('schoolCategories', function ($q) use ($schoolCategoryId) {
                    return $q->where('school_category_id', $schoolCategoryId);
                });
            });

            $criteria = $filters['criteria'] ?? false;
            $query->when($criteria, function ($query) use ($criteria) {
                $query->whereLike($criteria);
            });

            // order by
            $orderBy = 'id';
            $sort = 'DESC';

            $ordering = $filters['ordering'] ?? false;
            if ($ordering) {
                $isDesc = str_starts_with(
                    $ordering,
                    '-'
                );
                $orderBy = $isDesc ? substr($ordering, 1) : $ordering;
                $sort = $isDesc ? 'DESC' : 'ASC';
            }
            $query->orderBy($orderBy, $sort);

            $courses = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();
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
        DB::beginTransaction();
        try {
            $course = Course::find($id);
            $course->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during CourseService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getCoursesOfLevel(int $levelId, bool $paginate, int $perPage, array $filters)
    {
        try {
            $query = Level::find($levelId)->courses();

            // filters
            $schoolCategoryId = $filters['school_category_id'] ?? false;
            $query->when($schoolCategoryId, function($q) use ($schoolCategoryId, $levelId) {
                return $q->whereHas('schoolCategories', function($query) use ($schoolCategoryId, $levelId) {
                    return $query->where('school_category_id', $schoolCategoryId)->where('level_id', $levelId);
                });
            });

            $courses = $paginate
                ? $query->paginate($perPage)
                : $query->get();
            return $courses;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during CourseService getCoursesOfLevel method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getCoursesOfSchoolCategory($schoolCategoryId, bool $paginate, int $perPage)
    {
        try {
            $query = SchoolCategory::find($schoolCategoryId)->courses()->distinct('course_id');

            $courses = $paginate
                ? $query->paginate($perPage)
                : $query->get();

            return $courses;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during CourseService getCoursesOfSchoolCategory method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}
