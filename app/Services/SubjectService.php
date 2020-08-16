<?php

namespace App\Services;

use App\Subject;
use App\Level;
use App\Transcript;
use App\Evaluation;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubjectService
{
    public function index(object $data)
    {
        try {
            $perPage = $data->per_page ?? 20;
            $query = Subject::with(['department', 'schoolCategory']);

            //filter by school category
            $schoolCategoryId = $data->school_category_id ?? false;
            $query->when($schoolCategoryId, function($q) use ($schoolCategoryId) {
                return $q->where('school_category_id', $schoolCategoryId);
            });

            $subjects = !$data->has('paginate') || $data->paginate === 'true'
                ? $query->paginate($perPage)
                : $query->get();

            return $subjects;
        } catch (Exception $e) {
            Log::info('Error occured during SubjectService index method call: ');
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

    public function update(array $data, Subject $subject)
    {
        DB::beginTransaction();
        try {
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

    public function delete(Subject $subject)
    {
        try {
            $subject->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SubjectService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        } 
    }

    public function getSubjectsOfLevel($levelId, object $data)
    {
        $perPage = $data->per_page ?? 20;
        $query = Level::find($levelId)->subjects();

        // filters
        $courseId = $data->course_id ?? false;
        $query->when($courseId, function($q) use ($courseId) {
            return $q->where('course_id', $courseId);
        });

        $semesterId = $data->semester_id ?? false;
        $query->when($semesterId, function($q) use ($semesterId) {
            return $q->where('semester_id', $semesterId);
        });

        $subjects = !$data->has('paginate') || $data->paginate === 'true'
            ? $query->paginate($perPage)
            : $query->get();
        
        return $subjects->unique('id');
    }

    public function getSubjectsOfTranscript($transcriptId, object $data)
    {
        $perPage = $data->per_page ?? 20;
        $query = Transcript::find($transcriptId)->subjects();

        $subjects = !$data->has('paginate') || $data->paginate === 'true'
            ? $query->paginate($perPage)
            : $query->get();

        return $subjects;
    }

    public function getSubjectsOfEvaluation($evaluationId, object $data)
    {
        $perPage = $data->per_page ?? 20;
        $evaluation = Evaluation::find($evaluationId);
        $query = $evaluation->subjects()
        ->with(['prerequisites' => function($query) use ($evaluation) {
            return $query->with(['prerequisites' => function ($query) use ($evaluation) {
                $query->where('curriculum_id', $evaluation->curriculum_id);
            }]);
        }]);

        $subjects = !$data->has('paginate') || $data->paginate === 'true'
            ? $query->paginate($perPage)
            : $query->get();
        
        return $subjects;
    }
}
