<?php

use Illuminate\Support\Facades\Route;

Route::post('registration', 'AuthController@registration');
Route::post('login', 'AuthController@login');
Route::get('logout', 'AuthController@logout');


Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('profiles', 'UserController@show');
    Route::put('profiles/update', 'UserController@update');

    Route::get('events/{date}', 'EventController@index');
    Route::post('events/create', 'EventController@store');
    Route::get('events/read/{event}', 'EventController@show');
    Route::put('events/update', 'EventController@update');

    Route::get('event_types', 'EventTypeController');
});
