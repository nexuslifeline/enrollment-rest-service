<?php

namespace App\Http\Controllers;

use App\Http\Requests\SchoolFeeCategoryStoreRequest;
use App\Http\Requests\SchoolFeeUpdateRequest;
use App\SchoolFeeCategory;
use Illuminate\Http\Request;
use App\Http\Resources\SchoolFeeCategoryResource;
use App\Services\SchoolFeeCategoryService;

class SchoolFeeCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $schoolFeeCategoryService = new SchoolFeeCategoryService();
        $schoolFeeCategories = $schoolFeeCategoryService->index($request);
        return SchoolFeeCategoryResource::collection($schoolFeeCategories);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SchoolFeeCategoryStoreRequest $request)
    {
        $schoolFeeCategoryService = new SchoolFeeCategoryService();
        $schoolFeeCategory = $schoolFeeCategoryService->store($request->all());
        return (new SchoolFeeCategoryResource($schoolFeeCategory))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SchoolFeeCategory  $schoolFeeCategory
     * @return \Illuminate\Http\Response
     */
    public function show(SchoolFeeCategory $schoolFeeCategory)
    {
        return new SchoolFeeCategoryResource($schoolFeeCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SchoolFeeCategory  $schoolFeeCategory
     * @return \Illuminate\Http\Response
     */
    public function update(SchoolFeeUpdateRequest $request, SchoolFeeCategory $schoolFeeCategory)
    {
        $schoolFeeCategoryService = new SchoolFeeCategoryService();
        $schoolFeeCategory = $schoolFeeCategoryService->update($request->all(), $schoolFeeCategory);

        return (new SchoolFeeCategoryResource($schoolFeeCategory))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SchoolFeeCategory  $schoolFeeCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(SchoolFeeCategory $schoolFeeCategory)
    {
        $schoolFeeCategoryService = new SchoolFeeCategoryService();
        $schoolFeeCategoryService->delete($schoolFeeCategory);
        return response()->json([], 204);
    }
}
