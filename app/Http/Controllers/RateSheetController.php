<?php

namespace App\Http\Controllers;

use App\Http\Requests\RateSheetStoreRequest;
use App\Http\Requests\RateSheetUpdateRequest;
use App\RateSheet;
use Illuminate\Http\Request;
use App\Http\Resources\RateSheetResource;
use App\Services\RateSheetService;

class RateSheetController extends Controller
{
    public function index(Request $request)
    {
        $rateSheetService = new RateSheetService();
        $rateSheets = $rateSheetService->index($request);
        return RateSheetResource::collection(
            $rateSheets
        );
    }

    public function store(RateSheetStoreRequest $request)
    {
        $rateSheetService = new RateSheetService();
        $rateSheet = $rateSheetService->store($request);
        return (new RateSheetResource($rateSheet))
                ->response()
                ->setStatusCode(201);
    }

    public function update(RateSheetUpdateRequest $request, RateSheet $rateSheet)
    {
        $rateSheetService = new RateSheetService();
        $rateSheet = $rateSheetService->update($request, $rateSheet);
        return (new RateSheetResource($rateSheet))
        ->response()
        ->setStatusCode(200);
    }

    public function show(RateSheet $rateSheet)
    {
        $rateSheet->load(['level', 'course', 'semester', 'fees']);
        return new RateSheetResource($rateSheet);
    }

    public function destroy(RateSheet $rateSheet)
    {
        $rateSheetService = new RateSheetService();
        $rateSheetService->delete($rateSheet);
        return response()->json([], 204);
    }
}
