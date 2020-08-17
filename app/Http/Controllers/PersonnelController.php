<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonnelStoreRequest;
use App\Http\Requests\PersonnelUpdateRequest;
use App\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\PersonnelResource;
use App\Services\PersonnelService;
use Faker\Provider\ar_JO\Person;

class PersonnelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $personnelService = new PersonnelService();
        $personnels = $personnelService->index($request);
        return PersonnelResource::collection($personnels);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PersonnelStoreRequest $request)
    {
        $personnelService = new PersonnelService();
        $personnel = $personnelService->store($request);
        return (new PersonnelResource($personnel))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Personnel  $personnel
     * @return \Illuminate\Http\Response
     */
    public function show(Personnel $personnel)
    {
        $personnel->load(['user' => function($query) {
          $query->with('userGroup');
        }]);
        return new PersonnelResource($personnel);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Personnel  $personnel
     * @return \Illuminate\Http\Response
     */
    public function update(PersonnelUpdateRequest $request, Personnel $personnel)
    {
        $personnelService = new PersonnelService();
        $personnel = $personnelService->update($request, $personnel);
        return (new PersonnelResource($personnel))
        ->response()
        ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Personnel  $personnel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Personnel $personnel)
    {
        $personnelService = new PersonnelService();
        $personnelService->delete($personnel);
        return response()->json([], 204);
    }
}
