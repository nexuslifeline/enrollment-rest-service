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
        $data = $request->except('level_id');
        $levelId = $request->level_id;
        $evaluation = $applicationService->requestEvaluation($data, $applicationId, $levelId);
        return (new EvaluationResource($evaluation))
            ->response()
            ->setStatusCode(201);
    }

    public function submit(int $applicationId)
    {
        $applicationService = new ApplicationService();
        $application = $applicationService->submit($applicationId);
        return (new ApplicationResource($application))
            ->response()
            ->setStatusCode(201);
    }
}
