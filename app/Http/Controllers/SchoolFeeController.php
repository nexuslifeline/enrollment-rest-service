<?php

namespace App\Http\Controllers;

use App\SchoolFee;
use Illuminate\Http\Request;
use App\Http\Resources\SchoolFeeResource;

class SchoolFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 20;
        $schoolFees = !$request->has('paginate') || $request->paginate === 'true'
            ? SchoolFee::paginate($perPage)
            : SchoolFee::all();
        return SchoolFeeResource::collection(
            $schoolFees
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
            'name' => 'required|max:255',
            'description' => 'required|max:755'
        ]);

        $data = $request->all();

        $schoolFee = SchoolFee::create($data);
        return (new SchoolFeeResource($schoolFee))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SchoolFee  $schoolFee
     * @return \Illuminate\Http\Response
     */
    public function show(SchoolFee $schoolFee)
    {
        return new SchoolFeeResource($schoolFee);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SchoolFee  $schoolFee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SchoolFee $schoolFee)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'required|max:755'
        ]);

        $data = $request->all();

        $success = $schoolFee->update($data);

        if($success){
            return (new SchoolFeeResource($schoolFee))
                ->response()
                ->setStatusCode(200);
        }
        return response()->json([], 400); // Note! add error here
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SchoolFee  $schoolFee
     * @return \Illuminate\Http\Response
     */
    public function destroy(SchoolFee $schoolFee)
    {
        $schoolFee->delete();
        return response()->json([], 204);
    }
}
