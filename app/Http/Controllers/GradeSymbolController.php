<?php

namespace App\Http\Controllers;

use App\GradeSymbol;
use App\Http\Requests\GradeSymbolStoreRequest;
use App\Http\Requests\GradeSymbolUpdateRequest;
use App\Http\Resources\GradeSymbolResource;
use App\Services\GradeSymbolService;
use Illuminate\Http\Request;

class GradeSymbolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $gradeSymbolService = new GradeSymbolService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $gradeSymbols = $gradeSymbolService->list($isPaginated, $perPage);
        return GradeSymbolResource::collection(
            $gradeSymbols
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GradeSymbolStoreRequest $request)
    {
        $gradeSymbolService= new GradeSymbolService();
        $gradeSymbol = $gradeSymbolService->store($request->all());
        return (new GradeSymbolResource($gradeSymbol))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GradeSymbol  $gradeSymbol
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $gradeSymbolService= new GradeSymbolService();
        $gradeSymbol = $gradeSymbolService->get($id);
        return new GradeSymbolResource($gradeSymbol);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GradeSymbol  $gradeSymbol
     * @return \Illuminate\Http\Response
     */
    public function update(GradeSymbolUpdateRequest $request, int $id)
    {
        $gradeSymbolService= new GradeSymbolService();
        $gradeSymbol = $gradeSymbolService->update($request->all(), $id);

        return (new GradeSymbolResource($gradeSymbol))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GradeSymbol  $gradeSymbol
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $gradeSymbolService= new GradeSymbolService();
        $gradeSymbolService->delete($id);
        return response()->json([], 204);
    }
}
