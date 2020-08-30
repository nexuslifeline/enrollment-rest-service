<?php

namespace App\Services;

use App\Level;
use Exception;
use App\Subject;
use App\Evaluation;
use App\Transcript;
use App\SectionSchedule;
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

    public function getSubjectsOfLevel($levelId, bool $isPaginated, int $perPage, array $filters)
    {
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
    }

    public function getSubjectsOfTranscript($transcriptId, bool $isPaginated, int $perPage)
    {
        $query = Transcript::find($transcriptId)->subjects();

        $subjects = $isPaginated
            ? $query->paginate($perPage)
            : $query->get();

        return $subjects;
    }

    public function getSubjectsOfEvaluation($evaluationId, bool $isPaginated, int $perPage)
    {
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
    }


    public function getSectionScheduledSubjects($sectionId, bool $isPaginated, int $perPage)
    {
        $subjectIds = SectionSchedule::select('subject_id')
            ->where('section_id', $sectionId)
            ->get()
            ->pluck('subject_id');

        $query = Subject::with(['schedules' => function($q) use ($sectionId) {
            return $sectionId ? $q->where('section_id', $sectionId) : $q;
        }, 'schedules.personnel'])->whereIn('id', $subjectIds);

        $subjects = $isPaginated
            ? $query->paginate($perPage)
            : $query->get();

        return $subjects;
    }
}
