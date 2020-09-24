<?php

namespace App\Services;

use App\Level;
use Exception;
use App\Subject;
use App\Evaluation;
use App\AcademicRecord;
use App\SectionSchedule;
use App\EvaluationSubject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubjectService
{
    public function list(bool $isPaginated, int $perPage, array $filters)
    {
        try {
            $query = Subject::with(['department', 'schoolCategory']);

            //filter by school category
            $schoolCategoryId = $filters['school_category_id'] ?? false;
            $query->when($schoolCategoryId, function($q) use ($schoolCategoryId) {
                return $q->where('school_category_id', $schoolCategoryId);
            });

            $criteria = $filters['criteria'] ?? false;
            $query->when($criteria, function($query) use ($criteria) {
                return $query->where(function($q) use ($criteria) {
                    return $q->where('name', 'like', '%'.$criteria.'%')
                    ->orWhere('description', 'like', '%'.$criteria.'%')
                    ->orWhere('code', 'like', '%'.$criteria.'%');
                });
            });

            $subjects = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();

            return $subjects;
        } catch (Exception $e) {
            Log::info('Error occured during SubjectService list method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
            $subject = Subject::find($id);
            $subject->load(['schoolCategory']);
            return $subject;
        } catch (Exception $e) {
            Log::info('Error occured during SubjectService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $subject = Subject::create($data);
            $subject->load(['schoolCategory']);
            DB::commit();
            return $subject;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SubjectService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $subject = Subject::find($id);
            $subject->update($data);
            $subject->load(['schoolCategory']);
            DB::commit();
            return $subject;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SubjectService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id)
    {
        try {
            $subject = Subject::find($id);
            $subject->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SubjectService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getSubjectsOfLevel(int $levelId, bool $isPaginated, int $perPage, array $filters)
    {
        try{
            $query = Level::find($levelId)->subjects();

            // filters
            $courseId = $filters['course_id'] ?? false;
            $query->when($courseId, function($q) use ($courseId) {
                return $q->where('course_id', $courseId);
            });

            $semesterId = $filters['semester_id'] ?? false;
            $query->when($semesterId, function($q) use ($semesterId) {
                return $q->where('semester_id', $semesterId);
            });

            $subjects = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();

            return $subjects->unique('id');
        } catch (Exception $e) {
            Log::info('Error occured during SubjectService getSubjectsOfLevel method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getSubjectsOfAcademicRecord(int $academicRecordId, bool $isPaginated, int $perPage)
    {
        try {
            $query = AcademicRecord::find($academicRecordId)->subjects();

            $subjects = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();

            $subjects->append('section');
            return $subjects;
        } catch (Exception $e) {
            Log::info('Error occured during SubjectService getSubjectsOfAcademicRecord method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getSubjectsOfAcademicRecordWithSchedules(int $academicRecordId, bool $isPaginated, int $perPage)
    {
        try {
            $query = AcademicRecord::find($academicRecordId)->subjects();

            $subjects = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();

            $subjects->append('section_schedule');
            return $subjects;
        } catch (Exception $e) {
            Log::info('Error occured during SubjectService getSubjectsOfAcademicRecordWithSchedules method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getSubjectsOfEvaluation(int $evaluationId, bool $isPaginated, int $perPage)
    {
        try {
            $evaluation = Evaluation::find($evaluationId);
            $query = $evaluation->subjects()
            ->with(['prerequisites' => function($query) use ($evaluation) {
                return $query->with(['prerequisites' => function ($query) use ($evaluation) {
                    $query->where('curriculum_id', $evaluation->curriculum_id);
                }]);
            }]);

            $subjects = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();

            return $subjects;
        } catch (Exception $e) {
            Log::info('Error occured during SubjectService getSubjectsOfEvaluation method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }


    public function getSectionScheduledSubjects(int $sectionId, bool $isPaginated, int $perPage)
    {
        try {
            $subjectIds = SectionSchedule::select('subject_id')
                ->where('section_id', $sectionId)
                ->get()
                ->pluck('subject_id');

            $query = Subject::with(['schedules' => function($q) use ($sectionId) {
                return $sectionId ? $q->where('section_id', $sectionId)->with(['personnel', 'section']) : $q;
            }])->whereIn('id', $subjectIds);

            $subjects = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();

            return $subjects;
        } catch (Exception $e) {
            Log::info('Error occured during SubjectService getSectionScheduledSubjects method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getSectionUnscheduledSubjects(int $evaluationId, int $studentId, int $curriculumId, bool $isPaginated, int $perPage)
    {
        try {

            if (!$studentId) {
                throw new Exception('Student id not found!');
            }

            if (!$evaluationId) {
                throw new Exception('Evaluation id not found!');
            }

            if (!$curriculumId) {
                throw new Exception('Curriculum id not found!');
            }

            $evaluation = Evaluation::find($evaluationId);

            $query = $evaluation->subjects();

            $subjects = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();

            foreach ($subjects as $subject) {
                $subject->is_allowed = $this->isAllowedToTake(
                    $studentId,
                    $subject->id,
                    $curriculumId
                );
            }

            $subjects->append(['is_allowed']);
            return $subjects;
        } catch (Exception $e) {
            Log::info('Error occured during SubjectService getSectionUnscheduledSubjects method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getSectionScheduledSubjectsWithStatus(int $sectionId, int $studentId, int $curriculumId, bool $isPaginated, int $perPage)
    {
        try {
            if (!$studentId) {
                throw new Exception('Student id not found!');
            }

            if (!$sectionId) {
                throw new Exception('Section id not found!');
            }

            if (!$curriculumId) {
                throw new Exception('Curriculum id not found!');
            }

            $subjectIds = SectionSchedule::select('subject_id')
                ->where('section_id', $sectionId)
                ->get()
                ->pluck('subject_id');

            $query = Subject::with(['schedules' => function($q) use ($sectionId) {
                return $sectionId ? $q->where('section_id', $sectionId)->with(['personnel', 'section']) : $q;
            }])->whereIn('id', $subjectIds);

            $subjects = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();

            foreach ($subjects as $subject) {

                $subject->is_allowed = $this->isAllowedToTake(
                    $studentId,
                    $subject->id,
                    $curriculumId
                );
            }

            $subjects->append(['is_allowed']);
            return $subjects;
        } catch (Exception $e) {
            Log::info('Error occured during SubjectService getSectionScheduledSubjectsWithStatus method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    private function isAllowedToTake(int $studentId, int $subjectId, $curriculumId)
    {

        // - allowed if
        // - subject is not taken yet or subject is taken but with failed grade
        // - and all pre requisite were taken with passed grade
        if (
            !$this->isTaken($studentId, $subjectId) &&
            $this->arePrereqPassed($studentId, $subjectId, $curriculumId)
        ) {
            return true;
        }

        if (
            $this->isTaken($studentId, $subjectId) &&
            !$this->isPassed($studentId, $subjectId) &&
            $this->arePrereqPassed($studentId, $subjectId, $curriculumId)
        ) {
            return true;
        }

        return false;
    }

    private function isTaken(int $studentId, int $subjectId)
    {
        $evaluationIds = Evaluation::where('student_id', $studentId)
            ->get()
            ->pluck('id');

        return EvaluationSubject::whereIn('evaluation_id', $evaluationIds)
            ->where('subject_id', $subjectId)
            ->where('is_taken', 1)
            ->get()
            ->count() > 0;
    }

    // return true if grade is passed, if not taken yet this will return false
    private function isPassed(int $studentId, int $subjectId)
    {
        $evaluationIds = Evaluation::where('student_id', $studentId)
            ->get()
            ->pluck('id');

        return EvaluationSubject::whereIn('evaluation_id', $evaluationIds)
            ->where('subject_id', $subjectId)
            ->where('grade', '>', 74)
            ->get()
            ->count() > 0;
    }

    private function arePrereqPassed(int $studentId, int $subjectId, int $curriculumId)
    {
       $subjects = Subject::with(['prerequisites' => function($query) use ($curriculumId) {
            return $query->where('curriculum_id', $curriculumId);
        }])->find($subjectId);

        $prerequisites = $subjects->prerequisites;

        if (count($prerequisites) === 0) { // if there are no pre requisite, automatically return passed
            return true;
        }

        $passed = true;
        foreach ($prerequisites as $prerequisite) {
            $passed = $passed && $this->isPassed(
                $studentId,
                $prerequisite->id
            );
            if (!$passed) break;
        }

        return $passed;
    }
}
