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

/* Module Group Auth */
Route::group(['namespace' => 'Auth'], function() {
    Route::get('auth/signin',                              'SigninController@index')->name('signin');
    Route::post('auth/signin',                             'SigninController@signin');
    Route::any('auth/signout',                             'SignoutController')->name('singout');
    Route::get('auth/signup',                              'SignupController@index')->name('signup');
    Route::post('auth/signup',                             'SignupController@signup');
    Route::get('auth/confirm/{token}',                     'ActivateController');
    Route::get('auth/password/{token}',                    'PasswordResetController@index');
    Route::post('auth/password/reset',                     'PasswordResetController@requestPasswordReset');
    Route::post('auth/password/{token}',                   'PasswordResetController@submitNewPassword');
});

/* Payment callbacks */
Route::post('payment/webhook/',                       'PaymentController@doPaymentUpdate');

Route::group(['middleware' => 'guest'], function() {
    Route::get('ex-project-overview/{token}',         'ClientController@getClientPage');
    Route::post('ex-project-overview/{token}/update', 'ClientController@doUpdateCommunication');
    Route::get('ex-project-overview/{token}/done',    'ClientController@doOfferAccept');
});

/* Support */
Route::post('support',         'SupportController@sendSupport');
Route::get('support',          'SupportController@getSupport');
Route::get('support/gethelp',  'SupportController@helpPage');

/* Authentication Group */
Route::group(['middleware' => 'auth'], function() {
    Route::get('/', 'DashboardController')->name('dashboard');

    Route::get('admin/switch/back', 'AdminController@getSwitchSessionBack');

    /* Resource actions*/
    Route::any('resource/{resource_id}/view/{name}',      'ResourceController@view');
    Route::any('resource/{resource_id}/download/{name}',  'ResourceController@download');
    Route::get('resource/{resource_id}/delete',           'ResourceController@delete');

    /* Routes by PaymentController */
    Route::get('payment',                      'PaymentController@getPayment');
    Route::get('payment/order/{token}',        'PaymentController@getPaymentFinish');
    Route::post('payment/promocode',           'PaymentController@doCheckPromotionCode');
    Route::get('payment/increasefree',         'PaymentController@getPaymentFree');
    Route::get('payment/subscription/cancel',  'PaymentController@getSubscriptionCancel');

    /* Module Group Account */
    Route::group(['namespace' => 'Account'], function() {
        Route::get('account',                                  'AccountController@getAccount')->name('account');
        Route::get('account/deactivate',                       'AccountController@getAccountDeactivate');
        Route::get('account/loaddemo',                         'AccountController@doLoadDemoProject');
        Route::get('account/oauth/session/{client_id}/revoke', 'AccountController@doRevokeApp');
        Route::post('account/updateuser',                      'AccountController@doAccountUser');
        Route::post('account/iban/new',                        'AccountController@doNewIban');
        Route::post('account/security/update',                 'AccountController@doUpdateSecurity');
        Route::post('account/preferences/update',              'AccountController@doUpdatePreferences');
        Route::post('account/notepad/save',                    'AccountController@doUpdateNotepad');
    });
});

