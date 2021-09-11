<?php

namespace App\Services;

use App\Curriculum;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CurriculumService
{
    public function list(bool $isPaginated, int $perPage, array $filters)
    {
        try {
            $query = Curriculum::with(['schoolCategories', 'course', 'level']);

            // filters
            $schoolCategoryId = $filters['school_category_id'] ?? false;
            $query->when($schoolCategoryId, function($q) use ($schoolCategoryId) {
                return $q->whereHas('schoolCategories', function ($q) use ($schoolCategoryId) {
                    return $q->where('school_category_id', $schoolCategoryId);
                });
            })->select('curriculums.*');

            $courseId = $filters['course_id'] ?? false;
            $query->when($courseId, function($q) use ($courseId) {
                return $q->where('course_id', $courseId);
            });

            // $levelId = $filters['level_id'] ?? false;
            // $query->when($levelId && !$courseId, function($q) use ($levelId) {
            //     return $q->where('level_id', $levelId);
            // });

            $active = $filters['active'] ?? false;
            $query->when($active, function($q) use ($active) {
                return $q->where('active', $active);
            });

            $subjects = $filters['subjects'] ?? false;
            $query->when($subjects, function($q) use ($filters) {
                return $q->with(['subjects' => function ($query) use ($filters) {
                    $semesterId = $filters['semester_id'] ?? false;
                    $query->when($semesterId, function($q) use ($semesterId) {
                        return $q->where('semester_id', $semesterId);
                    });
                    $levelId = $filters['level_id'] ?? false;
                    $query->when($levelId, function($q) use ($levelId) {
                        return $q->where('level_id', $levelId);
                    });
                }]);
            });

            $criteria = $filters['criteria'] ?? false;
            $query->when($criteria, function ($query) use ($criteria) {
                $query->whereLike($criteria);
            });

            $query->schoolCategoryFilter();

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

            $schoolCategoryFields = ['school_category_name'];
            $courseFields = ['course_name'];
            // if (in_array($orderBy, $schoolCategoryFields)) {
            //     $query->orderBySchoolCategory($orderBy, $sort);
            // } else 
            if (in_array($orderBy, $courseFields)) {
                $query->orderByCourse($orderBy, $sort);
            } else {
                $query->orderBy($orderBy, $sort);
            }


            $curriculums = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();
            return $curriculums;
        } catch (Exception $e) {
            Log::info('Error occured during CurriculumService list method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function get($id)
    {
        try {
            $curriculum = Curriculum::find($id);
            $curriculum->load(['schoolCategories', 'course', 'level', 'subjects' => function($query) use ($id) {
                return $query->with(['prerequisites' => function ($query) use ($id) {
                    $query->where('curriculum_id', $id);
                }]);
            }]);
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during CurriculumService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }

        return $curriculum;
    }

    public function store(array $data, array $subjects, array $schoolCategories, array $prerequisites)
    {
        // return $data;
        DB::beginTransaction();
        try {
            $curriculum = Curriculum::create($data);
            $curriculum->schoolCategories()->sync($schoolCategories);
            $items = [];
            // if ($subjects) {
            foreach ($subjects as $subject) {
                $items[$subject['subject_id']] = [
                    'course_id' => $data['course_id'],
                    'school_category_id' => $subject['school_category_id'],
                    'level_id' => $subject['level_id'],
                    'semester_id' => $subject['semester_id']
                ];

                // if ($prerequisites) {
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
                // }
            // }
            }
            $curriculum->subjects()->sync($items);

            if ($data['active']) {
              $curriculums = Curriculum::whereHas('schoolCategories', function ($q) use($schoolCategories) {
                return $q->whereIn('school_category_id', $schoolCategories);
              })
              ->where('course_id', $data['course_id'])
              ->where('id', '!=', $curriculum['id']);
              $curriculums->update([
                'active' => 0
              ]);
            }

            $curriculum->load(['schoolCategories', 'course', 'level']);
            DB::commit();
            return $curriculum;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during CurriculumService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, array $subjects, array $schoolCategories, array $prerequisites, int $id)
    {
        DB::beginTransaction();
        try {
            $curriculum = Curriculum::find($id);
            $curriculum->update($data);
            $curriculum->schoolCategories()->sync($schoolCategories);
            $items = [];
            // if (count($subjects) > 0) {
            foreach ($subjects as $subject) {
                $items[$subject['subject_id']] = [
                    'course_id' => $data['course_id'],
                    'school_category_id' => $subject['school_category_id'],
                    'level_id' => $subject['level_id'],
                    'semester_id' => $subject['semester_id']
                ];

                // if ($prerequisites) {
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
                // }
            // }
            }
            $curriculum->subjects()->sync($items);

            if ($data['active']) {
                $curriculums = Curriculum::whereHas('schoolCategories', function ($q) use ($schoolCategories) {
                    return $q->whereIn('school_category_id', $schoolCategories);
                })
                ->where('course_id', $data['course_id'])
                ->where('id', '!=', $id);
                $curriculums->update([
                    'active' => 0
                ]);
            }
            $curriculum->load(['schoolCategories', 'course', 'level']);
            DB::commit();
            return $curriculum;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during CurriculumService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id)
    {
        DB::beginTransaction();
        try {
            $curriculum = Curriculum::find($id);
            $curriculum->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during CurriculumService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}
