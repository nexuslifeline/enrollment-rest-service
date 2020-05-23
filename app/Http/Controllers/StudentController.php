<?php

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Http\Request;
use App\Http\Resources\StudentResource;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->perPage ?? 20;
        $students = !$request->has('paginate') || $request->paginate === 'true'
            ? Student::paginate($perPage)
            : Student::all();
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
    public function store(Request $request)
    {
        $data = $request->all();
        $student = Student::create($data);
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
    public function show(Student $student)
    {
        return new StudentResource($student);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        $data = $request->all();
        $success = $student->update($data);
        if ($success) {
            return new StudentResource(
                Student::find($student->id)
            );
        }
        return response()->json([], 400); // Note! add error here
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return response()->json([], 204);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getStudentInfo()
    {
        $student = Student::findOrFail(auth("api")->user()->userable_id);
        $student->load("address");
        $student->load("family");
        $student->load("education");

        return new StudentResource($student);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function updateStudentInfo(Request $request, $child, Student $student)
    {
        $data = $request->all();
        
        if($child == "address")
        {
            //create or update student address
            $success = $student->address()->updateOrCreate(["id" => $request->id], $data);
        }
        else if($child == "family")
        {
            //create or update student family
            $success = $student->family()->updateOrCreate(["id" => $request->id], $data);
        }
        else if($child == "education")
        {
            //create or update student education
            $success = $student->education()->updateOrCreate(["id" => $request->id], $data);
        }
            
        if ($success) {
            return new StudentResource(
                Student::find($student->id)
            );
        }
        
        return response()->json([], 400); // Note! add error here
    }
}
