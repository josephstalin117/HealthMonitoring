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

Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::get('/home', 'HomeController@index');

//profile
Route::get('/profile', 'ProfileController@index');
Route::post('/profile/update', 'ProfileController@update');

//user manage
Route::get('/usermanage', 'UserManageController@index');
Route::post('/usermanage/update', 'UserManageController@update');
Route::get('/usermanage/delete/{id}', 'UserManageController@delete');
Route::post('/usermanage/create', 'UserManageController@create');

//pressure
Route::get('/pressures', 'PressureController@index');
Route::get('/pressure/create', 'PressureController@create');
Route::post('/pressure/store', 'PressureController@store');
Route::get('/pressure/search/{nickname?}', 'PressureController@search');

//api
Route::group(['prefix' => 'api'], function () {
    //user
    Route::resource('usermanage', 'UserManageController@show');
    Route::get('usermanage/search/{nickname}', 'UserManageController@search');
    Route::resource('usermanage/delete', 'UserManageController@destroy');
    //paper
    Route::resource('paper/delete', 'PaperController@destroy');
    //pressure
    Route::resource('pressure', 'PressureController@show');
});
