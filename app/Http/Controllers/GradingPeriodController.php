<?php

namespace App\Http\Controllers;

use App\GradingPeriod;
use Illuminate\Http\Request;
use App\Services\GradingPeriodService;
use App\Http\Resources\GradingPeriodResource;
use App\Http\Requests\GradingPeriodStoreRequest;
use App\Http\Requests\GradingPeriodUpdateRequest;

class GradingPeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $gradingPeriodService = new GradingPeriodService();
        $filters = $request->except('per_page', 'paginate');
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $gradingPeriods = $gradingPeriodService->list($isPaginated, $perPage, $filters);
        return GradingPeriodResource::collection(
            $gradingPeriods
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GradingPeriodStoreRequest $request)
    {
        $gradingPeriodService = new GradingPeriodService();
        $gradingPeriod = $gradingPeriodService->store($request->all());
        return (new GradingPeriodResource($gradingPeriod))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GradingPeriod  $gradingPeriod
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $gradingPeriodService = new GradingPeriodService();
        $gradingPeriod = $gradingPeriodService->get($id);
        return new GradingPeriodResource($gradingPeriod);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GradingPeriod  $gradingPeriod
     * @return \Illuminate\Http\Response
     */
    public function update(GradingPeriodUpdateRequest $request, int $id)
    {
        $gradingPeriodService = new GradingPeriodService();
        $gradingPeriod = $gradingPeriodService->update($request->all(), $id);

        return (new GradingPeriodResource($gradingPeriod))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GradingPeriod  $gradingPeriod
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $gradingPeriodService = new GradingPeriodService();
        $gradingPeriodService->delete($id);
        return response()->json([], 204);
    }
}
