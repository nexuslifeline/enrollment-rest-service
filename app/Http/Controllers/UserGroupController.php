<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserGroupStoreRequest;
use App\Http\Requests\UserGroupUpdateRequest;
use App\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\UserGroupResource;
use App\Services\UserGroupService;

class UserGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userGroupService = new UserGroupService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $userGroups = $userGroupService->list($isPaginated, $perPage);
        return UserGroupResource::collection($userGroups);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserGroupStoreRequest $request)
    {
        $userGroupService = new UserGroupService();
        $userGroup = $userGroupService->store($request->all());
        return (new UserGroupResource($userGroup))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserGroup  $userGroup
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $userGroupService = new UserGroupService();
        $userGroup = $userGroupService->get($id);
        return new UserGroupResource($userGroup);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserGroup  $userGroup
     * @return \Illuminate\Http\Response
     */
    public function update(UserGroupUpdateRequest $request, int $id)
    {
        $userGroupService = new UserGroupService();
        $userGroup = $userGroupService->update($request->all(), $id);
       
        return (new UserGroupResource($userGroup))
        ->response()
        ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserGroup  $userGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $userGroupService = new UserGroupService();
        $userGroupService->delete($id);
        return response()->json([], 204);
    }
}
