<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which`
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:api')->group(function() {
    require_once __DIR__ . '/api/authenticated.php';
    require_once __DIR__ . '/api/resources.php';
});

// Authentication Routes
Route::post('/get-token', 'Api\Auth\AuthController@createToken');
Route::post('/forgot-password', 'Api\Auth\ForgotPasswordController@sendResetLinkEmail');
Route::post('/reset-password', 'Api\Auth\ResetPasswordController@reset');

// Download and Exports Routes
Route::get('/applicants/template', 'Api\ApplicantController@template');
Route::get('/applicants/export', 'Api\ApplicantController@export');

// Other Rotues
Route::get('/webhook', 'Api\FbWebhookController@index');
Route::post('/webhook', 'Api\FbWebhookController@event');
