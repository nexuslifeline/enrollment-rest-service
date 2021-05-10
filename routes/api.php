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



Route::group(['prefix' => 'v1'], function () {
    // public endpoints here
    Route::post('/login', 'AuthController@login'); // for student
    Route::post('/personnel/login', 'AuthController@loginPersonnel'); // for personnels
    Route::post('/register', 'AuthController@register');

    Route::group(['middleware' => ['auth:api']], function () {
        // secured endpoints here

        // others
        Route::get('/me', 'AuthController@getAuthUser');
        Route::post('/logout', 'AuthController@logout');
        // students
        Route::resource('/students', 'StudentController');
        Route::post('/students/manual-registration', 'StudentController@manualRegister');
        Route::post('/students/{studentId}/enroll', 'StudentController@enroll');
        Route::get('/students/{studentId}/billings', 'StudentController@getBillingsOfStudent');
        Route::get('/students/{studentId}/evaluations', 'EvaluationController@getEvaluationsOfStudent');
        Route::get('/students/{studentId}/academic-records', 'AcademicRecordController@getAcademicRecordsOfStudent');
        Route::post('/students/{studentId}/photos', 'StudentPhotoController@store');
        Route::delete('/students/{studentId}/photos', 'StudentPhotoController@destroy');
        Route::get('/students/{studentId}/ledgers', 'StudentController@getLedgerOfStudent');
        Route::post('/students/{studentId}/user', 'StudentController@storeUser');
        Route::get('/students/{studentId}/student-fees', 'StudentFeeController@getStudentFeesOfStudent');
        Route::get('/students/{studentId}/academic-records', 'StudentController@getAcademicRecords');

        // student file
        Route::get('/students/{studentId}/files', 'StudentFileController@index');
        Route::post('/students/{studentId}/files', 'StudentFileController@store');
        Route::put('/students/{studentId}/files/{fileId}', 'StudentFileController@update');
        Route::get('/students/{studentId}/files/{fileId}/preview', 'StudentFileController@preview');
        Route::delete('/students/{studentId}/files/{fileId}', 'StudentFileController@destroy');

        // subjects
        Route::resource('/subjects', 'SubjectController');
        Route::get('/levels/{levelId}/subjects', 'SubjectController@getSubjectsOfLevel');
        Route::post('/levels/{levelId}/subjects', 'SubjectController@storeSubjectsOfLevel');
        Route::get('/sections/{sectionId}/scheduled-subjects', 'SubjectController@getScheduledSubjectsOfSection');
        Route::get('/subjects/{subjectId}/sections', 'SectionController@getSectionsOfSubject');
        Route::get('/sections/{sectionId}/my-scheduled-subjects', 'SubjectController@getSectionScheduledSubjectsWithStatus');

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
        Route::post('/school-categories/{schoolCategoryId}/modes', 'SchoolCategoryModeController@store');
        Route::put('/school-categories/{schoolCategoryId}/modes', 'SchoolCategoryModeController@update');
        Route::get('/user-groups/{userGroupId}/school-categories', 'SchoolCategoryController@getSchoolCategoriesOfUserGroup');
        Route::post('/user-groups/{userGroupId}/school-categories', 'SchoolCategoryController@storeSchoolCategoriesOfUserGroup');
        // users
        Route::resource('/users', 'UserController');
        Route::put('/users/{id}/update-password', 'UserController@updatePassword');
        Route::put('/users/{id}/update-email', 'UserController@updateEmail');
        // grading periods
        Route::resource('/grading-periods', 'GradingPeriodController');
        Route::post('grading-periods/update-multiple', 'GradingPeriodController@updateCreateBulk');
        // school years
        Route::patch('/school-years/{id}', 'SchoolYearController@patch');
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
        // academic records
        Route::resource('/academic-records', 'AcademicRecordController');
        Route::get('/academic-records/subjects/{subjectId}/sections/{sectionId}', 'AcademicRecordController@getGradesOfAcademicRecords');
        Route::post('academic-records/grade-batch-updates', 'AcademicRecordController@gradeBatchUpdate');
        Route::post('academic-records/finalize-grades', 'AcademicRecordController@finalizeGrades');
        Route::get('/academic-records/{academicRecordId}/student-fees', 'StudentFeeController@getStudentFeeOfAcademicRecord');
        Route::get('/academic-records/{academicRecordId}/subjects', 'AcademicRecordController@getSubjects');
        Route::put('/academic-records/{academicRecordId}/subjects/{subjectId}', 'AcademicRecordController@updateSubject');
        //Route::get('/academic-records/{academicRecordId}/subjects', 'SubjectController@getSubjectsOfAcademicRecord'); // should be removed once frontend is updated
        Route::get('/academic-records/{academicRecordId}/academic-subject-schedules', 'SubjectController@getSubjectsOfAcademicRecordWithSchedules');

        // user groups
        Route::resource('/user-groups', 'UserGroupController');
        Route::get('/user-groups/{userGroupId}/permissions', 'PermissionController@getPermissionsOfUserGroup');
        Route::post('/user-groups/{userGroupId}/permissions', 'PermissionController@storePermissionsOfUserGroup');
        // personnels
        Route::resource('/personnels', 'PersonnelController');
        Route::post('/personnels/{personnelId}/photos', 'PersonnelPhotoController@store');
        Route::delete('/personnels/{personnelId}/photos', 'PersonnelPhotoController@destroy');
        Route::get('/personnels/{personnelId}/education', 'PersonnelController@getEducationList');
        Route::post('/personnels/{personnelId}/education', 'PersonnelController@storeEducation');
        Route::put('/personnels/{personnelId}/education/{educationId}', 'PersonnelController@updateEducation');
        Route::delete('/personnels/{personnelId}/education/{educationId}', 'PersonnelController@deleteEducation');
        Route::get('/personnels/{personnelId}/employment', 'PersonnelController@getEmploymentList');
        Route::post('/personnels/{personnelId}/employment', 'PersonnelController@storeEmployment');
        Route::put('/personnels/{personnelId}/employment/{employmentId}', 'PersonnelController@updateEmployment');
        Route::delete('/personnels/{personnelId}/employment/{employmentId}', 'PersonnelController@deleteEmployment');
        Route::post('/personnels/{personnelId}/user', 'PersonnelController@storeUser');
        // payments
        Route::resource('/payments', 'PaymentController');
        Route::get('/payments/{paymentId}/files', 'PaymentFileController@index');
        Route::get('/payments/{paymentId}/files/{fileId}/preview', 'PaymentFileController@preview');
        Route::post('/payments/{paymentId}/files', 'PaymentFileController@store');
        Route::post('/payments/{paymentId}/multiple-files', 'PaymentFileController@storeMultiple');
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
        Route::post('/billings/batch-soa', 'BillingController@storeBatchSoa');
        Route::post('/billings/batch-other-billing', 'BillingController@storeBatchOtherBilling');
        Route::get('/billings/{billingId}/billing-items', 'BillingController@getBillingItemsOfBilling');
        // eWallets
        Route::resource('/e-wallet-accounts', 'EWalletAccountController');
        // bankAccounts
        Route::resource('/bank-accounts', 'BankAccountController');
        // sections
        Route::resource('/sections', 'SectionController');
        Route::get('/me/sections', 'SectionController@getSectionsOfPersonnel');
        // student fee
        Route::resource('/student-fees', 'StudentFeeController');
        // curriculum
        Route::resource('curriculums', 'CurriculumController');
        // school fee categories
        Route::resource('school-fee-categories', 'SchoolFeeCategoryController');
        // evaluations
        Route::resource('evaluations', 'EvaluationController');
        // // evaluations
        // Route::resource('evaluations', 'EvaluationController');
        // pera padala accounts
        Route::resource('pera-padala-accounts', 'PeraPadalaAccountController');
        // reports
        Route::get('assessment-form/{academicRecordId}', 'ReportController@assessmentForm');
        Route::get('requirement-list', 'ReportController@requirementList');
        Route::get('statement-of-account/{billingId}', 'ReportController@statementOfAccount');
        Route::get('collection-report', 'ReportController@collectionReport');
        Route::get('student-ledger/{studentId}', 'ReportController@studentLedger');
        Route::get('registration-form/{academicRecordId}', 'ReportController@registrationForm');
        Route::get('transcript-record/{transcriptRecordId}', 'ReportController@transcriptRecord');
        Route::get('enrolled-list', 'ReportController@enrolledList');
        // permission-groups
        Route::resource('permission-groups', 'PermissionGroupController');

        Route::get('organization-settings/{organizationSettingId}', 'OrganizationSettingController@show');
        Route::put('organization-settings/{organizationSettingId}', 'OrganizationSettingController@update');
        Route::post('/organization-settings/{organizationSettingId}/logos', 'OrganizationLogoController@store');
        Route::delete('/organization-settings/{organizationSettingId}/logos', 'OrganizationLogoController@destroy');

        //terms
        Route::resource('terms', 'TermController');
        Route::get('students/{studentId}/terms', 'TermController@getStudentFeeTermsOfStudent');
        Route::post('terms/update-multiple', 'TermController@updateCreateMultiple');

        //transcript-records
        Route::resource('/transcript-records', 'TranscriptRecordController');
        Route::get('/transcript-records/{transcriptRecordId}/subjects', 'SubjectController@getSubjectsOfTranscriptRecord');
        Route::get('/transcript-records/{transcriptRecordId}/unscheduled-subjects', 'SubjectController@getSectionUnscheduledSubjects');

        //document types
        Route::resource('/document-types', 'DocumentTypeController');

        //approval counts
        Route::get('/approval-count', 'AcademicRecordController@getPendingApprovalCount');

        //requirements
        Route::resource('requirements', 'RequirementController');
        Route::post('requirements/update-create-multiple/{schoolCategoryId}', 'RequirementController@updateCreateMultiple');

        //general settings
        Route::get('general-settings/{generalSettingId}', 'GeneralSettingController@show');
        Route::put('general-settings/{generalSettingId}', 'GeneralSettingController@update');
        Route::resource('student-grades', 'StudentGradeController');
        Route::get('student-grades/personnels/list', 'StudentGradeController@studentGradePersonnels');
        Route::post('student-grades/{personnelId}/{sectionId}/{subjectId}', 'StudentGradeController@acceptStudentGrade');
        Route::post('student-grades/batch-update', 'StudentGradeController@batchUpdate');

        //student clearances
        Route::resource('/student-clearances','StudentClearanceController');
        Route::get('/signatories', 'StudentClearanceController@signatoriesList');
        Route::post('/student-clearances/batch-store', 'StudentClearanceController@batchStore');
        Route::post('/signatories/update', 'StudentClearanceController@signatoriesUpdate');
    });
    // Route::get('requirement-list', 'ReportController@requirementList');
    // Route::get('statement-of-account/{billingId}', 'ReportController@statementOfAccount');
    // Route::get('transcript-record/{transcriptRecordId}', 'ReportController@transcriptRecord');
});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
