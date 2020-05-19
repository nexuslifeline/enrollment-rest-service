<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;

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
}
