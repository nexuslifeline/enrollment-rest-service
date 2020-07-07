<?php

namespace App\Http\Controllers;

use App\SchoolFeeCategory;
use Illuminate\Http\Request;
use App\Http\Resources\SchoolFeeCategoryResource;

class SchoolFeeCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 20;
        $schoolFeeCategories = !$request->has('paginate') || $request->paginate === 'true'
            ? SchoolFeeCategory::paginate($perPage)
            : SchoolFeeCategory::all();
        return SchoolFeeCategoryResource::collection(
            $schoolFeeCategories
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

        $schoolFeeCategories = SchoolFeeCategory::create($data);
        return (new SchoolFeeCategoryResource($schoolFeeCategories))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SchoolFeeCategory  $schoolFeeCategory
     * @return \Illuminate\Http\Response
     */
    public function show(SchoolFeeCategory $schoolFeeCategory)
    {
        return new SchoolFeeCategoryResource($schoolFeeCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SchoolFeeCategory  $schoolFeeCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SchoolFeeCategory $schoolFeeCategory)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'required|max:755'
        ]);

        $data = $request->all();

        $success = $schoolFeeCategory->update($data);

        if($success){
            return (new SchoolFeeCategoryResource($schoolFeeCategory))
                ->response()
                ->setStatusCode(200);
        }
        return response()->json([], 400); // Note! add error here
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SchoolFeeCategory  $schoolFeeCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(SchoolFeeCategory $schoolFeeCategory)
    {
        $schoolFeeCategory->delete();
        return response()->json([], 204);
    }
}