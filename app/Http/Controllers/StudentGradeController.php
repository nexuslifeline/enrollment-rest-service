<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentGradeUpdateGradePeriodRequest;
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
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, StudentGrade $studentGrade)
    {
        //
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
}
