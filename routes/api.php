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

        // others
        Route::get('/me', 'AuthController@getAuthUser');
        Route::post('/logout', 'AuthController@logout');
        // students
        Route::resource('/students', 'StudentController');
        Route::post('/students/{studentId}/photos', 'StudentPhotoController@store');
        Route::delete('/students/{studentId}/photos', 'StudentPhotoController@destroy');
        // subjects
        Route::resource('/subjects', 'SubjectController');
        Route::get('/levels/{levelId}/subjects', 'SubjectController@getSubjectsOfLevel');
        Route::post('/levels/{levelId}/subjects', 'SubjectController@storeSubjectsOfLevel');
        Route::get('/transcripts/{transcriptId}/subjects', 'SubjectController@getSubjectsOfTranscript');
        // levels
        Route::resource('/levels', 'LevelController');
        Route::get('/school-categories/{schoolCategoryId}/levels', 'LevelController@getLevelsOfSchoolCategory');
        Route::get('/school-categories/{schoolCategoryId}/courses', 'CourseController@getCoursesOfSchoolCategory');
        // courses
        Route::resource('/courses', 'CourseController');
        Route::get('/courses/{courseId}/levels', 'LevelController@getLevelsOfCourses');
        Route::get('/levels/{levelId}/courses', 'CourseController@getCoursesOfLevel');
        // school categories
        Route::resource('/school-categories', 'SchoolCategoryController');
        // school years
        Route::resource('/school-years', 'SchoolYearController');
        // semesters
        Route::resource('/semesters', 'SemesterController');
        // school fees
        Route::resource('/school-fees', 'SchoolFeeController');
        // rate sheets
        Route::resource('/rate-sheets', 'RateSheetController');
        // application steps
        Route::resource('/application-steps', 'ApplicationStepController');
        // admission steps
        Route::resource('/admission-steps', 'ApplicationStepController');
        // admissions
        Route::resource('/admissions', 'AdmissionController');
        Route::get('/admissions/{admissionId}/files', 'AdmissionFileController@index');
        Route::post('/admissions/{admissionId}/files', 'AdmissionFileController@store');
        Route::put('/admissions/{admissionId}/files/{fileId}', 'AdmissionFileController@update');
        Route::get('/admissions/{admissionId}/files/{fileId}/preview', 'AdmissionFileController@preview');
        Route::delete('/admissions/{admissionId}/files/{fileId}', 'AdmissionFileController@destroy');
        // transcript
        Route::resource('/transcripts', 'TranscriptController');
        // user groups
        Route::resource('/user-groups', 'UserGroupController');
        // user
        Route::resource('/personnels', 'PersonnelController');
        // payments
        Route::resource('/payments', 'PaymentController');
        Route::get('/payments/{paymentId}/files', 'PaymentFileController@index');
        Route::get('/payments/{paymentId}/files/{fileId}/preview', 'PaymentFileController@preview');
        Route::post('/payments/{paymentId}/files', 'PaymentFileController@store');
        Route::put('/payments/{paymentId}/files/{fileId}', 'PaymentFileController@update');
        Route::delete('/payments/{paymentId}/files/{fileId}', 'PaymentFileController@destroy');
        // payment receipt files
        Route::get('/payments/{paymentId}/payment-receipt-files', 'PaymentReceiptFileController@index');
        Route::post('/payments/{paymentId}/payment-receipt-files', 'PaymentReceiptFileController@store');
        Route::put('/payments/{paymentId}/payment-receipt-files/{fileId}', 'PaymentReceiptFileController@update');
        Route::get('/payments/{paymentId}/payment-receipt-files/{fileId}/preview', 'PaymentReceiptFileController@preview');
        Route::delete('/payments/{paymentId}/payment-receipt-files/{fileId}', 'PaymentReceiptFileController@destroy');
        // departments
        Route::resource('/departments', 'DepartmentController');
        // billings
        Route::resource('/billings', 'BillingController');
        // eWallets
        Route::resource('/e-wallet-accounts', 'EWalletAccountController');
        // bankAccounts
        Route::resource('/bank-accounts', 'BankAccountController');
        // sections
        Route::resource('/sections', 'SectionController');
        // student fee
        Route::get('/transcripts/{transcriptId}/student-fees', 'StudentFeeController@getStudentFeeOfTranscript');
        // curriculum
        Route::resource('curriculums', 'CurriculumController');
        // school fee categories
        Route::resource('school-fee-categories', 'SchoolFeeCategoryController');
        // evaluations
        Route::resource('evaluations', 'EvaluationController');
        Route::get('/evaluations/{evaluationId}/subjects', 'SubjectController@getSubjectsOfEvaluation');
        // evaluation file
        Route::get('/evaluations/{evaluationId}/files', 'EvaluationFileController@index');
        Route::post('/evaluations/{evaluationId}/files', 'EvaluationFileController@store');
        Route::put('/evaluations/{evaluationId}/files/{fileId}', 'EvaluationFileController@update');
        Route::get('/evaluations/{evaluationId}/files/{fileId}/preview', 'EvaluationFileController@preview');
        Route::delete('/evaluations/{evaluationId}/files/{fileId}', 'EvaluationFileController@destroy');
        // evaluations
        Route::resource('evaluations', 'EvaluationController');

    });
});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


