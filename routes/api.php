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
    Route::get('/auth-user', 'Api\Auth\TokenController@user');
    Route::get('/user-types', 'Api\UserTypeController@index');

    Route::post('/user-types/{id}/accesses', 'Api\UserTypeController@updateAccess');
    Route::post('/remove-tokens', 'Api\Auth\TokenController@remove');

    Route::apiResources(
    [
        'users' => 'Api\UserController',
        'modules' => 'Api\ModuleController',
        'modules.module_actions' => 'Api\ModuleActionController'
    ]);
});
