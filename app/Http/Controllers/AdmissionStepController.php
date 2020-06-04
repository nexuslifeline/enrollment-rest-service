<?php

namespace App\Http\Controllers;

use App\AdmissionStep;
use App\Http\Resources\AdmissionStepResource;
use Illuminate\Http\Request;

class AdmissionStepController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->perPage ?? 20;
        $admissionStep = !$request->has('paginate') || $request->paginate === 'true'
            ? AdmissionStep::paginate($perPage)
            : AdmissionStep::all();
        return AdmissionStepResource::collection(
            $admissionStep
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
     * @param  \App\AdmissionStep  $admissionStep
     * @return \Illuminate\Http\Response
     */
    public function show(AdmissionStep $admissionStep)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AdmissionStep  $admissionStep
     * @return \Illuminate\Http\Response
     */
    public function edit(AdmissionStep $admissionStep)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AdmissionStep  $admissionStep
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdmissionStep $admissionStep)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AdmissionStep  $admissionStep
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdmissionStep $admissionStep)
    {
        //
    }
}
