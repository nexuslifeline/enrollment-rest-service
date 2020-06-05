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
        $perPage = $request->per_page ?? 20;
        $students = !$request->has('paginate') || $request->paginate === 'true'
            ? Student::paginate($perPage)
            : Student::all();

        $students->load(['address', 'family', 'education', 'applications']);

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
        $related = ['address', 'family', 'education'];
        $data = $request->except($related);
        $student = Student::create($data);

        foreach($related as $item) {
            if ($request->has($item)) {
                $student->{$item}()->updateOrCreate(['student_id' => $student->id], $request->{$item});
            }
        }

        $student->load($related);
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
        $student->load(['address', 'family', 'education']);
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
        try {
            $related = ['address', 'family', 'education'];
            $except = ['address', 'family', 'education', 'active_application', 'active_admission', 'transcript', 'subjects'];
            $data = $request->except($except);
            $student->update($data);

            if ($request->has('active_application')) {
                $query = $student->active_application->updateOrCreate(['student_id' => $student->id], $request->active_application);
                
            }
            
            if ($request->has('active_admission')) {
                $query = $student->active_admission->updateOrCreate(['student_id' => $student->id], $request->active_admission);
            }

            if ($request->has('transcript')) {
                $transcript = $query->transcript()->first();
                $query->transcript()->update($request->transcript);
                $transcript->subjects()->sync($request->subjects);
            }

            // if ($request->has('transcript')) {
            //     $transcript = 
            //     // $transcript->update($request->transcript);
            //     // $transcript->subjects()->sync($request->subjects);
            // } 

            foreach($related as $item) {
                if ($request->has($item)) {
                    $query = $student->{$item}()->updateOrCreate(['student_id' => $student->id], $request->{$item});
                    // $student->active_application->update
                }
            }
           
            $student->load(['address', 'family', 'education'])->fresh();
            return new StudentResource($student);
        } catch (Throwable $e) {
            return response()->json([], 400); // Note! add error here
        }
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
}
