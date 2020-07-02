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
        $query = Subject::with(['department', 'schoolCategory', 'prerequisites']);

        //filter by school category
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
          // 'code' => 'required|max:191',
          'name' => 'required|max:191',
          'description' => 'required|max:191',
          'school_category_id' => 'required',
          'department_id' => 'required'
        ], ['required_if' => 'The :attribute field is required.'], [
          'school_category_id' => 'school category',
          'department_id' => 'department'
        ]);

        $data = $request->except('prerequisites');

        $subject = Subject::create($data);
        
        if ($request->has('prerequisites')) {
            $subject->prerequisites()->sync($request->prerequisites);
        }
        
        $subject->load(['department', 'schoolCategory', 'prerequisites']);
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
          // 'code' => 'required|max:191',
          'name' => 'required|max:191',
          'description' => 'required|max:191',
          'school_category_id' => 'required',
          'department_id' => 'required'
        ], ['required_if' => 'The :attribute field is required.'], [
          'school_category_id' => 'school category',
          'department_id' => 'department'
        ]);

        $data = $request->except('prerequisites');

        $success = $subject->update($data);
        
        if ($request->has('prerequisites')) {
            $subject->prerequisites()->sync($request->prerequisites);
        }

        if($success){
            $subject->load(['department', 'schoolCategory', 'prerequisites']);
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
        $query->when($courseId, function($q) use ($courseId) {
            return $q->where('course_id', $courseId);
        });

        $semesterId = $request->semester_id ?? false;
        $query->when($semesterId, function($q) use ($semesterId) {
            return $q->where('semester_id', $semesterId);
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
        // return $request;
        $this->validate($request, [
          'subjects' => 'array|min:1',
        ]);

        $subjects = $request->subjects;
        $items = [];

        foreach ($subjects as $subject) {
            $items[$subject['subject_id']] = [
                'course_id' => $request->course_id,
                'semester_id' => $request->semester_id,
                'school_category_id' => $request->school_category_id
            ];
        }

        $data = Level::find($levelId)->subjects();
        if ($request->course_id) {
            $data->wherePivot('course_id', $request->course_id);
        }
        if ($request->semester_id) {
            $data->wherePivot('semester_id', $request->semester_id);
        }  
                  
        $data->sync($items);
        return SubjectResource::collection($data->get());
    }
}
