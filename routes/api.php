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

	Route::apiResource('note', 'App\Http\Controllers\NoteController');

	Route::apiResource('place.note', 'App\Http\Controllers\NoteController');

	Route::apiResource('event', 'App\Http\Controllers\EventController');

	Route::apiResource('place.event', 'App\Http\Controllers\EventController');

    Route::apiResource('user', 'App\Http\Controllers\UserController');

    Route::apiResource('notification', 'App\Http\Controllers\NotificationController');

    Route::get('notification/chunk/{chunk}', 'App\Http\Controllers\NotificationController@index');

    Route::put('notification/read/{notification}', 'App\Http\Controllers\NotificationController@read');

    Route::apiResource('tags_category', 'App\Http\Controllers\Tags_categoryController');

    Route::put('link/request/place/{place}', 'App\Http\Controllers\PlaceController@requestLink');

    Route::put('link/unlink/place/{place}', 'App\Http\Controllers\PlaceController@unlink');

    Route::put('link/cancel/place/{place}', 'App\Http\Controllers\PlaceController@cancelLink');

    Route::put('link/confirm/place/{place}', 'App\Http\Controllers\PlaceController@confirmLink');

    Route::put('link/decline/place/{place}', 'App\Http\Controllers\PlaceController@declineLink');

    Route::put('link/request/user/{user}', 'App\Http\Controllers\UserController@requestLink');

    Route::put('link/unlink/user/{user}', 'App\Http\Controllers\UserController@unlink');

    Route::put('link/cancel/user/{user}', 'App\Http\Controllers\UserController@cancelLink');

    Route::put('link/confirm/user/{user}', 'App\Http\Controllers\UserController@confirmLink');

    Route::put('link/decline/user/{user}', 'App\Http\Controllers\UserController@declineLink');

});
