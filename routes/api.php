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



Route::group(['prefix' => 'v1'], function()
{
    // public endpoints here
    Route::post('/login', 'AuthController@login'); // for student
    Route::post('/personnel/login', 'AuthController@loginPersonnel'); // for personnels
    Route::post('/register', 'AuthController@register');

    Route::group(['middleware' => ['auth:api']], function() {
        // secured endpoints here
        Route::resource('/students', 'StudentController');
        Route::get('/me', 'AuthController@getAuthUser');
        Route::post('/logout', 'AuthController@logout');
        Route::resource('/subjects', 'SubjectController');
        Route::get('/levels/{levelId}/subjects', 'SubjectController@getSubjectsOfLevel');
        Route::resource('/levels', 'LevelController');
        Route::resource('/courses', 'CourseController');
        Route::get('/levels/{levelId}/courses', 'CourseController@getCoursesOfLevel');
        Route::resource('/school-categories', 'SchoolCategoryController');
        Route::get('/school-categories/{schoolCategoryId}/courses', 'CourseController@getCoursesOfSchoolCategory');
        Route::get('/school-categories/{schoolCategoryId}/levels', 'LevelController@getLevelsOfSchoolCategory');
        Route::resource('/school-years', 'SchoolYearController');
        Route::resource('/semesters', 'SemesterController');

        Route::get('/studentinfo', 'StudentController@getStudentInfo');
        Route::put('/studentinfo/{child}/{student}', 'StudentController@updateStudentInfo');
    });
});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


