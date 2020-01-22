<?php

use App\Contracts\IUser;
use Illuminate\Http\Request;

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
Route::post('/get-token', 'Api\Auth\TokenController@get')->name('getToken');
Route::post('/forgot-password', 'Api\Auth\ForgotPasswordController@sendResetLinkEmail');
Route::post('/reset-password', 'Api\Auth\ResetPasswordController@reset');

Route::middleware('auth:api')->group(function() {
    Route::prefix('tools')->group(function () {
        Route::get('user-management', 'Api\ToolController@userManagement');
        Route::get('module-management', 'Api\ToolController@moduleManagement');
        Route::get('client-management', 'Api\ToolController@clientManagement');
    });

    Route::get('/auth-user', 'Api\Auth\TokenController@user');
    Route::get('/user-types', 'Api\UserTypeController@index');
    Route::get('/user-types/{userType}', 'Api\UserTypeController@show');

    Route::post('/user-types/{userType}/accesses', 'Api\UserTypeController@updateAccess');
    Route::post('/remove-tokens', 'Api\Auth\TokenController@remove');
    Route::post('/applicant-check', 'Api\ApplicantController@applicantCheck');

    Route::apiResources(
    [
        'users' => 'Api\UserController',
        'clients' => 'Api\ClientController',
        'clients.branches' => 'Api\ClientBranchController',
        'clients.positions' => 'Api\ClientPositionController',
        'keywords' => 'Api\KeywordController',
        'applicants' => 'Api\ApplicantController',
        'applicants.families' => 'Api\ApplicantFamilyController',
        'applicants.educations' => 'Api\ApplicantEducationController',
        'applicants.employments' => 'Api\ApplicantEmploymentController',
        'applicants.applications' => 'Api\ApplicationController',
        'applications' => 'Api\ApplicationController'
    ]);
});
