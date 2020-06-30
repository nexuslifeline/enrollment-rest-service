<?php

namespace App\Http\Controllers;

use App\Section;
use Illuminate\Http\Request;
use App\Http\Resources\SectionResource;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 20;
        $query = Section::with(['schoolYear','schoolCategory','level','course','semester']);

        $schoolYearId = $request->school_year_id ?? false;        
        $query->when($schoolYearId, function($q) use ($schoolYearId) {
            return $q->where('school_year_id', $schoolYearId);
        });

        $schoolCategoryId = $request->school_category_id ?? false;        
        $query->when($schoolCategoryId, function($q) use ($schoolCategoryId) {
            return $q->where('level_id', $schoolCategoryId);
        });

        $levelId = $request->level_id ?? false;        
        $query->when($levelId, function($q) use ($levelId) {
            return $q->where('level_id', $levelId);
        });

        $courseId = $request->course_id ?? false;        
        $query->when($courseId, function($q) use ($courseId) {
            return $q->where('course_id', $courseId);
        });

        $semesterId = $request->semester_id ?? false;        
        $query->when($semesterId, function($q) use ($semesterId) {
            return $q->where('semester_id', $semesterId);
        });


        $sections = !$request->has('paginate') || $request->paginate === 'true'
            ? $query->paginate($perPage)
            : $query->get();        

        return SectionResource::collection(
            $sections->load(['schoolYear','schoolCategory','level','course','semester'])
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
            'name' => 'required|max:191',
            'description' => 'required|max:191',
            'school_year_id' => 'required',
            'school_category_id' => 'required',
            'level_id' => 'required',
            'course_id' => 'required_if:school_category_id,4,5,6',
            'semester_id' => 'required_if:school_category_id,4,5,6'
        ], 
        [
            'required_if' => 'The :attribute field is required.'
        ], 
        [
            'school_year_id' => 'school year',
            'school_category_id' => 'school category',
            'level_id' => 'level',
            'course_id' => 'course',
            'semester_id' => 'semester',
        ]);
  
          $data = $request->all();
  
          $section = Section::create($data);
          
        //   $section->load(['department', 'schoolCategory']);
          $section->load(['schoolYear','schoolCategory','level','course','semester']);
          return (new SectionResource($section))
              ->response()
              ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section)
    {
        $section->load(['schoolYear','schoolCategory','level','course','semester']);
        return new SectionResource($section);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Section $section)
    {
        $this->validate($request, [
            'name' => 'required|max:191',
            'description' => 'required|max:191',
            'school_year_id' => 'required',
            'school_category_id' => 'required',
            'level_id' => 'required',
            'course_id' => 'required_if:school_category_id,4,5,6',
            'semester_id' => 'required_if:school_category_id,4,5,6'
        ], 
        [
            'required_if' => 'The :attribute field is required.'
        ], 
        [
            'school_year_id' => 'school year',
            'school_category_id' => 'school category',
            'level_id' => 'level',
            'course_id' => 'course',
            'semester_id' => 'semester',
        ]);
  
        $data = $request->all();
  
        $success = $section->update($data);
        $section->load(['schoolYear','schoolCategory','level','course','semester']);
        if($success){
            return (new SectionResource($section))
            ->response()
            ->setStatusCode(200);
        }
        return response()->json([], 400); // Note! add error here
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Section $section)
    {
        $section->delete();
        return response()->json([], 204);
    }
}
