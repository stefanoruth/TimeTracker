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

// Auth
Route::get('logout', 'AuthController@logout')->middleware('auth')->name('logout');
Route::get('login', 'AuthController@redirectToProvider')->middleware('guest');
Route::get('login/callback', 'AuthController@handleProviderCallback')->middleware('guest');
// App
Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('auth', 'MainController@authUser')->name('auth');
    Route::resource('time', 'TimeRegistrationController');
});
// Public
Route::get('{uri}', 'MainController@index')->name('login')->where('uri', '^(?:(?!api).*)');
