<?php

namespace App\Http\Controllers;

use App\Http\Requests\SchoolYearGenerateBatchBillingRequest;
use App\SchoolYear;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Services\SchoolFeeService;
use App\Services\SchoolYearService;
use App\Http\Resources\SchoolYearResource;
use App\Http\Requests\SchoolYearPatchRequest;
use App\Http\Requests\SchoolYearStoreRequest;
use App\Http\Requests\SchoolYearUpdateRequest;
use App\Http\Resources\BillingResource;

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
        $filters = $request->except('paginate','per_page');
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $schoolYears = $schoolYearService->list($isPaginated, $perPage, $filters);
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
    public function show(int $id)
    {
        $schoolYearService = new SchoolYearService();
        $schoolYear = $schoolYearService->get($id);
        return new SchoolYearResource($schoolYear);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SchoolYear  $schoolYear
     * @return \Illuminate\Http\Response
     */
    public function update(SchoolYearUpdateRequest $request, int $id)
    {
        $schoolYearService = new SchoolYearService();
        $data = $request->all();

        if (Arr::exists($data, 'is_active')) {
            $schoolYearService->update(['is_active' => false]); // make sure no other sy is active at the same time
        }

        $schoolYear = $schoolYearService->update($data, $id);

        return (new SchoolYearResource($schoolYear))
            ->response()
            ->setStatusCode(200);
    }

    public function patch(SchoolYearPatchRequest $request, int $id)
    {
        $schoolYearService = new SchoolYearService();

        $data = $request->all();

        if (Arr::exists($data, 'is_active')) {
            $schoolYearService->update(['is_active' => false]); // make sure no other sy is active at the same time
        }

        $schoolYear = $schoolYearService->update($data, $id);

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
    public function destroy(int $id)
    {
        $schoolYearService = new SchoolYearService();
        $schoolYearService->delete($id);
        return response()->json([], 204);
    }

    public function generateBatchBilling(SchoolYearGenerateBatchBillingRequest $request, int $id)
    {
        $schoolYearService = new SchoolYearService();
        $data = $request->except('other_fees');
        $otherFees = $request->other_fees ?? [];
        $billings = $schoolYearService->generateBatchBilling($data, $otherFees, $id);
        return (new BillingResource($billings))
            ->response()
            ->setStatusCode(201);
    }
}