/* Authentication and Payzone Group */
Route::group(['middleware' => ['auth','payzone']], function() {

    /* Notification actions */
    Route::get('notification',                          'NotificationController@notificationList');
    Route::get('notification/message-{message}/read',   'NotificationController@doRead')->where('message', '[0-9]+');
    Route::get('notification/message-{message}/delete', 'NotificationController@doDelete')->where('message', '[0-9]+');
    Route::get('notification/message-{message}',        'NotificationController@getMessage')->where('message', '[0-9]+');

    /* Module Group Finance */
    Route::group(['namespace' => 'Finance'], function() {
        Route::get('finance/overview', 'OverviewController@overview')->middleware('reqcompany');
    });

    /* Module Group Product */
    Route::group(['namespace' => 'Product'], function() {

         /* Product list */
        Route::get('material',                        'MaterialController@getList');
        Route::get('material/subcat/{type}/{id}',     'MaterialController@getListSubcat');
        Route::post('material/search',                'MaterialController@doSearch');
        Route::post('material/newmaterial',           'MaterialController@doNew');
        Route::post('material/updatematerial',        'MaterialController@doUpdate');
        Route::post('material/deletematerial',        'MaterialController@doDelete');
        Route::post('material/favorite',              'MaterialController@doFavorite');
        Route::post('material/element/new',           'MaterialController@doNewElement');
        Route::post('material/upload',                'MaterialController@doUploadCSV');

        /* Wholesale */
        Route::get('wholesale',                       'WholesaleController@getAll');
        Route::get('wholesale/new',                   'WholesaleController@getNew');
        Route::post('wholesale/new',                  'WholesaleController@doNew');
        Route::post('wholesale/update',               'WholesaleController@doUpdate');
        Route::get('wholesale-{wholesale_id}/edit',   'WholesaleController@getEdit')->where('wholesale_id', '[0-9]+');
        Route::get('wholesale-{wholesale_id}/show',   'WholesaleController@getShow')->where('wholesale_id', '[0-9]+');
        Route::post('wholesale/iban/update',          'WholesaleController@doUpdateIban');
        Route::get('wholesale-{wholesale_id}/delete', 'WholesaleController@getDelete')->where('wholesale_id', '[0-9]+');
    });

    /* Module Group Company */
    Route::group(['namespace' => 'Company'], function() {
        Route::get('company/details',                 'LayoutController@details');
        Route::get('company/setupcompany',            'LayoutController@setupCompany');
        Route::post('company/setupcompany',           'SetupCompanyController');
        Route::get('company/contacts',                'LayoutController@contacts');
        Route::get('company/financial',               'LayoutController@financial');
        Route::get('company/logo',                    'LayoutController@logo');
        Route::get('company/preferences',             'LayoutController@preferences');
        Route::post('company/update',                 'UpdateController@updateDetails');
        Route::post('company/updatefinacial',         'UpdateController@updateIban');
        Route::post('company/uploadlogo',             'UploadController@uploadLogo');
        Route::post('company/uploadagreement',        'UploadController@uploadAgreement');
    });

    /* Module Group Relation */
    Route::group(['namespace' => 'Relation'], function() {
        Route::get('relation/new',                      'RelationController@getNew');
        Route::post('relation/new',                     'RelationController@doNew');
        Route::post('relation/update',                  'RelationController@doUpdate');
        Route::post('relation/note/update',             'RelationController@doUpdateNote');
        Route::post('relation/contact/new',             'RelationController@doNewContact');
        Route::post('relation/contact/update',          'RelationController@doUpdateContact');
        Route::post('relation/contact/delete',          'RelationController@doDeleteContact');
        Route::post('relation/iban/update',             'RelationController@doUpdateIban');
        Route::post('relation/iban/new',                'RelationController@doNewIban');
        Route::post('relation/updatecalc',              'RelationController@doUpdateProfit');
        Route::get('relation/delete',                   'RelationController@getDelete');
        Route::get('relation',                          'FilterController');
        Route::get('relation/import',                   'RelationController@getImport');
        Route::post('relation/import/save',             'ImportController');
        Route::get('relation/export',                   'ExportController');

        Route::get('relation/{relation_id}-{name}/details',                     'RelationController@details');
        Route::get('relation/{relation_id}-{name}/contacts',                    'RelationController@contacts');
        Route::get('relation/{relation_id}-{name}/financial',                   'RelationController@financial');
        Route::get('relation/{relation_id}-{name}/invoices',                    'RelationController@invoices');
        Route::get('relation/{relation_id}-{name}/preferences',                 'RelationController@preferences');
        Route::get('relation/{relation_id}-{name}/notes',                       'RelationController@notes');
        Route::get('relation/{relation_id}-{name}/contact/new',                 'RelationController@getNewContact');
        Route::get('relation/{relation_id}-{name}/convert',                     'RelationController@getConvert');
        Route::get('relation/{relation_id}-{name}/contact-{contact_id}/edit',   'RelationController@getEditContact');
        Route::get('relation/{relation_id}-{name}/contact-{contact_id}/vcard',  'RelationController@downloadVCard');
    });

    /* Module Group Favorite */
    Route::group(['namespace' => 'Favorite'], function() {
        Route::post('favorite/level/new',                                   'LevelController@newLevel');
        Route::post('favorite/level/rename',                                'LevelController@renameLevel');
        Route::post('favorite/level/description',                           'LevelController@descriptionLevel');
        Route::get('favorite/level/delete',                                 'LevelController@deleteLevel');
    });
});

