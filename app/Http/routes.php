<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


// API Контролни горски марки
Route::group(['prefix'=>'kgm'], function() {

    Route::get('search','ApiKgmController@search');

    Route::get('get','ApiKgmController@get');

//    Route::get('all','KgmapiController@all');

});

// API
//Route::group(['prefix'=>'api'], function() {
//
//});
