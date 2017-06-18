<?php

/**
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the BynqIO\CalculatieTool.com.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 *
 * @package  CalculatieTool
 * @author   Yorick de Wid <y.dewid@calculatietool.com>
 */

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

/* Admin */
Route::get('/', 'AdminController@getDashboard')->name('adminDashboard');
Route::get('user/new', function() {
    return view('admin.new_user');
});
Route::post('user/new', 'AdminController@doNewUser');
Route::get('user', function() {
    return view('admin.user');
});
Route::get('user-{user_id}/edit', function() {
    return view('admin.edit_user');
});
Route::get('user-{user_id}/switch', 'AdminController@getSwitchSession');
Route::get('user-{user_id}/validation', 'AdminController@getValidationProject');
Route::get('user-{user_id}/stabu', 'AdminController@getStabuProject');
Route::get('user-{user_id}/passreset', 'AdminController@getPasswordResetUser');
Route::get('user-{user_id}/passdefault', 'AdminController@getPasswordDefault');
Route::get('user-{user_id}/purge', 'AdminController@getPurgeUser');
Route::get('user-{user_id}/login', 'AdminController@getLoginAsUser');
Route::get('user-{user_id}/subscription/cancel', 'AdminController@getSubscriptionCancel');
Route::post('user-{user_id}/edit', 'AdminController@doUpdateUser');
Route::post('user-{user_id}/adminlog/new', 'AdminController@doNewAdminLog');

/* Group actions */
Route::get('group', function() {
    return view('admin.group');
});
Route::get('group-{group_id}/edit', function() {
    return view('admin.edit_group');
});
Route::post('group-{group_id}/edit', 'AdminController@doUpdateGroup');
Route::get('group-{group_id}/delete', 'AdminController@getDeleteGroup');
Route::get('group/new', function() {
    return view('admin.new_group');
});
Route::post('group/new', 'AdminController@doNewGroup');

/* Tag actions */
Route::get('user/tags', function() {
    return view('admin.tag');
});
Route::get('user/tags/new', function() {
    return view('admin.new_tag');
});
Route::post('user/tags/new', 'AdminController@doNewTag');
Route::get('user/tag-{tag}/delete', 'AdminController@doDeleteTag');

/* Alert actions */
Route::get('alert', function() {
    return view('admin.alert');
});
Route::post('alert/new', 'AdminController@doNewAlert');
Route::post('alert/delete', 'AdminController@doDeleteAlert');

Route::get('phpinfo', function() {
    return view('admin.phpinfo');
});
Route::post('transaction/{transcode}/refund', 'AdminController@doRefund');
Route::get('payment', function() {
    return view('admin.transaction');
});
Route::get('transaction/{transcode}', function() {
    return view('admin.transaction_code');
});
Route::get('environment', function() {
    return view('admin.server');
});
Route::get('environment/clearcaches', 'AdminController@doApplicationClearCache');
Route::get('project', function() {
    return view('admin.project');
});
Route::get('snailmail', function() {
    return view('admin.snailmail');
});
Route::get('message', function() {
    return view('admin.message');
});
Route::post('message', 'AdminController@doSendNotification');
Route::post('promo', 'AdminController@doNewPromotion');
Route::get('promo/{id}/delete', 'AdminController@doDeletePromotion');
Route::get('promo', function() {
    return view('admin.promo');
});
Route::get('application', function() {
    return view('admin.application');
});
Route::get('application/new', function() {
    return view('admin.new_application');
});
Route::get('application/{client_id}/edit', function() {
    return view('admin.edit_application');
});
Route::get('product', function() {
    return view('admin.product');
});
Route::get('auditlog', 'AuditController');

Route::post('product/upload', 'AdminController@doUploadCSV');
Route::post('product/emptylist', 'AdminController@getEmptyList');
Route::get('application/{client_id}/delete', 'AdminController@doDeleteApplication');
Route::post('application/{client_id}/edit', 'AdminController@doUpdateApplication');
Route::post('application/new', 'AdminController@doNewApplication');
Route::post('snailmail/offer/done', 'AdminController@doOfferPostDone');
Route::post('snailmail/invoice/done', 'AdminController@doInvoicePostDone');
Route::post('snailmail/offer/delete', 'AdminController@doOfferPostDelete');
Route::post('snailmail/invoice/delete', 'AdminController@doInvoicePostDelete');
Route::get('resource', function() {
    return view('admin.resource');
});
Route::post('resource/delete', 'AdminController@doDeleteResource');
// Route::get('log', function() {
//     return view('admin.log');
// });
// Route::get('log/truncate', 'AdminController@doTruncateLog');
// Route::get('session', function() {
//     return view('admin.session');
// });
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
Route::get('session/{session}/kill', 'AdminController@killSession');
