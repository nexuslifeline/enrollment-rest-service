<?php

namespace App\Http\Controllers;

use App\Level;
use Illuminate\Http\Request;
use App\Http\Resources\LevelResource;
use App\Http\Resources\SchoolCategoryResource;
use App\SchoolCategory;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->perPage ?? 20;
        $levels = !$request->has('paginate') || $request->paginate === 'true'
            ? Level::paginate($perPage)
            : Level::all();
        return LevelResource::collection(
            $levels
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
        //
        try{
            $data = $request->all();

            $level = Level::create($data);
            
            return (new LevelResource($level))
                ->response()
                ->setStatusCode(201);
        }catch (Throwable $e) {
            return response()->json([], 400); // Note! add error here
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Level  $level
     * @return \Illuminate\Http\Response
     */
    public function show(Level $level)
    {
        return new LevelResource($level);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Level  $level
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Level $level)
    {
        try{
            $data = $request->all();

            $success = $level->update($data);

            if ($success) {
                return (new LevelResource($level))
                    ->response() 
                    ->setStatusCode(200);
            }
        }catch (Throwable $e) {
            return response()->json([], 400); // Note! add error here
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Level  $level
     * @return \Illuminate\Http\Response
     */
    public function destroy(Level $level)
    {
        $level->delete();
        return response()->json([], 204);
    }

    public function getSubjects(Request $request, $level_id)
    {
        return Subject::where('level_id', $level_id)->get();
    }

    public function getLevelsOfSchoolCategory($schoolCategoryId, Request $request)
    {
        $perPage = $request->perPage ?? 20;
        $levels = SchoolCategory::find($schoolCategoryId)->levels();

        $levels = !$request->has('paginate') || $request->paginate === 'true'
            ? $levels->paginate($perPage)
            : $levels->get();

        return SchoolCategoryResource::collection($levels);
    }    
}
