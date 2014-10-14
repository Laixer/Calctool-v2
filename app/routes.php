<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('login', array('before' => 'guest', 'as' => 'login', 'uses' => 'AuthController@getLogin'));
Route::post('login', array('before' => 'guest', 'uses' => 'AuthController@doLogin'));

Route::group(array('before' => 'auth'), function()
{
	Route::get('logout', array('as' => 'logout', 'uses' => 'AuthController@doLogout'));
	Route::get('calculation', array('uses' => 'CalcController@getCalculation'));
	Route::get('relation/new', array('uses' => 'RelationController@getNew'));
	Route::get('project/new', array('uses' => 'ProjectController@getNew'));
	Route::get('/', array('uses' => 'HomeController@getHome'));
});
