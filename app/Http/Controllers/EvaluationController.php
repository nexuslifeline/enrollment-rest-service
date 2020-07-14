<?php

namespace App\Http\Controllers;

use App\Evaluation;
use App\Http\Resources\EvaluationResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 20;
        $query = Evaluation::with([
            'level', 
            'course', 
            'studentCategory',
            'curriculum',
            'student' => function($query) {
                $query->with(['address', 'photo']);
            }])
            ->where('evaluation_status_id', '!=', 1);

        // filters
        // student
        $studentId = $request->student_id ?? false;
        $query->when($studentId, function($q) use ($studentId) {
            return $q->whereHas('student', function($query) use ($studentId) {
                return $query->where('student_id', $studentId);
            });
        });

        //school category
        $schoolCategoryId = $request->school_category_id ?? false;
        $query->when($schoolCategoryId, function($q) use ($schoolCategoryId) {
            return $q->where('school_category_id', $schoolCategoryId);
        });

        // course
        $courseId = $request->course_id ?? false;
        $query->when($courseId, function($q) use ($courseId) {
            return $q->whereHas('course', function($query) use ($courseId) {
                return $query->where('course_id', $courseId);
            });
        });
        
        // level
        $levelId = $request->level_id ?? false;
        $query->when($levelId, function($q) use ($levelId) {
            return $q->whereHas('level', function($query) use ($levelId) {
                return $query->where('level_id', $levelId);
            });
        });

        // evaluation status
        $evaluationStatusId = $request->evaluation_status_id ?? false;
        $query->when($evaluationStatusId, function($query) use ($evaluationStatusId) {
            return $query->where('evaluation_status_id', $evaluationStatusId);
        });

        // filter by student name
        $criteria = $request->criteria ?? false;
        $query->when($criteria, function($q) use ($criteria) {
            return $q->whereHas('student', function($query) use ($criteria) {
                return $query->where(function($q) use ($criteria) {
                    return $q->where('name', 'like', '%'.$criteria.'%')
                        ->orWhere('first_name', 'like', '%'.$criteria.'%')
                        ->orWhere('middle_name', 'like', '%'.$criteria.'%')
                        ->orWhere('last_name', 'like', '%'.$criteria.'%');
                });
            });
        });
        
        $evaluations = !$request->has('paginate') || $request->paginate === 'true'
            ? $query->paginate($perPage)
            : $query->get();
          
        // $registrar = $request->registrar ?? false;
        // $students->when($registrar, function($students) {
        //     return $students->append(['active_admission', 'active_application', 'transcript']);
        // });

        return EvaluationResource::collection(
            $evaluations
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Evaluation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Evaluation $evaluation)
    {
        try {
            $except = ['subjects'];
            $data = $request->except($except);
            $evaluation->update($data);

            if ($request->has('subjects')) {
                $subjects = $request->subjects;
                $items = [];
                foreach ($subjects as $subject) {
                    $items[$subject['subject_id']] = [
                        'level_id' => $subject['level_id'],
                        'semester_id' => $subject['semester_id']
                    ];
                }
                $evaluation->subjects()->sync($items);
            }

            $evaluation->load([
                'level', 
                'course', 
                'studentCategory',
                'student' => function($query) {
                    $query->with(['address', 'photo']);
            }])->fresh();

            return new EvaluationResource($evaluation);
        } catch (Throwable $e) {
            Log::info($e->getMessage());
            return response()->json([], 400); // Note! add error here
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
