<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectionStoreRequest;
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
        $sections = $sectionService->index($request);
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
        $section = $sectionService->store($request);
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
        $section->load(['schoolYear','schoolCategory','level','course','semester','schedules']);
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
        $sectionService = new SectionService();
        $section = $sectionService->update($request, $section);
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
    public function destroy(Section $section)
    {
        $sectionService = new SectionService();
        $sectionService->delete($section);
        return response()->json([], 204);
    }
}
