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

Route::any('about-us', function()
{
	return View::make('generic.about');
});

Route::any('contact', function()
{
	return View::make('generic.contact');
});

Route::any('faq', function()
{
	return View::make('generic.faq');
});


Route::any('terms-and-conditions', function()
{
	return View::make('generic.terms');
});

Route::group(array('before' => 'auth'), function()
{
	Route::get('logout', array('as' => 'logout', 'uses' => 'AuthController@doLogout'));
	Route::get('calculation', array('as' => 'calculation', 'uses' => 'CalcController@getCalculation'));
	Route::get('relation/new', array('as' => 'relation.new', 'uses' => 'RelationController@getNew'));
	Route::get('project/new', array('as' => 'project.new', 'uses' => 'ProjectController@getNew'));
	Route::get('/', array('uses' => 'HomeController@getHome'));
});
