<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentGradeApproveEditRequestRequest;
use App\Http\Requests\StudentGradeFinalizeRequest;
use App\Http\Requests\StudentGradeRejectRequest;
use App\Http\Requests\StudentGradeStoreRequest;
use App\Http\Requests\StudentGradeUpdateGradePeriodRequest;
use App\Http\Requests\StudentGradeUpdateRequest;
use App\Http\Resources\StudentGradeResource;
use App\Services\StudentGradeService;
use App\Student;
use App\StudentGrade;
use Illuminate\Http\Request;

class StudentGradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $filters = $request->except('per_page', 'paginate');
        $studentGradeService = new StudentGradeService;
        $studentGrades = $studentGradeService->list($isPaginated, $perPage, $filters);
        return StudentGradeResource::collection($studentGrades);
    }

    public function studentGradePersonnels(Request $request)
    {
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $filters = $request->except('per_page', 'paginate');
        $studentGradeService = new StudentGradeService;
        $studentGrades = $studentGradeService->studentGradePersonnels($isPaginated, $perPage, $filters);
        return StudentGradeResource::collection($studentGrades);
    }

    public function acceptStudentGrade($personnelId, $sectionId, $subjectId)
    {
        $studentGradeService = new StudentGradeService;
        $studentGrades = $studentGradeService->acceptStudentGrade($personnelId, $sectionId, $subjectId);
        return $studentGrades;
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
    public function store(StudentGradeStoreRequest $request)
    {
        $studentGradeService = new StudentGradeService();
        $studentGrade = $studentGradeService->store($request->all());
        return (new StudentGradeResource($studentGrade))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StudentGrade  $studentGrade
     * @return \Illuminate\Http\Response
     */
    public function show(StudentGrade $studentGrade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StudentGrade  $studentGrade
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentGrade $studentGrade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StudentGrade  $studentGrade
     * @return \Illuminate\Http\Response
     */
    public function update(StudentGradeUpdateRequest $request, int $id)
    {
        $studentGradeService = new StudentGradeService();
        $studentGrade = $studentGradeService->update($request->all(), $id);

        return (new StudentGradeResource($studentGrade))
            ->response()
            ->setStatusCode(200);
    }

    public function patch(Request $request, int $id)
    {
        $studentGradeService = new StudentGradeService();
        $studentGrade = $studentGradeService->update($request->all(), $id);

        return (new StudentGradeResource($studentGrade))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StudentGrade  $studentGrade
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentGrade $studentGrade)
    {
        //
    }

    public function batchUpdate(Request $request)
    {
        $data = $request->all();
        $studentGradeService = new StudentGradeService;
        $studentGrades = $studentGradeService->batchUpdate($data);
        return StudentGradeResource::collection($studentGrades);
    }

    public function updateGradePeriod($sectionId, $subjectId, $academicRecordId, $gradingPeriodId, StudentGradeUpdateGradePeriodRequest $request)
    {
        $data = $request->all();
        $studentGradeService = new StudentGradeService;
        $studentGrade = $studentGradeService->updateGradePeriod($sectionId, $subjectId, $academicRecordId, $gradingPeriodId, $data);
        return (new StudentGradeResource($studentGrade))
        ->response()
        ->setStatusCode(200);
    }

    public function submit($studentGradeId, Request $request)
    {
        $data = $request->all();
        $studentGradeService = new StudentGradeService;
        $studentGrade = $studentGradeService->submit($studentGradeId, $data);
        return (new StudentGradeResource($studentGrade))
            ->response()
            ->setStatusCode(200);
    }

    public function publish($studentGradeId, Request $request)
    {
        $data = $request->all();
        $studentGradeService = new StudentGradeService;
        $studentGrade = $studentGradeService->publish($studentGradeId, $data);
        return (new StudentGradeResource($studentGrade))
            ->response()
            ->setStatusCode(200);
    }

    public function unpublish($studentGradeId, Request $request)
    {
        $data = $request->all();
        $studentGradeService = new StudentGradeService;
        $studentGrade = $studentGradeService->unpublish($studentGradeId, $data);
        return (new StudentGradeResource($studentGrade))
            ->response()
            ->setStatusCode(200);
    }

    public function requestEdit($studentGradeId, Request $request)
    {
        $data = $request->all();
        $studentGradeService = new StudentGradeService;
        $studentGrade = $studentGradeService->requestEdit($studentGradeId, $data);
        return (new StudentGradeResource($studentGrade))
            ->response()
            ->setStatusCode(200);
    }

    public function approveEditRequest($studentGradeId, StudentGradeApproveEditRequestRequest $request)
    {
        $data = $request->all();
        $studentGradeService = new StudentGradeService;
        $studentGrade = $studentGradeService->approveEditRequest($studentGradeId, $data);
        return (new StudentGradeResource($studentGrade))
            ->response()
            ->setStatusCode(200);
    }

    public function finalize($studentGradeId, StudentGradeFinalizeRequest $request)
    {
        $data = $request->all();
        $studentGradeService = new StudentGradeService;
        $studentGrade = $studentGradeService->finalize($studentGradeId, $data);
        return (new StudentGradeResource($studentGrade))
            ->response()
            ->setStatusCode(200);
    }

    public function reject($studentGradeId, StudentGradeRejectRequest $request)
    {
        $data = $request->all();
        $studentGradeService = new StudentGradeService;
        $studentGrade = $studentGradeService->reject($studentGradeId, $data);
        return (new StudentGradeResource($studentGrade))
            ->response()
            ->setStatusCode(200);
    }
}
