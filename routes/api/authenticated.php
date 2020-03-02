<?php
// Tool Routes
Route::prefix('tools')->group(function () {
    Route::get('user-management', 'Api\ToolController@userManagement');
    Route::get('module-management', 'Api\ToolController@moduleManagement');
    Route::get('client-management', 'Api\ToolController@clientManagement');
    Route::get('applicant-management', 'Api\ToolController@applicantManagement');
    Route::get('sms-management', 'Api\ToolController@smsManagement');
});

// Authenticated User Route
Route::get('/auth-user', 'Api\Auth\AuthController@user');
Route::post('/remove-tokens', 'Api\Auth\AuthController@removeTokens');

// User Type Routes
Route::get('/user-types', 'Api\UserTypeController@index');
Route::get('/user-types/{userType}', 'Api\UserTypeController@show');
Route::post('/user-types/{userType}/accesses', 'Api\UserTypeController@updateAccess');

// Auditing Routes
Route::get('/user-audits/{user?}/', 'Api\AuditController@index');

// Clients Routes
Route::post('/clients/{id}/restore', 'Api\ClientController@restore');
Route::post('/client-branches/{id}/restore', 'Api\ClientBranchController@restore');
Route::post('/client-positions/{id}/restore', 'Api\ClientPositionController@restore');

// Applicants Routes
Route::post('/applicants/import', 'Api\ApplicantController@import');
Route::post('/applicant-check', 'Api\ApplicantController@applicantCheck');

// SMS Routes
Route::get('/sms', 'Api\SmsController@index');
Route::get('/sms/{sms}', 'Api\SmsController@recipients');
Route::post('/sms-send', 'Api\SmsController@send');
Route::get('/sms-server', 'Api\SmsController@server');
Route::get('/sms-info', 'Api\SmsController@info');
Route::get('/sms-pending', 'Api\SmsController@pending');