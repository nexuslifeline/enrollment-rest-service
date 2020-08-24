<?php

namespace App\Http\Controllers;

use App\Evaluation;
use App\Http\Requests\EvaluationUpdateRequest;
use App\Http\Resources\EvaluationResource;
use App\Services\EvaluationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $evaluationService = new EvaluationService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $filters = $request->except('per_page', 'paginate');
        $evaluations = $evaluationService->list($isPaginated, $perPage, $filters);

        return EvaluationResource::collection(
            $evaluations
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $evaluationService = new EvaluationService();
        $evaluation = $evaluationService->get($id);
        return new EvaluationResource($evaluation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Evaluation
     * @return \Illuminate\Http\Response
     */
    public function update(EvaluationUpdateRequest $request, int $id)
    {
        $evaluationService = new EvaluationService();
        $data = $request->except('subjects');
        $subjects = $request->subjects ?? [];
        $evaluation = $evaluationService->update($data, $subjects, $id);
        return new EvaluationResource($evaluation);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