/* Authentication, Payzone, Require Company Group */
Route::group(['middleware' => ['auth', 'payzone', 'reqcompany']], function() {

    /* Module Group Invoice */
    Route::group(['namespace' => 'Invoice'], function() {
        // Route::post('invoice/updatedesc', 'InvoiceController@doUpdateDescription');
        // Route::post('invoice/updateamount', 'InvoiceController@doUpdateAmount');
        // Route::post('invoice/creditinvoice', 'InvoiceController@doCreditInvoiceNew');
        // Route::post('invoice/invclose', 'InvoiceController@doInvoiceCloseAjax');
        // Route::post('invoice/term/add', 'InvoiceController@doInvoiceNewTerm');
        // Route::post('invoice/term/delete', 'InvoiceController@doInvoiceDeleteTerm');
        // Route::get('invoice/project-{project_id}/invoice-{offer_id}/mail-preview', 'InvoiceController@getSendOfferPreview');
        // Route::post('invoice/sendmail', 'InvoiceController@doSendOffer');

        Route::get('invoice/new',    'NewTermController');
        Route::get('invoice/delete', 'DeleteTermController');
        Route::get('invoice/pay',    'PayController');
        Route::post('invoice/close', 'CloseController');
    });

    /* Module Group Quotations */
    Route::group(['namespace' => 'Quotation'], function() {
        // Route::post('offer/sendmail',                             'OfferController@doSendOffer');

        Route::post('quotation/new',                              'NewController');
        Route::post('quotation/confirm',                          'ConfirmController');
    });

    /* Module Group Calculation */
    Route::group(['namespace' => 'Calculation'], function() {

        // Route::post('favorite/newmaterial',                       'FavoriteController@doNewMaterial');
        // Route::post('favorite/newequipment',                      'FavoriteController@doNewEquipment');
        // Route::post('favorite/newlabor',                          'FavoriteController@doNewLabor');
        // Route::post('favorite/deletematerial',                    'FavoriteController@doDeleteMaterial');
        // Route::post('favorite/deleteequipment',                   'FavoriteController@doDeleteEquipment');
        // Route::post('favorite/deletelabor',                       'FavoriteController@doDeleteLabor');
        // Route::post('favorite/updatematerial',                    'FavoriteController@doUpdateMaterial');
        // Route::post('favorite/updateequipment',                   'FavoriteController@doUpdateEquipment');
        // Route::post('favorite/updatelabor',                       'FavoriteController@doUpdateLabor');
        // Route::post('favorite/deleteactivity',                    'FavoriteController@doDeleteActivity');
        // Route::post('favorite/noteactivity',                      'FavoriteController@doUpdateNote');
        // Route::post('favorite/rename_activity',                   'FavoriteController@doRenameActivity');

        /* Routes by FavoriteController */
        Route::post('favorite/new',                               'FavoriteController@new');
        Route::post('favorite/update',                            'FavoriteController@update');
        Route::post('favorite/delete',                            'FavoriteController@delete');


        /* Calculation actions */
        Route::post('calculation/new',                            'CalculationController@new');
        Route::post('calculation/update',                         'CalculationController@update');
        Route::post('calculation/delete',                         'CalculationController@delete');
        Route::get('calculation/summary/project-{project_id}',    'CalculationController@asyncSummary');
        Route::get('calculation/endresult/project-{project_id}',  'CalculationController@asyncEndresult');


        /* Estimate actions by estimate */
        Route::post('estimate/new',                               'EstimateController@new');
        Route::post('estimate/update',                            'EstimateController@update');
        Route::post('estimate/delete',                            'EstimateController@delete');
        Route::post('estimate/reset',                             'EstimateController@reset');
        Route::get('estimate/summary/project-{project_id}',       'EstimateController@asyncSummary');
        Route::get('estimate/endresult/project-{project_id}',     'EstimateController@asyncEndresult');

        /* Less actions */
        Route::post('less/update',                                'LessController@update');
        Route::post('less/reset',                                 'LessController@reset');
        Route::get('less/summary/project-{project_id}',           'LessController@asyncSummary');
        Route::get('less/endresult/project-{project_id}',         'LessController@asyncEndresult');


        /* More actions */
        Route::post('more/new',                                   'MoreController@new');
        Route::post('more/update',                                'MoreController@update');
        Route::post('more/delete',                                'MoreController@delete');
        Route::get('more/summary/project-{project_id}',           'MoreController@asyncSummary');
        Route::get('more/endresult/project-{project_id}',         'MoreController@asyncEndresult');

        // TODO /////////////////// {
        Route::post('calculation/calc/savefav',                   'CalcController@doNewCalculationFavorite');

        //TODO: rename
        /* Blancrow acions by calculation */
        Route::post('blancrow/newrow',                            'BlancController@doNewRow');
        Route::post('blancrow/updaterow',                         'BlancController@doUpdateRow');

        /* Calculation pages */
        Route::get('calculation/project-{project_id}/chapter-{chapter_id}/fav-{fav_id}', 'CalcController@getCalculationWithFavorite');

        Route::get('blancrow/project-{project_id}',               'BlancController@getBlanc');
        Route::get('estimate/project-{project_id}/chapter-{chapter_id}/fav-{fav_id}', 'CalcController@getEstimateWithFavorite');

        /* More pages */
        Route::get('more/project-{project_id}/chapter-{chapter_id}/fav-{fav_id}', 'MoreController@getMoreWithFavorite');
        /////////////////////////// }
    });

    // Route::get('xxx', function() {
    //     return view('mail.offer_send',['name'=>'kaas','body'=>'y','amount'=>12,'email'=>'info@jaas.com','reason'=>'oo',
    //     'expdate'=>'21-23-2017','project_name'=>'q','note'=>'Its just great','username'=>'trol',
    //     'subscription'=>'x','category'=>'trol','subject'=>'ice', 'message'=>'troll','token'=>'x','user'=>'Arie Kaas',
    //     'pref_email_offer' => 'Bij deze doe ik u toekomen mijn prijsopgaaf betreffende het uit te voeren werk zoals eerder opgenomen. De offerte heb ik in de bijlage toegevoegd. Mocht u vragen hebben, stel ze gerust, dan beantwoord ik deze graag.'
    //     ]);
    // });

    /* Module Group Project */
    Route::group(['namespace' => 'Project'], function() {
        Route::get('project/all',                                          'FilterController');
        Route::get('project/new',                                          'NewController@index');
        Route::post('project/new',                                         'NewController@new');

        /* Project component entrypoint */
        Route::get('project/{project_id}-{name}/{module}/{submodule?}',    'ComponentController@index');

        /* Layer operations */
        Route::post('project/layer/tax',                                   'LayerController@updateTax');

        /* Level operations*/
        Route::post('project/level/new',                                   'LevelController@newLevel');
        Route::post('project/level/rename',                                'LevelController@renameLevel');
        Route::post('project/level/description',                           'LevelController@descriptionLevel');
        Route::get('project/level/move',                                   'LevelController@moveLevel');
        Route::get('project/level/option',                                 'LevelController@setOption');
        Route::get('project/level/delete',                                 'LevelController@deleteLevel');
        Route::get('project/level/export',                                 'TransformController@toFavorite');
        Route::get('project/level/import',                                 'TransformController@fromFavorite');

        /* Project specific operations */
        Route::post('project/update',                                      'UpdateController@updateDetails');
        Route::post('project/update/note',                                 'UpdateController@updateNote');
        Route::post('project/update/communication',                        'UpdateController@doCommunication');//TODO: MOVE
        Route::post('project/updatecalc',                                  'UpdateController@updateProfit');
        Route::post('project/updateoptions',                               'UpdateController@updateOptions');
        Route::post('project/updateworkexecution',                         'UpdateController@updateWorkExecution');
        Route::post('project/updateworkcompletion',                        'UpdateController@updateWorkCompletion');
        Route::get('project/relation/{relation_id}',                       'UpdateController@getRelationDetails'); //TODO: MOVE
        Route::get('project/close',                                        'UpdateController@updateProjectClose');
        Route::get('project/cancel'                    ,                   'UpdateController@cancel');
        Route::get('project/copy',                                         'CopyController');
        Route::post('project/document/upload',                             'DocumentController');
    });

    /* Module Group Cost */
    Route::group([], function() {
        Route::get('timesheet',                                            'CostController@getTimesheet');
        Route::post('timesheet/new',                                       'CostController@doNewTimesheet');
        Route::post('timesheet/delete',                                    'CostController@doDeleteTimesheet');

        Route::get('timesheet/activity/{project_id}/{type}',               'CostController@getActivityByType')->where('type', '[0-9]+');
        Route::get('purchase',                                             'CostController@getPurchase');
        Route::post('purchase/new',                                        'CostController@doNewPurchase');
        Route::post('purchase/delete',                                     'CostController@doDeletePurchase');
    });
});
