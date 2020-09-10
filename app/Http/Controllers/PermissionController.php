<?php

namespace App\Http\Controllers;

use App\Http\Resources\PermissionResource;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getPermissionsOfUserGroup($userGroupId, Request $request)
    {
        $permissionService = new PermissionService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $permissions = $permissionService->getPermissionsOfUserGroup($userGroupId, $isPaginated, $perPage);
        return PermissionResource::collection($permissions);
    }

    public function storePermissionsOfUserGroup($userGroupId, Request $request)
    {
        $permissionService = new PermissionService();
        $data = $request->permissions;
        $permissions = $permissionService->storePermissionsOfUserGroup($userGroupId, $data);
        return PermissionResource::collection($permissions);
    }
}
