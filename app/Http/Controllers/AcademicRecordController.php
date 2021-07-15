<?php

namespace App\Http\Controllers;

use App\AcademicRecord;
use App\Http\Requests\AcademicRecordGenerateBillingRequest;
use Illuminate\Http\Request;
use App\Services\AcademicRecordService;
use App\Http\Resources\AcademicRecordResource;
use App\Http\Requests\AcademicRecordPatchRequest;
use App\Http\Requests\AcademicRecordQuickEnrollRequest;
use App\Http\Requests\AcademicRecordUpdateRequest;
use App\Http\Requests\ApplicationRequestEvaluation;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\EvaluationResource;
use App\Http\Resources\SubjectResource;

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
    public function update(AcademicRecordUpdateRequest $request, int $id)
    {
        $academicRecordService = new AcademicRecordService();
        $except = ['application', 'admission', 'student_fee', 'subjects', 'fees', 'billing', 'billing_item', 'transcript_record'];
        $data = $request->except($except);
        $academicRecordInfo = $request->only($except);
        $academicRecord = $academicRecordService->update($data, $academicRecordInfo, $id);
        return (new AcademicRecordResource($academicRecord))
            ->response()
            ->setStatusCode(200);
    }

    public function patch(AcademicRecordPatchRequest $request, int $id)
    {
        $academicRecordService = new AcademicRecordService();
        $data = $request->all();
        $academicRecordInfo = [];
        $academicRecord = $academicRecordService->update($data, $academicRecordInfo, $id);
        return (new AcademicRecordResource($academicRecord))
        ->response()
        ->setStatusCode(200);
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

    // public function gradeBatchUpdate(Request $request)
    // {
    //     $data = $request->all();
    //     $academicRecordService = new AcademicRecordService();
    //     $academicRecords = $academicRecordService->gradeBatchUpdate($data);

    //     return AcademicRecordResource::collection(
    //         $academicRecords
    //     );
    // }

    // public function finalizeGrades(Request $request)
    // {
    //     $data = $request->all();
    //     $academicRecordService = new AcademicRecordService();
    //     $academicRecords = $academicRecordService->finalizeGrades($data);

    //     // return AcademicRecordResource::collection(
    //         return $academicRecords;
    //     // );
    // }

    public function getSubjects(Request $request, $id)
    {
        $academicRecordService = new AcademicRecordService();

        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $filters = $request->except('per_page', 'paginate');

        $subjects = $academicRecordService->getSubjects($id, $isPaginated, $perPage, $filters);

        return AcademicRecordResource::collection(
            $subjects
        );
    }

    public function updateSubject(Request $request, $academicRecordId, $subjectId)
    {
        $academicRecordService = new AcademicRecordService();
        $data = $request->only(['section_id', 'is_dropped']);
        $academicRecord = $academicRecordService->updateSubject(
            $data,
            $academicRecordId,
            $subjectId
        );
        return new AcademicRecordResource($academicRecord);
    }

    public function quickEnroll(AcademicRecordQuickEnrollRequest $request, int $studentId)
    {
        $academicRecordService = new AcademicRecordService();
        $data = $request->all();
        $academicRecord = $academicRecordService->quickEnroll($data, $studentId);
        return (new AcademicRecordResource($academicRecord))
            ->response()
            ->setStatusCode(201);
    }

    public function syncSubjectsOfAcademicRecord(Request $request, int $academicRecordId)
    {
        $academicRecordService = new AcademicRecordService();
        $subjects = $request->subjects ?? [];
        $subjects = $academicRecordService->syncSubjectsOfAcademicRecord($academicRecordId, $subjects);
        return SubjectResource::collection($subjects);
    }

    public function getInitialBilling(int $academicRecordId)
    {
        $academicRecordService = new AcademicRecordService();
        $academicRecordBill = $academicRecordService->getInitialBilling($academicRecordId);
        return new AcademicRecordResource($academicRecordBill);
    }
    
    public function requestEvaluation(ApplicationRequestEvaluation $request, int $academicRecordId)
    {
        $academicRecordService = new AcademicRecordService();
        $evaluationData = $request->except('level_id', 'course_id', 'semester_id');
        $data = $request->only('level_id', 'course_id', 'semester_id');
        $evaluation = $academicRecordService->requestEvaluation($data, $evaluationData, $academicRecordId);
        return (new EvaluationResource($evaluation))
            ->response()
            ->setStatusCode(201);
    }

    public function submit(Request $request, int $academicRecordId)
    {
        $academicRecordService = new AcademicRecordService();
        $data = $request->except('subjects');
        $subjects = $request->subjects ?? [];
        $application = $academicRecordService->submit($data, $subjects, $academicRecordId);
        return (new ApplicationResource($application))
            ->response()
            ->setStatusCode(201);
    }

    public function approveAssessment(Request $request, int $academicRecordId)
    {
        $academicRecordService = new AcademicRecordService();
        $data = $request->except('fees');
        $fees = $request->fees ?? [];
        $academicRecord = $academicRecordService->approveAssessment($data, $fees, $academicRecordId);
        return (new AcademicRecordResource($academicRecord))
            ->response()
            ->setStatusCode(201);
    }

    public function rejectAssessment(Request $request, int $academicRecordId)
    {
        $academicRecordService = new AcademicRecordService();
        $data = $request->all();
        $academicRecord = $academicRecordService->rejectAssessment($data, $academicRecordId);
        return (new AcademicRecordResource($academicRecord))
            ->response()
            ->setStatusCode(201);
    }

    public function approveEnlistment(Request $request, int $academicRecordId)
    {
        $academicRecordService = new AcademicRecordService();
        $data = $request->except('subjects');
        $subjects = $request->subjects ?? [];
        $academicRecord = $academicRecordService->approveEnlistment($data, $subjects, $academicRecordId);
        return (new AcademicRecordResource($academicRecord))
            ->response()
            ->setStatusCode(201);
    }

    public function rejectEnlistment(Request $request, int $academicRecordId)
    {
        $academicRecordService = new AcademicRecordService();
        $data = $request->all();
        $academicRecord = $academicRecordService->rejectEnlistment($data, $academicRecordId);
        return (new AcademicRecordResource($academicRecord))
            ->response()
            ->setStatusCode(201);
    }

    public function requestAssessment(int $academicRecordId)
    {
        $academicRecordService = new AcademicRecordService();
        $academicRecord = $academicRecordService->requestAssessment($academicRecordId);
        return (new AcademicRecordResource($academicRecord))
            ->response()
            ->setStatusCode(201);
    }

    public function generateBilling(AcademicRecordGenerateBillingRequest $request, int $academicRecordId)
    {
        $academicRecordService = new AcademicRecordService();
        $data = $request->except('other_fees');
        $otherFees = $request->other_fees ?? [];
        $academicRecord = $academicRecordService->generateBilling($data, $otherFees, $academicRecordId);
        return (new AcademicRecordResource($academicRecord))
            ->response()
            ->setStatusCode(201);
    }
}
