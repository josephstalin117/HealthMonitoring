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
Route::get('/lines', 'LineController@index');

//statistics
Route::get('/statistics/pressure', 'StatisticsController@pressure');
Route::get('/statistics/sugar', 'StatisticsController@sugar');

//articles
Route::get('/articlemanage', 'ArticleController@index');
Route::get('/article/create', 'ArticleController@create');
Route::get('/article/update/{id}', 'ArticleController@update');
Route::post('/article/store/{id?}', 'ArticleController@store');
Route::get('/articles', 'ArticleController@list_articles');
Route::get('article/{id}', 'ArticleController@show');

//api
Route::group(['prefix' => 'api'], function () {
    //user
    Route::resource('usermanage', 'UserManageController@show');
    Route::get('usermanage/search/{nickname}', 'UserManageController@search');
    Route::resource('usermanage/delete', 'UserManageController@destroy');
    //pressure
    Route::resource('pressure', 'PressureController@show');
    Route::get('pressure/delete/{id}', 'PressureController@destroy');
    //sugar
    Route::resource('sugar', 'SugarController@show');
    Route::get('sugar/delete/{id}', 'SugarController@destroy');
    //follow
    Route::resource('follow', 'FollowController@follow');
    Route::resource('unfollow', 'FollowController@unfollow');
    Route::get('follow/approve/user/{follow_id}/message/{message_id}', 'FollowController@approve_follow');
    //message
    Route::get('message/send/to_user_id/{to_user_id}/content/{content}', 'MessageController@send');
    Route::resource('message/delete', 'MessageController@destroy');
    Route::resource('message/check', 'MessageController@check');
    //line
    Route::resource('line/pressure/high', 'LineController@set_pressure_high_line');
    Route::resource('line/pressure/low', 'LineController@set_pressure_low_line');
    Route::resource('line/sugar', 'LineController@set_sugar_line');
    Route::resource('line/get', 'LineController@get');
    //statistics
    Route::resource('statistics', 'StatisticsController@index');
    //article
    Route::get('article/delete/{id}', 'ArticleController@destroy');

});
