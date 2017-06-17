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
    Route::get('res-{resource_id}/download', 'ResourceController@download');
    Route::get('res-{resource_id}/view',     'ResourceController@view');
    Route::get('res-{resource_id}/delete',   'ResourceController@doDeleteResource');

    Route::get('inline/{content}', 'InlineController');

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

    /* Finance actions */
    Route::get('finance/overview', 'Finance\OverviewController@overview')->middleware('reqcompany');

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

    /* Module Group Company */ //TODO: namespace
    Route::group(['prefix' => 'company'], function() {
        Route::get('details',                 'Company\LayoutController@details');
        Route::get('setupcompany',            'Company\LayoutController@setupCompany');
        Route::post('setupcompany',           'Company\SetupCompanyController');
        Route::get('contacts',                'Company\LayoutController@contacts');
        Route::get('financial',               'Company\LayoutController@financial');
        Route::get('logo',                    'Company\LayoutController@logo');
        Route::get('preferences',             'Company\LayoutController@preferences');
        Route::post('update',                 'Company\UpdateController@updateDetails');
        Route::post('updatefinacial',         'Company\UpdateController@updateIban');
        Route::post('uploadlogo',             'Company\UploadController@uploadLogo');
        Route::post('uploadagreement',        'Company\UploadController@uploadAgreement');
    });

    /* Module Group Relation */
    Route::group(['namespace' => 'Relation'], function() {
        Route::get('relation/new',                      'RelationController@getNew');
        Route::post('relation/new',                     'RelationController@doNew');
        Route::post('relation/update',                  'RelationController@doUpdate');
        Route::post('relation/contact/new',             'RelationController@doNewContact');
        Route::post('relation/contact/update',          'RelationController@doUpdateContact');
        Route::post('relation/contact/delete',          'RelationController@doDeleteContact');
        Route::post('relation/iban/update',             'RelationController@doUpdateIban');
        Route::post('relation/iban/new',                'RelationController@doNewIban');
        Route::post('relation/updatecalc',              'RelationController@doUpdateProfit');
        Route::get('relation',                          'RelationController@getAll');
        Route::get('relation/import',                   'RelationController@getImport');
        Route::post('relation/import/save',             'ImportController');
        Route::get('relation/export',                   'ExportController');
        Route::get('relation/{relation_id}-{name}/details','RelationController@details');
        Route::get('relation/{relation_id}-{name}/contacts','RelationController@contacts');
        Route::get('relation/{relation_id}-{name}/financial','RelationController@financial');
        Route::get('relation/{relation_id}-{name}/invoices','RelationController@invoices');
        Route::get('relation/{relation_id}-{name}/preferences','RelationController@preferences');
        Route::get('relation/{relation_id}-{name}/notes','RelationController@notes');
        Route::get('relation/delete',     'RelationController@getDelete');
        Route::get('relation-{relation_id}/contact/new','RelationController@getNewContact');
        Route::get('relation-{relation_id}/contact-{contact_id}/edit', 'RelationController@getEditContact');
        Route::get('relation-{relation_id}/convert',    'RelationController@getConvert');
        Route::get('relation-{relation_id}/contact-{contact_id}/vcard', 'RelationController@downloadVCard');
    });
});

