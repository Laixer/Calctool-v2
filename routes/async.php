<?php

/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

/* Frontend API */
Route::post('register/usernamecheck', 'ApiController@doCheckUsernameEXist');

Route::group(['middleware' => 'auth'], function() {
    Route::get('/',                  'ApiController@getApiRoot');
    Route::post('/update',           'ApiController@getUserUpdate');
    Route::get('/projects',          'ApiController@getProjects');
    Route::get('/relations',         'ApiController@getRelations');

    Route::get('/timesheet',         'ApiController@getTimesheet');
    Route::post('/timesheet/delete', 'ApiController@doTimesheetDelete');
    Route::post('/timesheet/new',    'ApiController@doTimesheetNew');

    Route::get('/purchase',          'ApiController@getPurchase');
    Route::post('/purchase/delete',  'ApiController@doPurchaseDelete');
    Route::post('/purchase/new',     'ApiController@doPurchaseNew');
});
