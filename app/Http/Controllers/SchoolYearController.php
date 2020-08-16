<?php

namespace App\Http\Controllers;

use App\Http\Requests\SchoolYearStoreRequest;
use App\Http\Requests\SchoolYearUpdateRequest;
use App\SchoolYear;
use Illuminate\Http\Request;
use App\Http\Resources\SchoolYearResource;
use App\Services\SchoolYearService;

class SchoolYearController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $schoolYearService = new SchoolYearService();
        $schoolYears = $schoolYearService->index($request);
        return SchoolYearResource::collection(
            $schoolYears
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SchoolYearStoreRequest $request)
    {
        $schoolYearService = new SchoolYearService();
        $schoolYear = $schoolYearService->store($request->all());

        return (new SchoolYearResource($schoolYear))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SchoolYear  $schoolYear
     * @return \Illuminate\Http\Response
     */
    public function show(SchoolYear $schoolYear)
    {
        return new SchoolYearResource($schoolYear);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SchoolYear  $schoolYear
     * @return \Illuminate\Http\Response
     */
    public function update(SchoolYearUpdateRequest $request, SchoolYear $schoolYear)
    {
        $schoolYearService = new SchoolYearService();
        $schoolYear = $schoolYearService->update($request->all(), $schoolYear);

        return (new SchoolYearResource($schoolYear))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SchoolYear  $schoolYear
     * @return \Illuminate\Http\Response
     */
    public function destroy(SchoolYear $schoolYear)
    {
        $schoolYearService = new SchoolYearService();
        $schoolYearService->delete($schoolYear);
        return response()->json([], 204);
    }
}
