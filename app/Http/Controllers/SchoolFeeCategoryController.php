<?php

namespace App\Http\Controllers;

use App\Http\Requests\SchoolFeeCategoryStoreRequest;
use App\Http\Requests\SchoolFeeUpdateRequest;
use App\SchoolFeeCategory;
use Illuminate\Http\Request;
use App\Http\Resources\SchoolFeeCategoryResource;
use App\Services\SchoolFeeCategoryService;
use App\Services\SchoolFeeService;

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
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $schoolFeeCategories = $schoolFeeCategoryService->list($isPaginated, $perPage);
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
    public function show(int $id)
    {
        $schoolFeeCategoryService = new SchoolFeeCategoryService();
        $schoolFeeCategory = $schoolFeeCategoryService->get($id);
        return new SchoolFeeCategoryResource($schoolFeeCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SchoolFeeCategory  $schoolFeeCategory
     * @return \Illuminate\Http\Response
     */
    public function update(SchoolFeeUpdateRequest $request, int $id)
    {
        $schoolFeeCategoryService = new SchoolFeeCategoryService();
        $schoolFeeCategory = $schoolFeeCategoryService->update($request->all(), $id);

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
    public function destroy(int $id)
    {
        $schoolFeeCategoryService = new SchoolFeeCategoryService();
        $schoolFeeCategoryService->delete($id);
        return response()->json([], 204);
    }
}
