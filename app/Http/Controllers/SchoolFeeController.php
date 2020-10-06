<?php

namespace App\Http\Controllers;

use App\Http\Requests\SchoolFeeStoreRequest;
use App\Http\Requests\SchoolFeeUpdateRequest;
use App\SchoolFee;
use Illuminate\Http\Request;
use App\Http\Resources\SchoolFeeResource;
use App\Services\SchoolFeeService;

class SchoolFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $schoolFeeService = new SchoolFeeService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $schoolFees = $schoolFeeService->list($isPaginated, $perPage);
        return SchoolFeeResource::collection($schoolFees);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SchoolFeeStoreRequest $request)
    {
        $schoolFeeService = new SchoolFeeService();
        $schoolFee = $schoolFeeService->store($request->all());
        return (new SchoolFeeResource($schoolFee))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SchoolFee  $schoolFee
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $schoolFeeService = new SchoolFeeService();
        $schoolFee = $schoolFeeService->get($id);
        return new SchoolFeeResource($schoolFee);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SchoolFee  $schoolFee
     * @return \Illuminate\Http\Response
     */
    public function update(SchoolFeeUpdateRequest $request, int $id)
    {
        $schoolFeeService = new SchoolFeeService();
        $schoolFee = $schoolFeeService->update($request->all(), $id);
        return (new SchoolFeeResource($schoolFee))
                ->response()
                ->setStatusCode(200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SchoolFee  $schoolFee
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $schoolFeeService = new SchoolFeeService();
        $schoolFeeService->delete($id);
        return response()->json([], 204);
    }
}
