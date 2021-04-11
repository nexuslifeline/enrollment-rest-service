<?php

namespace App\Http\Controllers;

use App\Personnel;
use Illuminate\Http\Request;
use Faker\Provider\ar_JO\Person;
use App\Services\PersonnelService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\PersonnelResource;
use App\Http\Requests\PersonnelStoreRequest;
use App\Http\Requests\PersonnelUpdateRequest;
use App\Http\Requests\PersonnelEducationStoreRequest;
use App\Http\Requests\PersonnelEducationUpdateRequest;
use App\Http\Requests\PersonnelEmploymentStoreRequest;
use App\Http\Requests\PersonnelEmploymentUpdateRequest;

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
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $filters = $request->except('per_page', 'paginate');
        $personnels = $personnelService->list($isPaginated, $perPage, $filters);
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
        $data = $request->except('user');
        $user = $request->user ?? [];
        $personnel = $personnelService->store($data, $user);
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
    public function show(int $id)
    {
        $personnelService = new PersonnelService();
        $personnel = $personnelService->get($id);
        return new PersonnelResource($personnel);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Personnel  $personnel
     * @return \Illuminate\Http\Response
     */
    public function update(PersonnelUpdateRequest $request, int $id)
    {
        $personnelService = new PersonnelService();
        $data = $request->except('user');
        $user = $request->user ?? [];
        $personnel = $personnelService->update($data, $user, $id);
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
    public function destroy(int $id)
    {
        $personnelService = new PersonnelService();
        $personnelService->delete($id);
        return response()->json([], 204);
    }

    public function getEducationList(Request $request, int $id)
    {
        $personnelService = new PersonnelService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $personnel = $personnelService->getEducationList($id, $isPaginated, $perPage);
        return (new PersonnelResource($personnel))
        ->response()
        ->setStatusCode(200);
    }

    public function storeEducation(PersonnelEducationStoreRequest $request, int $id)
    {
        $personnelService = new PersonnelService();
        $data = $request->all();
        $personnel = $personnelService->storeEducation($id, $data);
        return (new PersonnelResource($personnel))
            ->response()
            ->setStatusCode(201);
    }

    public function updateEducation(PersonnelEducationUpdateRequest $request, int $id, int $educationId)
    {
        $personnelService = new PersonnelService();
        $data = $request->all();
        $personnel = $personnelService->updateEducation($id, $educationId, $data);
        return (new PersonnelResource($personnel))
            ->response()
            ->setStatusCode(200);
    }

    public function deleteEducation(int $id, int $educationId)
    {
        $personnelService = new PersonnelService();
        $personnelService->deleteEducation($id, $educationId);
        return response()->json([], 204);
    }

    public function getEmploymentList(Request $request, int $id)
    {
        $personnelService = new PersonnelService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $personnel = $personnelService->getEmploymentList($id, $isPaginated, $perPage);
        return (new PersonnelResource($personnel))
        ->response()
        ->setStatusCode(200);
    }

    public function storeEmployment(PersonnelEmploymentStoreRequest $request, int $id)
    {
        $personnelService = new PersonnelService();
        $data = $request->all();
        $personnel = $personnelService->storeEmployment($id, $data);
        return (new PersonnelResource($personnel))
            ->response()
            ->setStatusCode(201);
    }

    public function updateEmployment(PersonnelEmploymentUpdateRequest $request, int $id, int $employmentId)
    {
        $personnelService = new PersonnelService();
        $data = $request->all();
        $personnel = $personnelService->updateEmployment($id, $employmentId, $data);
        return (new PersonnelResource($personnel))
            ->response()
            ->setStatusCode(200);
    }

    public function deleteEmployment(int $id, int $employmentId)
    {
        $personnelService = new PersonnelService();
        $personnelService->deleteEmployment($id, $employmentId);
        return response()->json([], 204);
    }
}
