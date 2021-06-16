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
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $filters = $request->all();
        $courses = $courseService->list($isPaginated, $perPage, $filters);
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
        $data = $request->except('levels');
        $levels = $request->levels ?? [];
        $course = $courseService->store($data, $levels);
        
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
    public function show(int $id)
    {
        $courseService = new CourseService();
        $course = $courseService->get($id);
        return new CourseResource($course);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(CourseUpdateRequest $request, int $id)
    {
        $courseService = new CourseService();
        $data = $request->except('levels');
        $levels = $request->levels ?? [];
        $course = $courseService->update($data, $levels, $id);
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
    public function destroy(int $id)
    {
        $courseService = new CourseService();
        $courseService->delete($id);
        return response()->json([], 204);
    }

    public function getCoursesOfLevel($levelId, Request $request)
    {
        $courseService = new CourseService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $filters = $request->except('per_page', 'paginate');
        $courses = $courseService->getCoursesOfLevel($levelId, $isPaginated, $perPage, $filters);
        return CourseResource::collection($courses);
    }

    public function getCoursesOfSchoolCategory($schoolCategoryId, Request $request)
    {
        $courseService = new CourseService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $courses = $courseService->getCoursesOfSchoolCategory($schoolCategoryId, $isPaginated, $perPage);
        return CourseResource::collection($courses);
    }
}
