<?php

namespace App\Http\Controllers;

use App\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\PersonnelResource;

class PersonnelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 20;
        $personnels = !$request->has('paginate') || $request->paginate === 'true'
            ? Personnel::paginate($perPage)
            : Personnel::all();

        $personnels->load(['user' => function($query) {
          $query->with('userGroup');
        }]);
        return PersonnelResource::collection(
            $personnels
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
          'first_name' => 'required|string|max:255',
          'last_name' => 'required|string|max:255',
          'username' => 'required|string|email|max:255|unique:users',
          'password' => 'required|string|min:6|confirmed',
          'user_group_id' => 'required',
          'birth_date' => 'required|date'
        ]);

        $data = $request->except('username', 'password', 'password_confirmation', 'user_group_id');

        $personnel = Personnel::create($data);

        $user = $personnel->user()->create([
          'username' => $request->username,
          'user_group_id' => $request->user_group_id,
          'password' => Hash::make($request->password)
        ]);

        $personnel->load(['user' => function($query) {
          $query->with('userGroup');
        }]);

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
        return new UserGroupResource($personnel);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Personnel  $personnel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Personnel $personnel)
    {
        $this->validate($request, [
          'first_name' => 'required|string|max:255',
          'last_name' => 'required|string|max:255',
          'username' => 'required|string|email|max:255|unique:users,username,'.$personnel->id.',userable_id',
          // 'password' => 'string|min:6|confirmed',
          'user_group_id' => 'required',
          'birth_date' => 'required|date'
        ]);

        $data = $request->except('username', 'password', 'password_confirmation', 'user_group_id');

        $success = $personnel->update($data);

        $user = $personnel->user()->update([
          'username' => $request->username,
          'user_group_id' => $request->user_group_id,
          //'password' => Hash::make($request->password)
        ]);

        $personnel->load(['user' => function($query) {
          $query->with('userGroup');
        }]);

        if($success){
            return (new PersonnelResource($personnel))
            ->response()
            ->setStatusCode(200);
        }
        return response()->json([], 400); // Note! add error here
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Personnel  $personnel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Personnel $personnel)
    {
        $personnel->delete();
        // $personnel->user()->delete();
        return response()->json([], 204);
    }
}
