<?php

namespace App\Http\Controllers;

use App\Student;
use App\Admission;
use App\Application;
use App\Transcript;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\StudentResource;
use App\Http\Requests\StudentUpdateRequest;

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
        
        $students->load(['address', 'family', 'education']);

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
    public function update(StudentUpdateRequest $request, Student $student)
    {

        try {
            $related = ['address', 'family', 'education'];
            $except = ['address', 'family', 'education', 'active_application', 'active_admission', 'transcript', 'subjects'];
            $data = $request->except($except);
            $student->update($data);

            if ($request->has('active_application') && count($request->active_application) > 0) {
                $application = Application::find($request->active_application['id']);
                if ($application) {
                    $application->update($request->active_application);
                }
            }

            if ($request->has('active_admission') && count($request->active_admission) > 0) {
                $admission = Admission::find($request->active_admission['id']);
                if ($admission) {
                    $admission->update($request->active_admission);
                }
            }

            if ($request->has('transcript')) {
                $transcript = Transcript::find($request->transcript['id']);
                if ($transcript) {
                    $transcript->update($request->transcript);
                    if($request->has('subjects')) {
                        $subjects = $request->subjects;
                        $items = [];
                        foreach ($subjects as $subject) {
                          $items[$subject['id']] = [];
                        }  
                      $transcript->subjects()->sync($items);
                    }
                }
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
            Log::info($e->getMessage());
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
