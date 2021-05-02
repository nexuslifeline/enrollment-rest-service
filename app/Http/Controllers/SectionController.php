<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectionStoreRequest;
use App\Http\Requests\SectionUpdateRequest;
use App\Section;
use Illuminate\Http\Request;
use App\Http\Resources\SectionResource;
use App\Services\SectionService;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sectionService = new SectionService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $filters = $request->except('per_page', 'paginate');
        $sections = $sectionService->list($isPaginated, $perPage, $filters);
        return SectionResource::collection($sections);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SectionStoreRequest $request)
    {
        $sectionService = new SectionService();
        $data = $request->except('schedules');
        $schedules = $request->schedules ?? [];
        $section = $sectionService->store($data, $schedules);
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
    public function show(int $id)
    {
        $sectionService = new SectionService();
        $section = $sectionService->get($id);
        return new SectionResource($section);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(SectionUpdateRequest $request, int $id)
    {
        $sectionService = new SectionService();
        $data = $request->except('schedules');
        $schedules = $request->schedules ?? [];
        $section = $sectionService->update($data, $schedules, $id);
        return (new SectionResource($section))
        ->response()
        ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $sectionService = new SectionService();
        $sectionService->delete($id);
        return response()->json([], 204);
    }

    public function getSectionsOfSubject(Request $request, int $subjectId) {
        $sectionService = new SectionService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $filters = $request->except('per_page', 'paginate');
        $section = $sectionService->getSectionsOfSubject($isPaginated, $perPage, $filters, $subjectId);
        return (new SectionResource($section))
        ->response()
        ->setStatusCode(200);
    }

    public function getSectionsOfPersonnel(Request $request)
    {
        $sectionService = new SectionService();
        $filters = $request->all();
        $sections = $sectionService->getSectionsOfPersonnel($filters);
        return SectionResource::collection($sections);
    }
}
