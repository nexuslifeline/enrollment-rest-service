<?php

namespace App\Http\Controllers;

use App\ApplicationStep;
use App\Http\Resources\ApplicationStepResource;
use Illuminate\Http\Request;

class ApplicationStepController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 20;
        $applicationSteps = !$request->has('paginate') || $request->paginate === 'true'
            ? ApplicationStep::paginate($perPage)
            : ApplicationStep::all();
        return ApplicationStepResource::collection(
            $applicationSteps
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
     * @param  \App\ApplicationStep  $applicationStep
     * @return \Illuminate\Http\Response
     */
    public function show(ApplicationStep $applicationStep)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ApplicationStep  $applicationStep
     * @return \Illuminate\Http\Response
     */
    public function edit(ApplicationStep $applicationStep)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ApplicationStep  $applicationStep
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ApplicationStep $applicationStep)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ApplicationStep  $applicationStep
     * @return \Illuminate\Http\Response
     */
    public function destroy(ApplicationStep $applicationStep)
    {
        //
    }
}
