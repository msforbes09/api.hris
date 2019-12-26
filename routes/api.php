<?php

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
Route::post('/get-token', 'Api\ApiTokenController@getToken')->name('getToken');
Route::post('/forgot-password', 'Api\ApiForgotPasswordController@sendResetLinkEmail');
Route::post('/reset-password', 'Api\ApiResetPasswordController@reset');

Route::middleware('auth:api')->group(function() {
    Route::post('/remove-token', 'Api\ApiTokenController@removeToken')->name('removeToken');
});