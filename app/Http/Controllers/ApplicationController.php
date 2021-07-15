<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationRequestEvaluation;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\EvaluationResource;
use App\Services\ApplicationService;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function requestEvaluation(ApplicationRequestEvaluation $request, int $applicationId)
    {
        $applicationService = new ApplicationService();
        $data = $request->except('level_id','course_id','semester_id');
        $academicRecordData = $request->only('level_id', 'course_id', 'semester_id');
        $evaluation = $applicationService->requestEvaluation($data, $academicRecordData, $applicationId);
        return (new EvaluationResource($evaluation))
            ->response()
            ->setStatusCode(201);
    }

    public function submit(Request $request, int $applicationId)
    {
        $applicationService = new ApplicationService();
        $data = $request->except('subjects');
        $subjects = $request->subjects ?? [];
        $application = $applicationService->submit($data, $subjects, $applicationId);
        return (new ApplicationResource($application))
            ->response()
            ->setStatusCode(201);
    }
}
