<?php

namespace App\Http\Controllers;

use App\Http\Resources\RequirementResource;
use App\Requirement;
use App\Services\RequirementService;
use Illuminate\Http\Request;

class RequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $requirementService = new RequirementService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $filters = $request->except('per_page', 'paginate');
        $requirements = $requirementService->list($isPaginated, $perPage, $filters);
        return RequirementResource::collection(
            $requirements
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Requirement  $requirement
     * @return \Illuminate\Http\Response
     */
    public function show(Requirement $requirement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Requirement  $requirement
     * @return \Illuminate\Http\Response
     */
    public function edit(Requirement $requirement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Requirement  $requirement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Requirement $requirement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Requirement  $requirement
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $requirementService = new RequirementService();
        $requirementService->delete($id);
        return response()->json([], 204);
    }

    public function updateCreateMultiple(int $schoolCategoryId, Request $request)
    {
        $requirementService = new RequirementService();
        $data = $request->all();
        // $arrTerms = $request->except('school_category_id', 'semester_id', 'school_year_id');

        $requirements = $requirementService->updateCreateMultiple($schoolCategoryId, $data);

        return (new RequirementResource($requirements))
            ->response()
            ->setStatusCode(200);
    }
}
