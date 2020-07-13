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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    public function show(Evaluation $evaluation)
    {
        $evaluation->load(['subjects']);
        return new EvaluationResource($evaluation);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
