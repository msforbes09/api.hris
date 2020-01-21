<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Authentication Routes...
Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/home', 'HomeController@index')->name('home');

/*Route::get('/routes', function() {
    $routes = [];
    foreach (\Route::getRoutes()->getIterator() as $route){
        if (strpos($route->uri, 'api') !== false && !Str::contains($route->uri, 'telescope')){
            $routes[] = $route->uri;
        }
    }
    dd($routes);
});*/

Route::get('/test', 'TestController@test');
