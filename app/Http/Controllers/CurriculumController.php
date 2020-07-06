<?php

namespace App\Http\Controllers;

use App\Curriculum;
use Illuminate\Http\Request;
use App\Http\Resources\CurriculumResource;

class CurriculumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 20;

        $curriculums = !$request->has('paginate') || $request->paginate === 'true'
            ? Curriculum::paginate($perPage)
            : Curriculum::all();        

        $curriculums->load(['schoolCategory', 'course', 'level']);
          
        return CurriculumResource::collection(
            $curriculums
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
            'name' => 'required|string|max:191',
            'school_category_id' => 'required|numeric',
            'course_id' => 'required_if:school_category_id,4,5,6,7',
            'level_id' => 'required_if:school_category_id,1,2,3,6,7',
            'effective_year' => 'required|numeric'
        ],
        [
            'required_if' => 'The :attribute field is required.'
        ], 
        [
            'school_category_id' => 'school category',
            'course_id' => 'course'
        ]);

        $data = $request->except('subjects');

        $curriculum = Curriculum::create($data);

        if ($request->has('subjects')) {
            $subjects = $request->subjects;
            $items = [];
            foreach ($subjects as $subject) {
                $items[$subject['subject_id']] = [
                    'course_id' => $request->course_id,
                    'school_category_id' => $request->school_category_id,
                    'level_id' => $subject['level_id'],
                    'semester_id' => $subject['semester_id']
                ];
            }
            $curriculum->subjects()->sync($items);
        }

        if ($request->active) {
          $curriculums = Curriculum::where('school_category_id', $request->school_category_id)
          ->where('course_id', $request->course_id)
          ->where('level_id', $request->level_id)
          ->where('id', '!=', $curriculum->id);
          $curriculums->update([
            'active' => 0
          ]);
        }   

        $curriculum->load(['schoolCategory', 'course', 'level']);
        return (new CurriculumResource($curriculum))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Curriculum  $curriculum
     * @return \Illuminate\Http\Response
     */
    public function show(Curriculum $curriculum)
    {
        $curriculum->load(['schoolCategory', 'course', 'level', 'subjects' => function($query) {
          return $query->with(['prerequisites']);
        }]);
        return new CurriculumResource($curriculum);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Curriculum  $curriculum
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Curriculum $curriculum)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'school_category_id' => 'required|numeric',
            'course_id' => 'required_if:school_category_id,4,5,6,7',
            'level_id' => 'required_if:school_category_id,1,2,3,6,7',
            'effective_year' => 'required|numeric'
        ],
        [
            'required_if' => 'The :attribute field is required.'
        ], 
        [
            'school_category_id' => 'school category',
            'course_id' => 'course'
        ]);

        $data = $request->except('subjects');

        $success = $curriculum->update($data);

        if ($request->has('subjects')) {
            $subjects = $request->subjects;
            $items = [];
            foreach ($subjects as $subject) {
                $items[$subject['subject_id']] = [
                    'course_id' => $request->course_id,
                    'school_category_id' => $request->school_category_id,
                    'level_id' => $subject['level_id'],
                    'semester_id' => $subject['semester_id']
                ];
            }
            $curriculum->subjects()->sync($items);
        }
        
        if ($request->active) {
          $curriculums = Curriculum::where('school_category_id', $request->school_category_id)
          ->where('course_id', $request->course_id)
          ->where('level_id', $request->level_id)
          ->where('id', '!=', $curriculum->id);
          $curriculums->update([
            'active' => 0
          ]);
        }   
        
        if($success){
            $curriculum->load(['schoolCategory', 'course', 'level']);
            return (new CurriculumResource($curriculum))
            ->response()
            ->setStatusCode(200);
        }
      return response()->json([], 400); // Note! add error here
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Curriculum  $curriculum
     * @return \Illuminate\Http\Response
     */
    public function destroy(Curriculum $curriculum)
    {
        $curriculum->delete();
        return response()->json([], 204);
    }
}
