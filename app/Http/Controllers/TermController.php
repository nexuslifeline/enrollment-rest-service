<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TermService;
use App\Http\Resources\TermResource;

class TermController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $termService = new TermService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $filters = $request->except('per_page', 'paginate');
        $terms = $termService->list($isPaginated, $perPage, $filters);
        return TermResource::collection(
            $terms
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Term  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $termService = new TermService();
        $term = $termService->get($id);
        return new TermResource($term);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $termService = new TermService();
        $term = $termService->store($request->all());
        return (new TermResource($term))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Term  $term
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $termService = new TermService();
        $term = $termService->update($request->all(), $id);
        return (new TermResource($term))
                ->response()
                ->setStatusCode(200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Term  $term
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $termService = new TermService();
        $termService->delete($id);
        return response()->json([], 204);
    }

    public function updateCreateMultiple(Request $request)
    {
        $termService = new TermService();
        $filters = $request->except('terms');
        $arrTerms = $request->terms;
        // $arrTerms = $request->except('school_category_id', 'semester_id', 'school_year_id');

        $terms = $termService->updateCreateBulk($arrTerms, $filters);

        return (new TermResource($terms))
            ->response()
            ->setStatusCode(200);
    }
}
