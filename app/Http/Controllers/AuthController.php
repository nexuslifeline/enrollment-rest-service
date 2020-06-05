<?php

namespace App\Http\Controllers;

use App\User;
use App\Student;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use App\Http\Resources\StudentResource;

class AuthController extends Controller
{
    public function login(Request $request)
    {
      $this->validate($request, [
        'username' => 'required',
        'password' => 'required',
      ]);

      $user = User::where('username', $request->username)
        ->where('userable_type', 'App\Student')
        ->first();
  
      if ($user && Hash::check($request->password, $user->password)) {
        $http = new \GuzzleHttp\Client;
        $response = $http->post(url('/') . '/oauth/token', [
          'form_params' => [
            'grant_type' => 'password',
            'client_id' => Config::get('client.id'),
            'client_secret' => Config::get('client.secret'),
            'username' => $request->username,
            'password' => $request->password,
            'scope' => '',
          ],
        ]);
        return json_encode(json_decode((string) $response->getBody(), true));
      } else {
        return response()->json(['error' => 'Unauthenticated.'], 401);
      }
    }


    public function loginPersonnel(Request $request)
    {

      $this->validate($request, [
        'username' => 'required',
        'password' => 'required',
      ]);

      $user = User::where('username', $request->username)
        ->where('userable_type', 'App\Personnel')
        ->first();

      if ($user && Hash::check($request->password, $user->password)) {
        $http = new \GuzzleHttp\Client;
        $response = $http->post(url('/') . '/oauth/token', [
          'form_params' => [
            'grant_type' => 'password',
            'client_id' => Config::get('client.id'),
            'client_secret' => Config::get('client.secret'),
            'username' => $request->username,
            'password' => $request->password,
            'scope' => '',
          ],
        ]);
        return json_encode(json_decode((string) $response->getBody(), true));
      } else {
        return response()->json(['error' => 'Unauthenticated.'], 401);
      }
    }

    public function getAuthUser()
    {
      $user = User::with(['userable'])->where('id', Auth::id())->first();
      return new UserResource($user);
    }

    public function register(Request $request)
    {
      $this->validate($request, [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'username' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
      ]);

      $student = Student::create([
        'first_name' => $request->first_name,
        'middle_name' => $request->middle_name,
        'last_name' => $request->last_name,
        'email' => $request->username
      ]);

      $studentCategoryId = $request->student_category_id;
      if ($studentCategoryId == 1) {
        $student->admission()->create([
          'school_year_id' =>  1, // active_school_year_id
          'admission_step_id' => 1,
          'admission_status_id' => 2
        ])->transcript()->create([
          'school_year_id' => 1, // active_school_year_id
          'student_id' => $student->id,
          'student_category_id' => $studentCategoryId
        ]);
      } else {
        $student->applications()->create([
          'school_year_id' =>  1, // active_school_year_id
          'application_step_id' => 1,
          'application_status_id' => 2
        ])->transcript()->create([
          'school_year_id' => 1, // active_school_year_id
          'student_id' => $student->id,
          'student_category_id' => $studentCategoryId
        ]);
      }

      $user = $student->user()->create([
        'username' => $request->username,
        'password' => Hash::make($request->password)
      ]);
      

      return (new UserResource($user))
                ->response()
                ->setStatusCode(201);
    }

    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json('Logged out successfully', 200);
    }
}
