<?php

namespace App\Http\Controllers;

use App\Student;
use App\Admission;
use App\Application;
use App\AcademicRecord;
use App\Evaluation;
use App\Http\Requests\StudentStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\StudentResource;
use App\Http\Requests\StudentUpdateRequest;
use App\Services\StudentService;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $studentService = new StudentService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $filters = $request->except('per_page', 'paginate');
        $students = $studentService->list($isPaginated, $perPage, $filters);

        return StudentResource::collection(
            $students
        );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudentUpdateRequest $request)
    {
        $studentService = new StudentService();
        $related = ['address', 'family', 'education', 'photo', 'user'];
        $studentInfo = $request->only($related);
        $data = $request->except($related);
        $student = $studentService->store($data, $studentInfo, $related);
        return (new StudentResource($student))
        ->response()
        ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $studentService = new StudentService();
        $student = $studentService->get($id);
        return new StudentResource($student);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(StudentUpdateRequest $request, int $id)
    {
        $related = ['address', 'family', 'education', 'evaluation'];
        $except = ['address', 'family', 'education', 'active_application', 'active_admission', 'academic_record', 'subjects', 'user', 'evaluation'];
        $studentService = new StudentService();
        $studentInfo = $request->only($except);
        $data = $request->except($except);
        $student = $studentService->update($data, $studentInfo, $related, $id);
        return new StudentResource($student);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $studentService = new StudentService();
        $studentService->delete($id);
        return response()->json([], 204);
    }
}
