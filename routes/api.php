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


Route::middleware('auth:api')->group(function(){

	Route::apiResource('place', 'App\Http\Controllers\PlaceController');

	Route::apiResource('tag', 'App\Http\Controllers\TagController');

	Route::apiResource('event', 'App\Http\Controllers\EventController');

	Route::apiResource('place.event', 'App\Http\Controllers\EventController');

	Route::apiResource('project', 'App\Http\Controllers\ProjectController');

	Route::apiResource('place.project', 'App\Http\Controllers\ProjectController');

    Route::apiResource('user', 'App\Http\Controllers\UserController');

});



Route::group(['namespace' => 'App\Http\Controllers\Auth', 'as' => 'api.'], function () {

    Route::post('login', 'LoginController@login')->name('login');

    Route::post('register', 'RegisterController@register')->name('register');

    Route::group(['middleware' => ['auth:api']], function () {

        Route::get('email/verify/{hash}', 'VerificationController@verify')->name('verification.verify');

        Route::get('email/resend', 'VerificationController@resend')->name('verification.resend');

        Route::get('user', 'AuthenticationController@user')->name('user');

        Route::post('logout', 'LoginController@logout')->name('logout');


    });
});