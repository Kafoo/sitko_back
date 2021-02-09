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

Route::get('info', function(){echo phpinfo();});


Route::group(['namespace' => 'App\Http\Controllers\Auth', 'as' => 'api.'], function () {

    Route::post('login', 'LoginController@login')->name('login');

    Route::post('register', 'RegisterController@register')->name('register');

    Route::group(['middleware' => ['auth:api']], function () {

        Route::get('email/verify/{hash}', 'VerificationController@verify')->name('verification.verify');

        Route::get('email/resend', 'VerificationController@resend')->name('verification.resend');

        Route::get('auth', 'AuthenticationController@getauth')->name('auth');

        Route::post('logout', 'LoginController@logout')->name('logout');

    });





});

Route::middleware('auth:api')->group(function(){

	Route::apiResource('place', 'App\Http\Controllers\PlaceController');

	Route::apiResource('tag', 'App\Http\Controllers\TagController');

	Route::apiResource('caldate', 'App\Http\Controllers\CaldateController');

	Route::apiResource('place.caldate', 'App\Http\Controllers\CaldateController');

	Route::apiResource('project', 'App\Http\Controllers\ProjectController');

	Route::apiResource('place.project', 'App\Http\Controllers\ProjectController');

	Route::apiResource('event', 'App\Http\Controllers\EventController');

	Route::apiResource('place.event', 'App\Http\Controllers\EventController');

    Route::apiResource('user', 'App\Http\Controllers\UserController');

    Route::apiResource('tags_category', 'App\Http\Controllers\Tags_categoryController');

});
