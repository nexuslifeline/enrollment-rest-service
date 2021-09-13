<?php

namespace App\Services;

use App\Section;
use Exception;
use Illuminate\Support\Facades\Auth;
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
              return $q->where('school_category_id', $schoolCategoryId);
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

          $schoolYearId = $filters['school_year_id'] ?? false;
          $query->when($schoolYearId, function($q) use ($schoolYearId) {
              return $q->where('school_year_id', $schoolYearId);
          });

          //criteria
          $criteria = $filters['criteria'] ?? false;
          $query->when($criteria, function($q) use ($criteria) {
            return $q->where(function($q) use ($criteria) {
                return $q->where('sections.name', 'like', '%'.$criteria.'%')
                    ->orWhere('sections.description', 'like', '%'.$criteria.'%')
                    ->orWhereHas('schoolYear', function($query) use ($criteria) {
                        return $query->where('name', 'like', '%'.$criteria.'%');
                    })
                    ->orWhereHas('schoolCategory', function($query) use ($criteria) {
                        return $query->where('name', 'like', '%'.$criteria.'%');
                    })
                    ->orWhereHas('level', function($query) use ($criteria) {
                        return $query->where('name', 'like', '%'.$criteria.'%');
                    })
                    ->orWhereHas('course', function($query) use ($criteria) {
                        return $query->where('name', 'like', '%'.$criteria.'%');
                    })
                    ->orWhereHas('semester', function($query) use ($criteria) {
                        return $query->where('name', 'like', '%'.$criteria.'%');
                    });
                });
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
            $section->load(['schoolYear','schoolCategory','level','course','semester','schedules' => function($query) {
                $query->with(['subject', 'personnel']);
            }]);
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

            //if ($schedules) {
                $section->schedules()->delete();
                foreach ($schedules as $schedule) {
                    $section->schedules()->create($schedule);
                }
                // $section->schedules()->sync($schedules);
            //}

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

            //if ($schedules) {
                $section->schedules()->delete();
                foreach ($schedules as $schedule) {
                    $section->schedules()->create($schedule);
                }
                // $section->schedules()->sync($schedules);
            //}
                // $section->schedules()->sync($schedules);
            // }

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

    public function getSectionsOfPersonnel(array $filters)
    {
        try {
            $personnelId = Auth::user()->userable->id;
            $query = Section::with('level','course','semester')
            ->whereHas('schedules', function ($q) use ($personnelId) {
                return $q->where('personnel_id', $personnelId);
            });

            $schoolCategoryId = $filters['school_category_id'] ?? false;
            $query->when($schoolCategoryId, function($q) use ($schoolCategoryId) { 
                return $q->where('school_category_id', $schoolCategoryId);
            });

            $schoolYearId = $filters['school_year_id'] ?? false;
            $query->when($schoolYearId, function($q) use ($schoolYearId) { 
                return $q->where('school_year_id', $schoolYearId);
            });

            $semesterId = $filters['semester_id'] ?? false;
            $query->when($semesterId, function ($q) use ($semesterId) {
                return $q->where('semester_id', $semesterId);
            });

            $sections = $query->get();
            
            $sections->append('subjects');
            return $sections;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SectionService getSectionsOfPersonnel method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}
