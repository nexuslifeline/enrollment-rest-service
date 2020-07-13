<?php

namespace App\Http\Controllers;

use App\Student;
use App\Admission;
use App\Application;
use App\Transcript;
use App\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\StudentResource;
use App\Http\Requests\StudentUpdateRequest;
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
        $perPage = $request->per_page ?? 20;
        $query = Student::with(['address', 'family', 'education', 'photo', 'user']);

        $criteria = $request->criteria ?? false;
        $query->when($criteria, function($query) use ($criteria) {
            return $query->where(function($q) use ($criteria) {
                return $q->where('name', 'like', '%'.$criteria.'%')
                    ->orWhere('first_name', 'like', '%'.$criteria.'%')
                    ->orWhere('middle_name', 'like', '%'.$criteria.'%')
                    ->orWhere('last_name', 'like', '%'.$criteria.'%');
                });
        });

        $students = !$request->has('paginate') || $request->paginate === 'true'
            ? $query->paginate($perPage)
            : $query->all();

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
        try{
            
            $related = ['address', 'family', 'education', 'photo', 'user'];
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

        } catch (Throwable $e) {
            Log::info($e->getMessage());
            return response()->json([], 400); // Note! add error here
        }
    }
        

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        $student->load(['address', 'family', 'education', 'photo', 'evaluation']);
        $student->append(['active_admission', 'active_application', 'transcript',]);
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
            $related = ['address', 'family', 'education', 'evaluation'];
            $except = ['address', 'family', 'education', 'active_application', 'active_admission', 'transcript', 'subjects', 'user', 'evaluation'];
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
                    if ($request->has('subjects') && $request->subjects) {
                        $subjects = $request->subjects;
                        $transcript->subjects()->sync($subjects);
                    }
                }
            }

            foreach($related as $item) {
                if ($request->has($item)) {
                    $query = $student->{$item}()->updateOrCreate(['student_id' => $student->id], $request->{$item});
                    // $student->active_application->update
                }
            }
            
            if ($request->has('user')) {
                $user = $student->user()->updateOrCreate(
                    [   
                        'userable_id' => $student->id
                    ],
                    [ 
                        'username' => $request->user['username'],
                        'password' => Hash::make($request->user['password'])
                    ]
                );
            }

            $student->load(['address', 'family', 'education','photo', 'user', 'evaluation'])->fresh();
            $student->append(['active_admission', 'active_application', 'transcript']);

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
        $student->user()->delete();
        return response()->json([], 204);
    }
}
