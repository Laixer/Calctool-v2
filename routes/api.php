<?php

/*
|--------------------------------------------------------------------------
| Service Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

/* Oauth2 REST API */
Route::group(['middleware' => ['check-authorization-params', 'auth']], function() {
    Route::get('authorize',  'AuthController@getOauth2Authorize');
    Route::post('authorize', 'AuthController@doOauth2Authorize');
});

Route::post('access_token', 'AuthController@doIssueAccessToken');

/* Rest Service calls */
Route::group(['prefix' => 'oauth2/rest', 'middleware' => 'oauth'], function() {

    /* Owner rest functions */
    Route::get('user',      'AuthController@getRestUser');
    Route::get('projects',  'AuthController@getRestUserProjects');
    Route::get('relations', 'AuthController@getRestUserRelations');

    /* Internal rest functions */
    Route::get('internal/verify',         'AuthController@getRestVerify');
    Route::post('internal/user_signup',   'AuthController@doRestNewUser');
    Route::post('internal/usernamecheck', 'AuthController@doRestUsernameCheck');
    Route::get('internal/user_all',       'AuthController@getRestAllUsers');
    Route::get('internal/relation_all',   'AuthController@getRestAllRelations');
    Route::get('internal/project_all',    'AuthController@getRestAllProjects');
    Route::get('internal/chapter_all',    'AuthController@getRestAllChapters');
    Route::get('internal/activity_all',   'AuthController@getRestAllActivities');
    Route::get('internal/offer_all',      'AuthController@getRestAllOffers');
    Route::get('internal/invoice_all',    'AuthController@getRestAllInvoices');
});
