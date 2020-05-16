<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
      $http = new \GuzzleHttp\Client;
      $response = $http->post(url('/') . '/oauth/token', [
        'form_params' => [
          'grant_type' => 'password',
          'client_id' => '2',
          'client_secret' => 'Z0kWGDOrX0v57U9kwueti4OdcFQpIPSrIKhdrmLt',
          'username' => $request->username,
          'password' => $request->password,
          'scope' => '',
        ],
      ]);
      return json_encode(json_decode((string) $response->getBody(), true));
    }
}
