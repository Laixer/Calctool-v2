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

Route::any('privacy-policy', function()
{
	return View::make('generic.privacy');
});

Route::group(array('before' => 'auth'), function()
{
	Route::get('logout', array('as' => 'logout', 'uses' => 'AuthController@doLogout'));
	Route::get('calculation/project-{project_id}', array('as' => 'calculation', 'uses' => 'CalcController@getCalculation'))->where('project_id', '[0-9]+');
	Route::post('calculation/newchapter/{project_id}', array('as' => 'calculation', 'uses' => 'CalcController@doNewChapter'))->where('project_id', '[0-9]+');
	Route::post('calculation/newactivity/{chapter_id}', array('as' => 'calculation', 'uses' => 'CalcController@doNewActivity'))->where('chapter_id', '[0-9]+');
	Route::post('calculation/updatepart', array('as' => 'calculation', 'uses' => 'CalcController@doUpdatePart'));
	Route::post('calculation/updateparttype', array('as' => 'calculation', 'uses' => 'CalcController@doUpdatePartType'));
	Route::post('calculation/updatetax', array('as' => 'calculation', 'uses' => 'CalcController@doUpdateTax'));
	Route::post('calculation/deleteactivity', array('as' => 'calculation', 'uses' => 'CalcController@doDeleteActivity'));
	Route::post('calculation/updateamount', array('as' => 'calculation', 'uses' => 'CalcController@doUpdateAmount'));
	Route::post('calculation/newmaterial', array('as' => 'calculation', 'uses' => 'CalcController@doNewMaterial'));
	Route::post('calculation/deletematerial', array('as' => 'calculation', 'uses' => 'CalcController@doDeleteMaterial'));
	Route::post('calculation/updatematerial', array('as' => 'calculation', 'uses' => 'CalcController@doUpdateMaterial'));
	/*
	DZa: Routes naar de controler achter de delte knop van calculatie labour. MOgelijk de de "amount" (ook in de regel hierboven) veranderen in Labour
	Route::post('calculation/deleteamount', array('as' => 'calculation', 'uses' => 'CalcController@doDeleteAmount'));
	*/
	Route::get('estimate', array('as' => 'estimate', 'uses' => 'CalcController@getEstimate'));
	Route::get('less', array('as' => 'less', 'uses' => 'CalcController@getLess'));
	Route::get('more', array('as' => 'more', 'uses' => 'CalcController@getMore'));
	Route::get('relation/new', array('as' => 'relation.new', 'uses' => 'RelationController@getNew'));
	Route::post('relation/new', array('as' => 'relation.new', 'uses' => 'RelationController@doNew'));
	Route::get('relation', array('as' => 'relation', 'uses' => 'RelationController@getAll'));
	Route::get('project/new', array('as' => 'project.new', 'uses' => 'ProjectController@getNew'));
	Route::post('project/new', array('as' => 'project.new', 'uses' => 'ProjectController@doNew'));
	Route::get('project', array('as' => 'project', 'uses' => 'ProjectController@getAll'));
	Route::get('timesheet', array('as' => 'timesheet', 'uses' => 'CostController@getTimesheet'));
	Route::get('purchase', array('as' => 'purchase', 'uses' => 'CostController@getPurchase'));
	Route::get('/', array('uses' => 'HomeController@getHome'));
});
