<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApplicationResource;
use App\Services\ApplicationService;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function requestEvaluation(int $applicationId)
    {
        $applicationService = new ApplicationService();
        $application = $applicationService->requestEvaluation($applicationId);
        return (new ApplicationResource($application))
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
