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

//message
Route::get('/message/receives', 'MessageController@show_receives');
Route::get('/message/sends', 'MessageController@show_sends');
Route::get('/message/create', 'MessageController@create');

//line
Route::get('/line/set', 'LineController@set');

//statistics
Route::get('/statistics/pressure','StatisticsController@pressure');
Route::get('/statistics/sugar','StatisticsController@sugar');

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
    Route::resource('unfollow', 'FollowController@unfollow');
    Route::resource('follow/approve', 'FollowController@approve_follow');
    //message
    Route::get('message/send/to_user_id/{to_user_id}/content/{content}', 'MessageController@send');
    Route::resource('message/delete', 'MessageController@destroy');
    //line
    Route::resource('line/pressure/high', 'LineController@set_pressure_high_line');
    Route::resource('line/pressure/low', 'LineController@set_pressure_low_line');
    Route::resource('line/sugar', 'LineController@set_sugar_line');
    Route::resource('line/get', 'LineController@get');
    //statistics
    Route::resource('statistics','StatisticsController@index');

});
