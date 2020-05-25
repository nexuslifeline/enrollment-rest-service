<?php

namespace App\Http\Controllers;

use App\Level;
use App\Subject;
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
    public function index()
    {
        //
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
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function edit(Subject $subject)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        //
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
}