/* Authentication, Payzone, Require Company Group */
Route::group(['middleware' => ['auth','payzone','reqcompany']], function() {

    /* Module Group Invoice */
    Route::group(['namespace' => 'Invoice'], function() {
        // Route::post('invoice/updatecondition', 'InvoiceController@doUpdateCondition');
        // Route::post('invoice/updatecode', 'InvoiceController@doUpdateCode');
        // Route::post('invoice/updatedesc', 'InvoiceController@doUpdateDescription');
        // Route::post('invoice/updateamount', 'InvoiceController@doUpdateAmount');
        // // Route::get('invoice/project-{project_id}', 'Calculation\CalcController@getInvoiceAll');;
        // Route::get('invoice/project-{project_id}/invoice-{invoice_id}', 'Calculation\CalcController@getInvoice');
        // Route::get('invoice/project-{project_id}/term-invoice-{invoice_id}', 'Calculation\CalcController@getTermInvoice');
        // Route::post('invoice/save', 'InvoiceController@doInvoiceVersionNew');
        // Route::post('invoice/close', 'InvoiceController@doInvoiceClose');
        // Route::post('invoice/pay', 'InvoiceController@doInvoicePay');
        // Route::post('invoice/creditinvoice', 'InvoiceController@doCreditInvoiceNew');
        // Route::post('invoice/invclose', 'InvoiceController@doInvoiceCloseAjax');
        // Route::post('invoice/term/add', 'InvoiceController@doInvoiceNewTerm');
        // Route::post('invoice/term/delete', 'InvoiceController@doInvoiceDeleteTerm');
        // Route::get('invoice/project-{project_id}/invoice-version-{invoice_id}', function() {
        //     return view('calc.invoice_show_pdf');
        // });
        // Route::get('invoice/project-{project_id}/pdf-invoice-{invoice_id}', function() {
        //     return view('calc.invoice_show_final_pdf');
        // });
        // Route::get('invoice/project-{project_id}/invoice-{offer_id}/mail-preview', 'InvoiceController@getSendOfferPreview');
        // Route::post('invoice/sendmail', 'InvoiceController@doSendOffer');
        // Route::post('invoice/sendpost', 'InvoiceController@doSendPostOffer');
        // Route::get('invoice/pdf/project-{project_id}/invoice-{invoice_id}', 'Calculation\CalcController@getInvoicePDF');
        // Route::get('invoice/pdf/project-{project_id}/invoice-{invoice_id}/download', 'Calculation\CalcController@getInvoiceDownloadPDF');
        // Route::get('invoice/pdf/project-{project_id}/term-invoice-{invoice_id}/download', 'Calculation\CalcController@getTermInvoiceDownloadPDF');
        // Route::get('invoice/project-{project_id}/history-invoice-{invoice_id}', function() {
        //     return view('calc.invoice_version');
        // });

        Route::get('invoice/new',    'NewTermController');
        Route::get('invoice/delete', 'DeleteTermController');
        Route::get('invoice/pay',    'PayController');
        Route::post('invoice/close', 'CloseController');
    });

    /* Module Group Quotations */
    Route::group(['namespace' => 'Quotation'], function() {
        // Route::get('offer/project-{project_id}',                  'Calculation\CalcController@getOffer');;
        // Route::post('offer/project-{project_id}',                 'OfferController@doNewOffer');
        // Route::get('offer/project-{project_id}/offer-{offer_id}', function() {
        //     return view('calc.offer_show_pdf');
        // })->where('offer_id', '[0-9]+');;
        // Route::get('offer/project-{project_id}/offer-{offer_id}/mail-preview', 'OfferController@getSendOfferPreview')->where('offer_id', '[0-9]+');;
        // Route::post('offer/close',                                'OfferController@doOfferClose');
        // Route::post('offer/sendmail',                             'OfferController@doSendOffer');
        // Route::post('offer/sendpost',                             'OfferController@doSendPostOffer');

        Route::post('quotation/new',                              'NewController');
        Route::post('quotation/confirm',                          'ConfirmController');
    });

    /* Module Group Calculation */
    Route::group(['namespace' => 'Calculation'], function() {

        /* Routes by FavoriteController */
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
    //     return view('mail.inform_deactivate_user2',['name'=>'kaas','firstname'=>'arie',
    //     'lastname'=>'koos','body'=>'y','amount'=>12,'email'=>'info@jaas.com','reason'=>'oo',
    //     'expdate'=>'21-23-2017','project_name'=>'q','note'=>'x','username'=>'trol']);
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
