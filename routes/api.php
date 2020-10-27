<?php

use Illuminate\Http\Request;

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('events', 'EventsController@index');
Route::get('eventTypes', 'EventTypesController@index');