<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentClearanceBatchCreateRequest;
use App\Http\Resources\StudentClearanceResource;
use App\Services\StudentClearanceService;
use App\StudentClearance;
use Illuminate\Http\Request;

class StudentClearanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $studentClearanceService = new StudentClearanceService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $filters = $request->except('per_page', 'paginate');
        $studentClearances = $studentClearanceService->list($isPaginated, $perPage, $filters);
        return StudentClearanceResource::collection(
            $studentClearances
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $studentClearanceService = new StudentClearanceService();
        $data = $request->except('signatories');
        $signatories = $request->signatories ?? [];
        $studentClearance = $studentClearanceService->store($data, $signatories);

        return (new StudentClearanceResource($studentClearance))
            ->response()
            ->setStatusCode(201);
    }

    public function batchStore(StudentClearanceBatchCreateRequest $request)
    {
        $studentClearanceService = new StudentClearanceService();
        $data = $request->except('signatories');
        $signatories = $request->signatories;
        $studentClearances = $studentClearanceService->batchStore($data, $signatories);
        return StudentClearanceResource::collection($studentClearances);
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $studentClearanceService = new StudentClearanceService();
        $studentClearance = $studentClearanceService->get($id);
        return new StudentClearanceResource($studentClearance);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $studentClearanceService = new StudentClearanceService();
        $data = $request->except('signatories');
        $signatories = $request->signatories ?? [];
        $studentClearance = $studentClearanceService->update($id, $data, $signatories);
        return (new StudentClearanceResource($studentClearance))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $studentClearanceService = new StudentClearanceService();
        $studentClearanceService->delete($id);
        return response()->json([], 204);
    }
}
