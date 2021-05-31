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
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $filters = $request->except('per_page', 'paginate');
        $curriculums = $curriculumService->list($isPaginated, $perPage, $filters);
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
        $data = $request->except('subjects', 'school_categories', 'prerequisites');
        $subjects = $request->subjects ?? [];
        $schoolCategories = $request->school_categories ?? [];
        $prerequisites = $request->prerequisites ?? [];
        $curriculum = $curriculumService->store($data, $subjects, $schoolCategories, $prerequisites);
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
    public function show(int $id)
    {
        $curriculumService = new CurriculumService();
        $curriculum = $curriculumService->get($id);
        return new CurriculumResource($curriculum);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Curriculum  $curriculum
     * @return \Illuminate\Http\Response
     */
    public function update(CurriculumUpdateRequest $request, int $id)
    {
        $curriculumService = new CurriculumService();
        $data = $request->except('subjects', 'prerequisites', 'school_categories');
        $subjects = $request->subjects ?? [];
        $schoolCategories = $request->school_categories ?? [];
        $prerequisites = $request->prerequisites ?? [];
        $curriculum = $curriculumService->update($data, $subjects, $schoolCategories, $prerequisites, $id);
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
    public function destroy(int $id)
    {
        $curriculumService = new CurriculumService();
        $curriculumService->delete($id);
        return response()->json([], 204);
    }
}
