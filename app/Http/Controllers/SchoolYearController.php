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
        $perPage = $request->per_page ?? 20;
        $schoolYears = !$request->has('paginate') || $request->paginate === 'true'
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
        $this->validate($request, [
            'name' => 'required|max:191',
            'description' => 'required|max:191',
            'start_date' => 'required|date'
        ]);

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
        $this->validate($request, [
            'name' => 'required|max:191',
            'description' => 'required|max:191',
            'start_date' => 'required|date'
        ]);

        $data = $request->all();

        $success = $schoolYear->update($data);

        if($success){
            return (new SchoolYearResource($schoolYear))
                ->response()
                ->setStatusCode(200);
        }
        return response()->json([], 400); // Note! add error here
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
