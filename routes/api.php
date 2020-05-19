<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// public endpoints here
Route::post('/login', 'AuthController@login'); // for student
Route::post('/personnel/login', 'AuthController@loginPersonnel'); // for personnels

Route::middleware('auth:api')->get('/user', function (Request $request) {
    // secured endpoints here
    return $request->user();
});


