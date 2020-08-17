<?php

namespace App\Http\Controllers;

use App\Course;
use App\Http\Requests\CourseStoreRequest;
use App\Http\Requests\CourseUpdateRequest;
use App\Level;
use App\SchoolCategory;
use Illuminate\Http\Request;
use App\Http\Resources\CourseResource;
use App\Services\CourseService;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $courseService = new CourseService();
        $courses = $courseService->index($request);
        return CourseResource::collection($courses);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CourseStoreRequest $request)
    {
        $courseService = new CourseService();
        $course = $courseService->store($request);
        
        return (new CourseResource($course))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        return new CourseResource($course);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(CourseUpdateRequest $request, Course $course)
    {
        $courseService = new CourseService();
        $course = $courseService->update($request, $course);
        return (new CourseResource($course))
        ->response()
        ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        $courseService = new CourseService();
        $courseService->delete($course);
        return response()->json([], 204);
    }

    public function getCoursesOfLevel($levelId, Request $request)
    {
        $courseService = new CourseService();
        $courses = $courseService->getCoursesOfLevel($levelId, $request);
        return CourseResource::collection($courses);
    }

    public function getCoursesOfSchoolCategory($schoolCategoryId, Request $request)
    {
        $courseService = new CourseService();
        $courses = $courseService->getCoursesOfSchoolCategory($schoolCategoryId, $request);
        return CourseResource::collection($courses);
    }
}
