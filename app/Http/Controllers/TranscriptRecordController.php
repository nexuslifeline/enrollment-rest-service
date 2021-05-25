<?php

namespace App\Http\Controllers;

use App\AcademicRecord;
use Illuminate\Http\Request;
use App\Services\TranscriptRecordService;
use App\Http\Resources\TranscriptRecordResource;
use App\Http\Requests\TranscriptSubjectsUpdateRequest;

class TranscriptRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $transcriptRecordService = new transcriptRecordService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $filters = $request->except('per_page', 'paginate');
        $transcriptRecords = $transcriptRecordService->list($isPaginated, $perPage, $filters);

        return TranscriptRecordResource::collection(
            $transcriptRecords
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transcriptRecordService = new TranscriptRecordService();
        $transcriptRecord = $transcriptRecordService->get($id);
        return new TranscriptRecordResource($transcriptRecord);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $transcriptRecordService = new TranscriptRecordService();
        $data = $request->except('subjects');
        $subjects = $request->subjects ?? [];
        $requirements = $request->requirements ?? [];
        $transcriptRecord = $transcriptRecordService->update($data, $subjects, $requirements, $id);
        return new TranscriptRecordResource($transcriptRecord);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getLevels(Request $request, $id)
    {
        $transcriptRecordService = new transcriptRecordService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $filters = $request->except('per_page', 'paginate');
        $levels = $transcriptRecordService->getLevels($id, $isPaginated, $perPage, $filters);

        return TranscriptRecordResource::collection(
            $levels
        );
    }

    public function updateSubjects(TranscriptSubjectsUpdateRequest $request, $id)
    {
        $transcriptRecordService = new TranscriptRecordService();
        $subjects = $request->all();
        $transcriptRecord = $transcriptRecordService->updateSubjects($subjects, $id);
        return new TranscriptRecordResource($transcriptRecord);
    }

    public function activeFirstOrCreate(Request $request)
    {
        $transcriptRecordService = new TranscriptRecordService();
        $academicRecord = new AcademicRecord($request->all());
        $transcriptRecord = $transcriptRecordService->activeFirstOrCreate($academicRecord);
        return new TranscriptRecordResource($transcriptRecord);
    }
}
