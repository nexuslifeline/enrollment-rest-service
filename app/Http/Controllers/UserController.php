<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserEmailUpdateRequest;
use App\Http\Requests\UserPasswordUpdateRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userService = new UserService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $userGroups = $userService->list($isPaginated, $perPage);
        return UserResource::collection($userGroups);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        $userService = new UserService();
        $user = $userService->store($request->all());
        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $userService = new UserService();
        $user = $userService->get($id);
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, int $id)
    {
        $userService = new UserService();
        $user = $userService->update($request->all(), $id);

        return (new UserResource($user))
        ->response()
        ->setStatusCode(200);
    }

    /**
     * Update the password of specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(UserPasswordUpdateRequest $request, int $id)
    {
        $userService = new UserService();
        $password = $request->only('password');
        $data = ['password' => Hash::make($password['password'])];
        // return $data['password'];
        $user = $userService->update($data, $id);

        return (new UserResource($user))
        ->response()
        ->setStatusCode(200);
    }

     /**
     * Update the password of specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updateEmail(UserEmailUpdateRequest $request, int $id)
    {
        $userService = new UserService();
        $user = $userService->update($request->only(['username', 'user_group_id']), $id);

        return (new UserResource($user))
        ->response()
        ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $userService = new UserService();
        $userService->delete($id);
        return response()->json([], 204);
    }
}
