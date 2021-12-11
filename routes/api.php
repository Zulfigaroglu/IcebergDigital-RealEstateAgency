<?php

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
Route::group(['namespace' => 'Api'], function () {
    Route::post('/register', 'AuthController@register')->name('auth.register');;
    Route::post('/login', 'AuthController@login')->name('auth.login');

    Route::group([ 'middleware' => 'auth:api'], function () {
        Route::get('/me', 'UserController@me')->name('user.me');
        Route::post('/logout', 'AuthController@logout')->name('auth.logout');


        Route::resource('users', 'UserController')
            ->only(['index', 'show', 'update', 'destroy'])->names('user');

        Route::resource('appointments', 'AppointmentController')->names('appointment');
    });
});
