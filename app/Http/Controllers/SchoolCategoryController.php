<?php

namespace App\Http\Controllers;

use App\Http\Resources\SchoolCategoryResource;
use App\SchoolCategory;
use Illuminate\Http\Request;

class SchoolCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 20;
        $schoolCategories = !$request->has('paginate') || $request->paginate === 'true'
            ? SchoolCategory::paginate($perPage)
            : SchoolCategory::all();

        return SchoolCategoryResource::collection(
            $schoolCategories
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
        $schoolCategory = SchoolCategory::create($data);

        return (new SchoolCategoryResource($schoolCategory))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SchoolCategory  $schoolCategory
     * @return \Illuminate\Http\Response
     */
    public function show(SchoolCategory $schoolCategory)
    {
        return new SchoolCategoryResource($schoolCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SchoolCategory  $schoolCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SchoolCategory $schoolCategory)
    {
        $this->validate($request, [
            'name' => 'required|max:191',
            'description' => 'required|max:191'
        ]);
        
        $data = $request->all();
        $success = $schoolCategory->update($data);

        if ($success) {
            return new SchoolCategoryResource($schoolCategory);
        }
        return response()->json([], 400); // Note! add error here
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SchoolCategory  $schoolCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(SchoolCategory $schoolCategory)
    {
        $schoolCategory->delete();
        return response()->json([], 204);
    }
}
