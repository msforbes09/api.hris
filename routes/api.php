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
Route::post('/get-token', 'Api\Auth\ApiTokenController@getToken')->name('getToken');
Route::post('/forgot-password', 'Api\Auth\ApiForgotPasswordController@sendResetLinkEmail');
Route::post('/reset-password', 'Api\Auth\ApiResetPasswordController@reset');

Route::get('/user-types', 'Api\UserTypeController@index');

Route::middleware('auth:api')->group(function() {
    Route::get('/auth-user', 'Api\Auth\ApiTokenController@authenticatedUser');
    Route::post('/remove-tokens', 'Api\Auth\ApiTokenController@removeToken')->name('removeToken');

    Route::resources(
    [
        'users' => 'Api\UserController',
        'modules' => 'Api\ModuleController',
        'modules.module_actions' => 'Api\ModuleActionController'
    ]);
});
