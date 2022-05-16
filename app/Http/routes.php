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

use App\Name;
// use App\Http\Controllers\ListNamesKgmController;

Route::get('/welcome', function(){
    return view("welcome");
});

// API Контролни горски марки
Route::group(['prefix'=>'kgm'], function() {

    Route::get('search','Kgm\ApiKgmController@search');

    Route::get('get','Kgm\ApiKgmController@get');


//    Route::get('all','KgmapiController@all');

});

// API Телефонен указател
Route::group(['prefix'=>'tel'], function() {

    Route::get('allusers/{filterBy}',"Tel\AllUsersController@allusers");
    Route::get('podelenia/{filterBy?}',"Tel\PodeleniaController@podelenia");

    //vasil.iag.bg/tel/users/byPhone
    Route::get('users',"Tel\ChasnaPracticaController@phone");

});

Route::get('list', ['uses' => 'ListNamesKgmController@index', 'us' => 'list']);

Route::get('pod', ['uses' => 'PodeleniaController@index', 'us' => 'pod']);



Route::group(['prefix'=>'tel/v1'], function() {
    Route::resource('/','IagTel\EmplController');
});

Route::group(['prefix'=>'tel/v2'], function() {
    Route::resource('/','IagTel\ЕmployeesController');
});


// $param iag, rdg, dgs, search, about
Route::group(['prefix'=>'tel/v3/{param}'], function($param) {
    Route::resource('/','IagTel\ParamEmplController');
});

// $param iag, rdg, dgs, search, about
Route::group(['prefix'=>'tel/v4/'], function() {
    Route::resource('/','IagTel\V4Controller');
});

Route::group(['prefix'=>'tel/v5/'], function() {
    Route::resource('/','IagTel\V5Controller');
});


