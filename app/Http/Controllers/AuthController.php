<?php

namespace App\Http\Controllers;

use App\User;
use App\Student;
use App\SchoolYear;
use Illuminate\Http\Request;
use App\Services\StudentService;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use App\Http\Resources\StudentResource;
use App\Http\Requests\StudentLoginRequest;
use App\Http\Requests\PersonnelLoginRequest;
use App\Http\Requests\StudentRegisterRequest;

class AuthController extends Controller
{
    public function login(StudentLoginRequest $request)
    {
      $data = $request->validated();

      $user = User::where('username', $data['username'])
        ->where('userable_type', 'App\Student')
        ->first();

      if ($user) {
        $http = new \GuzzleHttp\Client;
        $response = $http->post(url('/') . '/oauth/token', [
          'form_params' => [
            'grant_type' => 'password',
            'client_id' => Config::get('client.id'),
            'client_secret' => Config::get('client.secret'),
            'username' => $data['username'],
            'password' => $data['password'],
            'scope' => '',
          ],
        ]);
        return json_encode(json_decode((string) $response->getBody(), true));
      } else {
        return response()->json(['error' => 'Unauthenticated.'], 401);
      }
    }


    public function loginPersonnel(PersonnelLoginRequest $request)
    {
      $data = $request->validated();

      $user = User::where('username', $data['username'])
        ->where('userable_type', 'App\Personnel')
        ->first();

      if ($user) {
        $http = new \GuzzleHttp\Client;
        $response = $http->post(url('/') . '/oauth/token', [
          'form_params' => [
            'grant_type' => 'password',
            'client_id' => Config::get('client.id'),
            'client_secret' => Config::get('client.secret'),
            'username' => $data['username'],
            'password' => $data['password'],
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
      $user = Auth::user();
      $user->load(['userable', 'userable.photo']);

      if ($user->userable_type === 'App\\Student') {
        $user->userable->append([
          'active_admission',
          'active_application',
          'transcript'
        ]);
      }

      return new UserResource($user);
    }

    public function register(StudentRegisterRequest $request)
    {
      $studentService = new StudentService();
      $user = $studentService->register($request->all());

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
