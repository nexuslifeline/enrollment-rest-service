<?php

namespace App\Http\Controllers;

use App\Curriculum;
use App\Http\Requests\CurriculumStoreRequest;
use App\Http\Requests\CurriculumUpdateRequest;
use Illuminate\Http\Request;
use App\Http\Resources\CurriculumResource;
use App\Services\CurriculumService;

class CurriculumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $curriculumService = new CurriculumService();
        $curriculums = $curriculumService->index($request);
        return CurriculumResource::collection($curriculums);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CurriculumStoreRequest $request)
    {
        $curriculumService = new CurriculumService();
        $curriculum = $curriculumService->store($request);
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
        // return $curriculum->id;
        $curriculum->load(['schoolCategory', 'course', 'level', 'subjects' => function($query) use ($curriculum) {
            return $query->with(['prerequisites' => function ($query) use ($curriculum) {
                $query->where('curriculum_id', $curriculum->id);
            }]);
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
    public function update(CurriculumUpdateRequest $request, Curriculum $curriculum)
    {
        $curriculumService = new CurriculumService();
        $curriculum = $curriculumService->update($request, $curriculum);
        return (new CurriculumResource($curriculum))
        ->response()
        ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Curriculum  $curriculum
     * @return \Illuminate\Http\Response
     */
    public function destroy(Curriculum $curriculum)
    {
        $curriculumService = new CurriculumService();
        $curriculumService->delete($curriculum);
        return response()->json([], 204);
    }
}
