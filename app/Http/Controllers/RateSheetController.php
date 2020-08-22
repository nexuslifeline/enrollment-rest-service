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
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $filters = $request->except('per_page', 'paginate');
        $rateSheets = $rateSheetService->list($isPaginated, $perPage, $filters);
        return RateSheetResource::collection(
            $rateSheets
        );
    }

    public function store(RateSheetStoreRequest $request)
    {
        $rateSheetService = new RateSheetService();
        $data = $request->except('fees');
        $fees = $request->fees ?? [];
        $rateSheet = $rateSheetService->store($data, $fees);
        return (new RateSheetResource($rateSheet))
                ->response()
                ->setStatusCode(201);
    }

    public function update(RateSheetUpdateRequest $request, int $id)
    {
        $rateSheetService = new RateSheetService();
        $data = $request->except('fees');
        $fees = $request->fees ?? [];
        $rateSheet = $rateSheetService->update($data, $fees, $id);
        return (new RateSheetResource($rateSheet))
        ->response()
        ->setStatusCode(200);
    }

    public function show(int $id)
    {
        $rateSheetService = new RateSheetService();
        $rateSheet = $rateSheetService->get($id);
        return new RateSheetResource($rateSheet);
    }

    public function destroy(int $id)
    {
        $rateSheetService = new RateSheetService();
        $rateSheetService->delete($id);
        return response()->json([], 204);
    }
}
