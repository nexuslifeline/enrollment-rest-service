<?php

namespace App\Http\Controllers;

use App\AcademicRecord;
use Illuminate\Http\Request;
use App\Http\Resources\AcademicRecordResource;
use App\Services\AcademicRecordService;

class AcademicRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $academicRecordService = new AcademicRecordService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $filters = $request->except('per_page', 'paginate');
        $academicRecords = $academicRecordService->list($isPaginated, $perPage, $filters);
        // $registrar = $request->registrar ?? false;
        // $students->when($registrar, function($students) {
        //     return $students->append(['active_admission', 'active_application', 'academicRecord']);
        // });

        return AcademicRecordResource::collection(
            $academicRecords
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
        $academicRecordService = new AcademicRecordService();
        $academicRecord = $academicRecordService->get($id);
        return new AcademicRecordResource($academicRecord);
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
    public function update(Request $request, int $id)
    {
        $academicRecordService = new AcademicRecordService();
        $except = ['application', 'admission', 'student_fee', 'subjects', 'fees', 'billing', 'billing_item'];
        $data = $request->except($except);
        $academicRecordInfo = $request->only($except);
        $academicRecord = $academicRecordService->update($data, $academicRecordInfo, $id);
        return new AcademicRecordResource($academicRecord);
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

    public function getAcademicRecordsOfStudent($studentId, Request $request)
    {
        $academicRecordService = new AcademicRecordService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $filters = $request->except('per_page', 'paginate');
        $evaluations = $academicRecordService->getAcademicRecordsOfStudent($studentId, $isPaginated, $perPage, $filters);
        return AcademicRecordResource::collection($evaluations);
    }

    public function getPendingApprovalCount(Request $request)
    {
        $academicRecordService = new AcademicRecordService();
        $filters = $request->except('per_page', 'paginate');
        return $academicRecordService->getPendingApprovalCount($filters);
    }

    public function getGradesOfAcademicRecords($subjectId, $sectionId, Request $request)
    {
        $academicRecordService = new AcademicRecordService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $filters = $request->except('per_page', 'paginate');
        $academicRecords = $academicRecordService->getGradesOfAcademicRecords($subjectId, $sectionId, $isPaginated, $perPage, $filters);
        return AcademicRecordResource::collection($academicRecords);
    }

    public function gradeBatchUpdate(Request $request)
    {
        $data = $request->all();
        $academicRecordService = new AcademicRecordService();
        $academicRecords = $academicRecordService->gradeBatchUpdate($data);

        return AcademicRecordResource::collection(
            $academicRecords
        );
    }
}
