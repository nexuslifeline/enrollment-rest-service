<?php

namespace App\Http\Controllers;

use App\SchoolYear;
use Illuminate\Http\Request;
use App\Http\Resources\SchoolYearResource;

class SchoolYearController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->perPage ?? 20;
        $schoolYears = $request->has('paginate') || $request->paginate === 'true'
            ? SchoolYear::paginate($perPage)
            : SchoolYear::all();
        return SchoolYearResource::collection(
            $schoolYears
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
        $data = $request->all();

        $schoolYear = SchoolYear::create($data);

        return (new SchoolYearResource($schoolYear))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SchoolYear  $schoolYear
     * @return \Illuminate\Http\Response
     */
    public function show(SchoolYear $schoolYear)
    {
        return new SchoolYearResource($schoolYear);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SchoolYear  $schoolYear
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SchoolYear $schoolYear)
    {
        $data = $request->all();

        $success = $schoolYear->update($data);

        if($success){
            return (new SchoolYearResource($schoolYear))
                ->response()
                ->setStatusCode(200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SchoolYear  $schoolYear
     * @return \Illuminate\Http\Response
     */
    public function destroy(SchoolYear $schoolYear)
    {
        $schoolYear->delete();
        return response()->json([], 204);
    }
}
