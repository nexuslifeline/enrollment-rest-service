<?php

namespace App\Http\Controllers;

use App\Course;
use App\Level;
use App\SchoolCategory;
use Illuminate\Http\Request;
use App\Http\Resources\CourseResource;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->perPage ?? 20;
        $courses = $request->has('paginate') || $request->paginate === 'true'
            ? Courses::paginate($perPage)
            : Courses::all();
        return CourseResource::collection(
            $courses
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        //
    }

    public function getCoursesOfLevel($levelId, Request $request)
    {

        $perPage = $request->perPage ?? 20;
        $query = Level::find($levelId)->courses();

        // filters
        $schoolCategoryId = $request->school_category_id ?? false;
        $query->when($schoolCategoryId, function($q) use ($schoolCategoryId, $levelId) {
            return $q->whereHas('school_categories', function($query) use ($schoolCategoryId, $levelId) {
                return $query->where('school_category_id', $schoolCategoryId)->where('level_id', $levelId);
            });
        });

        $courses = !$request->has('paginate') || $request->paginate === 'true'
            ? $query->paginate($perPage)
            : $query->get();

        return CourseResource::collection($courses);
    }

    public function getCoursesOfSchoolCategory($schoolCategoryId, Request $request)
    {
        $perPage = $request->per_page ?? 20;
        $query = SchoolCategory::find($schoolCategoryId)->courses();

        //filters
        $levelId = $request->level_id ?? false;
        $query->when($levelId, function($q) use ($levelId, $schoolCategoryId) {
            return $q->whereHas('levels', function($query) use ($levelId, $schoolCategoryId) {
                return $query->where('level_id', $levelId)->where('level_courses.school_category_id', $schoolCategoryId);
            });
        });

        $course = !$request->has('paginate') || $request->paginate === 'true'
            ? $query->paginate($perPage)
            : $query->get();

        return CourseResource::collection($course);
    }
}
