<?php

namespace App\Http\Controllers;

use App\Semester;
use Illuminate\Http\Request;
use App\Services\SemesterService;
use App\Http\Resources\SemesterResource;

class SemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $semesterService = new SemesterService();
        $filters = $request->except('paginate','per_page');
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $semesters = $semesterService->list($isPaginated, $perPage, $filters);
        return SemesterResource::collection(
            $semesters
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
            'description' => 'required|max:191'
        ]);

        $data = $request->all();

        $semester = Semester::create($data);

        return (new SemesterResource($semester))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Semester  $semester
     * @return \Illuminate\Http\Response
     */
    public function show(Semester $semester)
    {
        return new SemesterResource($semester);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Semester  $semester
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Semester $semester)
    {
        $this->validate($request, [
            'name' => 'required|max:191',
            'description' => 'required|max:191'
        ]);

        $data = $request->all();

        $success = $semester->update($data);

        if($success){
            return (new SemesterResource($semester))
                ->response()
                ->setStatusCode(200);
        }
        return response()->json([], 400); // Note! add error here
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Semester  $semester
     * @return \Illuminate\Http\Response
     */
    public function destroy(Semester $semester)
    {
        $semester->delete();
        return response()->json([], 204);
    }
}
