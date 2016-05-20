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
Route::get('/pressure/search', 'PressureController@search');
Route::get('/pressure/search/nickname/{nickname}', 'PressureController@search');

//sugar
Route::get('/sugars', 'SugarController@index');
Route::get('/sugar/create', 'SugarController@create');
Route::post('/sugar/store', 'SugarController@store');
Route::get('/sugar/search', 'SugarController@search');
Route::get('/sugar/search/nickname/{nickname}', 'SugarController@search');

//follow
Route::get('/following', 'FollowController@show_following');
Route::get('/followers', 'FollowController@show_followers');

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
    //sugar
    Route::resource('sugar', 'SugarController@show');
    //follow
    Route::resource('follow', 'FollowController@follow');
    //message
    Route::get('message/send/to_user_id/{to_user_id}/from_user_id/{from_user_id}/content/{content}/type/{type}', 'MessageController@send');
});
