<?php

namespace App\Http\Controllers;

use App\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\UserGroupResource;

class UserGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 20;
        $userGroups = !$request->has('paginate') || $request->paginate === 'true'
            ? UserGroup::paginate($perPage)
            : UserGroup::all();
        return UserGroupResource::collection(
            $userGroups
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
          'code' => 'required|max:191',
          'name' => 'required|max:191',
          'description' => 'required|max:191'
        ]);

        $data = $request->all();

        $userGroup = UserGroup::create($data);

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
    public function show(UserGroup $userGroup)
    {
        return new UserGroupResource($userGroup);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserGroup  $userGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserGroup $userGroup)
    {
        $this->validate($request, [
          'code' => 'required|max:191',
          'name' => 'required|max:191',
          'description' => 'required|max:191'
        ]);

        $data = $request->all();

        $success = $userGroup->update($data);

        if($success){
            return (new UserGroupResource($userGroup))
            ->response()
            ->setStatusCode(200);
        }
        return response()->json([], 400); // Note! add error here
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserGroup  $userGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserGroup $userGroup)
    {
        $userGroup->delete();
        return response()->json([], 204);
    }
}
