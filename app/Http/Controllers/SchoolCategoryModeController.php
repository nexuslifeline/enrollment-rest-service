<?php

namespace App\Http\Controllers;

use App\SchoolCategoryMode;
use Illuminate\Http\Request;
use App\Services\SchoolCategoryModeService;
use App\Http\Resources\SchoolCategoryModeResource;
use App\Http\Requests\SchoolCategoryModeStoreRequest;
use App\Http\Requests\SchoolCategoryModeUpdateRequest;

class SchoolCategoryModeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $schoolCategoryModeService = new SchoolCategoryModeService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $schoolCategoryModes = $schoolCategoryModeService->list($isPaginated, $perPage);
        return SchoolCategoryModeResource::collection(
            $schoolCategoryModes
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SchoolCategoryModeStoreRequest $request, int $id)
    {
        $schoolCategoryModeService = new SchoolCategoryModeService();
        $schoolCategoryMode = $schoolCategoryModeService->store($request->all());
        return (new SchoolCategoryModeResource($schoolCategoryMode))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SchoolCategoryMode  $schoolCategoryMode
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $schoolCategoryModeService = new SchoolCategoryModeService();
        $schoolCategoryMode = $schoolCategoryModeService->get($id);
        return new SchoolCategoryModeResource($schoolCategoryMode);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SchoolCategoryMode  $schoolCategoryMode
     * @return \Illuminate\Http\Response
     */
    public function update(SchoolCategoryModeUpdateRequest $request, int $id)
    {
        $schoolCategoryModeService = new SchoolCategoryModeService();
        $schoolCategoryMode = $schoolCategoryModeService->update($request->all(), $id);

        return (new SchoolCategoryModeResource($schoolCategoryMode))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SchoolCategoryMode  $schoolCategoryMode
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $schoolCategoryModeService = new SchoolCategoryModeService();
        $schoolCategoryModeService->delete($id);
        return response()->json([], 204);
    }
}
