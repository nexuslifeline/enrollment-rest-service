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
        $related = ['address', 'family', 'education', 'applications'];
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
        $schoolYearId = 1;
        $student->load(['address', 'family', 'education'])->load(['transcripts' => function ($query) use($schoolYearId){
            $query->with('application')->where('school_year_id', $schoolYearId)->first();
        }]);

        // return $student->load(['address', 'family', 'education'])->transcripts()->first()->application()->get();

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
            $related = ['address', 'family', 'education', 'applications'];
            $except = ['address', 'family', 'education', 'applications', 'transcript', 'subjects'];
            $data = $request->except($except);
            $student->update($data);
            foreach($related as $item) {
              if ($request->has($item)) {
                if($item === 'applications') {
                  $application = $student->applications()->create($request->applications);
                  $application->transcript()->create($request->transcript)->subjects()->attach($request->subjects);
                } else {
                  $student->{$item}()->updateOrCreate(['student_id' => $student->id], $request->{$item});
                }         
              }
            }
            $student->load($related)->fresh();
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
