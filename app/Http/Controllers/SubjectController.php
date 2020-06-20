<?php

namespace App\Http\Controllers;

use App\Level;
use App\Subject;
use App\Transcript;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\SubjectResource;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 20;
        $query = Subject::with(['department', 'schoolCategory']);

        $schoolCategoryId = $request->school_category_id ?? false;
        $query->when($schoolCategoryId, function($q) use ($schoolCategoryId) {
            return $q->where('school_category_id', $schoolCategoryId);
        });


        $subjects = !$request->has('paginate') || $request->paginate === 'true'
            ? $query->paginate($perPage)
            : $query->get();        

        return SubjectResource::collection(
            $subjects
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
        $this->validate($request, [
          'code' => 'required|max:191',
          'name' => 'required|max:191',
          'description' => 'required|max:191',
          'school_category_id' => 'required',
          'department_id' => 'required_if:school_category_id,4,5,6'
        ], ['required_if' => 'The :attribute field is required.'], [
          'school_category_id' => 'school category',
          'department_id' => 'department'
        ]);

        $data = $request->all();

        $subject = Subject::create($data);
        
        $subject->load(['department', 'schoolCategory']);
        return (new SubjectResource($subject))
            ->response()
            ->setStatusCode(201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        $subject->load(['department', 'schoolCategory']);
        return new SubjectResource($subject);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subject $subject)
    {
        $this->validate($request, [
          'code' => 'required|max:191',
          'name' => 'required|max:191',
          'description' => 'required|max:191',
          'school_category_id' => 'required',
          'department_id' => 'required'
        ], [], [
          'school_category_id' => 'school category',
          'department_id' => 'department'
        ]);

        $data = $request->all();

        $success = $subject->update($data);

        if($success){
            $subject->load(['department', 'schoolCategory']);
            return (new SubjectResource($subject))
            ->response()
            ->setStatusCode(200);
        }
        return response()->json([], 400); // Note! add error here
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();
        return response()->json([], 204);
    }

    public function getSubjectsOfLevel($levelId, Request $request)
    {

        $perPage = $request->perPage ?? 20;
        $query = Level::find($levelId)->subjects();

        // filters
        $courseId = $request->course_id ?? false;
        $query->when($courseId, function($q) use ($courseId, $levelId) {
            return $q->whereHas('courses', function($query) use ($courseId, $levelId) {
                return $query->where('course_id', $courseId)->where('level_id', $levelId);
            });
        });

        $semesterId = $request->semester_id ?? false;
        $query->when($semesterId, function($q) use ($semesterId, $levelId) {
            return $q->whereHas('semesters', function($query) use ($semesterId, $levelId) {
                return $query->where('semester_id', $semesterId)->where('level_id', $levelId);
            });
        });

        $subjects = !$request->has('paginate') || $request->paginate === 'true'
            ? $query->paginate($perPage)
            : $query->get();
        return SubjectResource::collection($subjects);
    }

    public function getSubjectsOfTranscript($transcriptId, Request $request)
    {

        $perPage = $request->perPage ?? 20;
        $query = Transcript::find($transcriptId)->subjects();

        $subjects = !$request->has('paginate') || $request->paginate === 'true'
            ? $query->paginate($perPage)
            : $query->get();
        return SubjectResource::collection($subjects);
    }

    public function storeSubjectsOfLevel($levelId, Request $request)
    {
        $subjects = $request->subjects;
        $items = [];
        foreach ($subjects as $subject) {
            $items[$subject['subject_id']] = [
                'course_id' => $subject['course_id'],
                'semester_id' => $subject['semester_id'],
                'school_category_id' => $subject['school_category_id']
            ];
        }
        // return $items;
        $data = Level::find($levelId)->subjects();
        $data->sync($items);
        return SubjectResource::collection($data->get());
    }
}
