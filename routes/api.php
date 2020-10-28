<?php

use Illuminate\Http\Request;

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('login', 'AuthController@login');
Route::post('registration', 'AuthController@registration');

Route::get('profile/{id}', 'UsersController@read');
Route::put('profile/update','UsersController@update');

Route::get('events/{date}', 'EventsController@index');
Route::post('events/create', 'EventsController@create');
Route::put('events/update', 'EventsController@update');

Route::get('eventTypes', 'EventTypesController@index');