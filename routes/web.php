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

/* Application front actions */
Route::group(['namespace' => $this->namespaceAuth, 'prefix' => 'auth'], function() {
    /* Signin actions */
    Route::get('signin',                              'SigninController@index')->name('signin');
    Route::post('signin',                             'SigninController@signin');

    /* Signout actions */
    Route::any('signout',                             'SignoutController')->name('singout');

    /* Signup actions */
    Route::get('signup',                              'SignupController@index')->name('signup');
    Route::post('signup',                             'SignupController@signup');

    /* Account confirm actions */
    Route::get('confirm/{token}',                     'ActivateController');

    /* Password reset actions */
    Route::get('password/{token}',                    'PasswordResetController@index');
    Route::post('password/reset',                     'PasswordResetController@requestPasswordReset');
    Route::post('password/{token}',                   'PasswordResetController@submitNewPassword');
});

Route::group(['middleware' => 'guest'], function() {
    /* Payment callbacks */
    Route::post('payment/webhook/',                   'PaymentController@doPaymentUpdate');

    Route::get('ex-project-overview/{token}',         'ClientController@getClientPage');
    Route::post('ex-project-overview/{token}/update', 'ClientController@doUpdateCommunication');
    Route::get('ex-project-overview/{token}/done',    'ClientController@doOfferAccept');
});

/* Support */
Route::post('support', 'SupportController@sendSupport');
Route::get('support',  'SupportController@getSupport');
Route::get('get-help',  'SupportController@helpPage');

Route::group(['middleware' => 'auth'], function() {
    Route::get('/', 'DashboardController')->name('dashboard');

    Route::get('admin/switch/back', 'AdminController@getSwitchSessionBack');
    
    /* Resource actions*/
    Route::get('res-{resource_id}/download', 'ResourceController@download');
    Route::get('res-{resource_id}/view', 'ResourceController@view');
    Route::get('res-{resource_id}/delete', 'ResourceController@doDeleteResource');
   
    /* Module Group Account */
    Route::group(['namespace' => $this->namespaceAccount], function() {
        
        /* Account actions */
        Route::get('account',                     'AccountController@getAccount')->name('account');
        Route::get('account/deactivate',          'AccountController@getAccountDeactivate');
        Route::get('account/loaddemo',            'AccountController@doLoadDemoProject');
        Route::get('account/oauth/session/{client_id}/revoke', 'AccountController@doRevokeApp');
        Route::post('account/updateuser',         'AccountController@doAccountUser');
        Route::post('account/iban/new',           'AccountController@doNewIban');
        Route::post('account/security/update',    'AccountController@doUpdateSecurity');
        Route::post('account/preferences/update', 'AccountController@doUpdatePreferences');
        Route::post('account/notepad/save',       'AccountController@doUpdateNotepad');
    });

    /* Notification actions */
    Route::get('notification',                          'NotificationController@notificationList');
    Route::get('notification/message-{message}/read',   'NotificationController@doRead')->where('message', '[0-9]+');
    Route::get('notification/message-{message}/delete', 'NotificationController@doDelete')->where('message', '[0-9]+');
    Route::get('notification/message-{message}',        'NotificationController@getMessage')->where('message', '[0-9]+');

    /* Finance actions */
    Route::get('finance/overview', 'Finance\OverviewController@overview');

    Route::get('affiliate/5bdc2bbd-4021-4e12-9012-647385c28c05', function(){
        return view('user.affiliate');
    });

    /* Routes by PaymentController */
    Route::get('payment',                      'PaymentController@getPayment');
    Route::get('payment/order/{token}',        'PaymentController@getPaymentFinish');
    Route::post('payment/promocode',           'PaymentController@doCheckPromotionCode');
    Route::get('payment/increasefree',         'PaymentController@getPaymentFree');
    Route::get('payment/subscription/cancel',  'PaymentController@getSubscriptionCancel');

    /* Module Group Invoice */
    Route::group(['middleware' => 'payzone'], function() {
        Route::post('invoice/updatecondition', 'InvoiceController@doUpdateCondition');
        Route::post('invoice/updatecode', 'InvoiceController@doUpdateCode');
        Route::post('invoice/updatedesc', 'InvoiceController@doUpdateDescription');
        Route::post('invoice/updateamount', 'InvoiceController@doUpdateAmount');
        Route::get('invoice/project-{project_id}', 'Calculation\CalcController@getInvoiceAll');;
        Route::get('invoice/project-{project_id}/invoice-{invoice_id}', 'Calculation\CalcController@getInvoice');
        Route::get('invoice/project-{project_id}/term-invoice-{invoice_id}', 'Calculation\CalcController@getTermInvoice');
        Route::post('invoice/save', 'InvoiceController@doInvoiceVersionNew');
        Route::post('invoice/close', 'InvoiceController@doInvoiceClose');
        Route::post('invoice/pay', 'InvoiceController@doInvoicePay');
        Route::post('invoice/creditinvoice', 'InvoiceController@doCreditInvoiceNew');
        Route::post('invoice/invclose', 'InvoiceController@doInvoiceCloseAjax');
        Route::post('invoice/term/add', 'InvoiceController@doInvoiceNewTerm');
        Route::post('invoice/term/delete', 'InvoiceController@doInvoiceDeleteTerm');
        Route::get('invoice/project-{project_id}/invoice-version-{invoice_id}', function() {
            return view('calc.invoice_show_pdf');
        });
        Route::get('invoice/project-{project_id}/pdf-invoice-{invoice_id}', function() {
            return view('calc.invoice_show_final_pdf');
        });
        Route::get('invoice/project-{project_id}/invoice-{offer_id}/mail-preview', 'InvoiceController@getSendOfferPreview');
        Route::post('invoice/sendmail', 'InvoiceController@doSendOffer');
        Route::post('invoice/sendpost', 'InvoiceController@doSendPostOffer');
        Route::get('invoice/pdf/project-{project_id}/invoice-{invoice_id}', 'Calculation\CalcController@getInvoicePDF');
        Route::get('invoice/pdf/project-{project_id}/invoice-{invoice_id}/download', 'Calculation\CalcController@getInvoiceDownloadPDF');
        Route::get('invoice/pdf/project-{project_id}/term-invoice-{invoice_id}/download', 'Calculation\CalcController@getTermInvoiceDownloadPDF');
        Route::get('invoice/project-{project_id}/history-invoice-{invoice_id}', function() {
            return view('calc.invoice_version');
        });
    });

    /* Module Group Proposal */
    Route::group(['middleware' => 'payzone'], function() {
        Route::get('offerversions/project-{project_id}', 'Calculation\CalcController@getOfferAll');;
        Route::get('offer/project-{project_id}', 'Calculation\CalcController@getOffer');;
        Route::post('offer/project-{project_id}',                 'OfferController@doNewOffer');
        Route::get('offer/project-{project_id}/offer-{offer_id}', function() {
            return view('calc.offer_show_pdf');
        })->where('offer_id', '[0-9]+');;
        Route::get('offer/project-{project_id}/offer-{offer_id}/mail-preview', 'OfferController@getSendOfferPreview')->where('offer_id', '[0-9]+');;
        Route::post('offer/close',                                'OfferController@doOfferClose');
        Route::post('offer/sendmail',                             'OfferController@doSendOffer');
        Route::post('offer/sendpost',                             'OfferController@doSendPostOffer');
    });

    //TODO: move into namespaceCalculation
    Route::get('result/project-{project_id}', function() {
        return view('calc.result');
    })->middleware('payzone');

    /* Module Group Calculation */
    Route::group(['namespace' => $this->namespaceCalculation, 'middleware' => 'payzone'], function() {

        /* Routes by CalcController */
        Route::post('calculation/newchapter/{project_id}',        'CalcController@doNewChapter');
        Route::post('calculation/calc/newactivity/{chapter_id}',  'CalcController@doNewCalculationActivity')->where('chapter_id', '[0-9]+');
        Route::post('calculation/estim/newactivity/{chapter_id}', 'CalcController@doNewEstimateActivity')->where('chapter_id', '[0-9]+');
        Route::post('calculation/updatepart',                     'CalcController@doUpdatePart');
        Route::post('calculation/updatetax',                      'CalcController@doUpdateTax');
        Route::post('calculation/updateestimatetax',              'CalcController@doUpdateEstimateTax');
        Route::post('calculation/noteactivity',                   'CalcController@doUpdateNote');
        Route::post('calculation/deleteactivity',                 'CalcController@doDeleteActivity');
        Route::post('calculation/deletechapter',                  'CalcController@doDeleteChapter');
        Route::post('calculation/moveactivity',                   'CalcController@doMoveActivity');
        Route::post('calculation/movechapter',                    'CalcController@doMoveChapter');
        Route::post('calculation/activity/usetimesheet',          'CalcController@doUpdateUseTimesheet');

        /* Routes by FavoriteController */
        Route::post('favorite/newmaterial',                       'FavoriteController@doNewMaterial');
        Route::post('favorite/newequipment',                      'FavoriteController@doNewEquipment');
        Route::post('favorite/newlabor',                          'FavoriteController@doNewLabor');
        Route::post('favorite/deletematerial',                    'FavoriteController@doDeleteMaterial');
        Route::post('favorite/deleteequipment',                   'FavoriteController@doDeleteEquipment');
        Route::post('favorite/deletelabor',                       'FavoriteController@doDeleteLabor');
        Route::post('favorite/updatematerial',                    'FavoriteController@doUpdateMaterial');
        Route::post('favorite/updateequipment',                   'FavoriteController@doUpdateEquipment');
        Route::post('favorite/updatelabor',                       'FavoriteController@doUpdateLabor');
        Route::post('favorite/deleteactivity',                    'FavoriteController@doDeleteActivity');
        Route::post('favorite/noteactivity',                      'FavoriteController@doUpdateNote');
        Route::post('favorite/rename_activity',                   'FavoriteController@doRenameActivity');

        /* Calculation acions by calculation */
        Route::post('calculation/calc/newmaterial',               'CalcController@doNewCalculationMaterial');
        Route::post('calculation/calc/newequipment',              'CalcController@doNewCalculationEquipment');
        Route::post('calculation/calc/newlabor',                  'CalcController@doNewCalculationLabor');
        Route::post('calculation/calc/deletematerial',            'CalcController@doDeleteCalculationMaterial');
        Route::post('calculation/calc/deleteequipment',           'CalcController@doDeleteCalculationEquipment');
        Route::post('calculation/calc/deletelabor',               'CalcController@doDeleteCalculationLabor');
        Route::post('calculation/calc/updatematerial',            'CalcController@doUpdateCalculationMaterial');
        Route::post('calculation/calc/updateequipment',           'CalcController@doUpdateCalculationEquipment');
        Route::post('calculation/calc/updatelabor',               'CalcController@doUpdateCalculationLabor');
        Route::post('calculation/calc/savefav',                   'CalcController@doNewCalculationFavorite');
        Route::post('calculation/calc/rename_activity',           'CalcController@doRenameCalculationActivity');
        Route::post('calculation/calc/rename_chapter',            'CalcController@doRenameCalculationChapter');

        //TODO: own conrtoller
        /* Estimate acions by calculation */
        Route::post('calculation/estim/newmaterial',              'CalcController@doNewEstimateMaterial');
        Route::post('calculation/estim/newequipment',             'CalcController@doNewEstimateEquipment');
        Route::post('calculation/estim/newlabor',                 'CalcController@doNewEstimateLabor');
        Route::post('calculation/estim/deletematerial',           'CalcController@doDeleteEstimateMaterial');
        Route::post('calculation/estim/deleteequipment',          'CalcController@doDeleteEstimateEquipment');
        Route::post('calculation/estim/deletelabor',              'CalcController@doDeleteEstimateLabor');
        Route::post('calculation/estim/updatematerial',           'CalcController@doUpdateEstimateMaterial');
        Route::post('calculation/estim/updateequipment',          'CalcController@doUpdateEstimateEquipment');
        Route::post('calculation/estim/updatelabor',              'CalcController@doUpdateEstimateLabor');
        Route::post('calculation/estim/savefav',                  'CalcController@doNewEstimateFavorite');

        //TODO: rename
        /* Blancrow acions by calculation */
        Route::post('blancrow/newrow',                            'BlancController@doNewRow');
        Route::post('blancrow/updaterow',                         'BlancController@doUpdateRow');

        /* Calculation pages */
        Route::get('calculation/project-{project_id}',            'CalcController@getCalculation');
        Route::get('calculation/project-{project_id}/chapter-{chapter_id}/fav-{fav_id}', 'CalcController@getCalculationWithFavorite');
        Route::get('calculation/summary/project-{project_id}',    'CalcController@getCalculationSummary');
        Route::get('calculation/endresult/project-{project_id}',  'CalcController@getCalculationEndresult');
        Route::get('blancrow/project-{project_id}',               'BlancController@getBlanc');
        Route::get('estimate/project-{project_id}',               'CalcController@getEstimate');
        Route::get('estimate/project-{project_id}/chapter-{chapter_id}/fav-{fav_id}', 'CalcController@getEstimateWithFavorite');
        Route::get('estimate/summary/project-{project_id}',       'CalcController@getEstimateSummary');
        Route::get('estimate/endresult/project-{project_id}',     'CalcController@getEstimateEndresult');

        /* Estimate acions by estimate */
        Route::post('estimate/newlabor',                          'EstimController@doNewEstimateLabor');
        Route::post('estimate/newmaterial',                       'EstimController@doNewEstimateMaterial');
        Route::post('estimate/updatematerial',                    'EstimController@doUpdateEstimateMaterial');
        Route::post('estimate/deletematerial',                    'EstimController@doDeleteEstimateMaterial');
        Route::post('estimate/resetmaterial',                     'EstimController@doResetEstimateMaterial');
        Route::post('estimate/newequipment',                      'EstimController@doNewEstimateEquipment');
        Route::post('estimate/updateequipment',                   'EstimController@doUpdateEstimateEquipment');
        Route::post('estimate/deleteequipment',                   'EstimController@doDeleteEstimateEquipment');
        Route::post('estimate/resetequipment',                    'EstimController@doResetEstimateEquipment');
        Route::post('estimate/updatelabor',                       'EstimController@doUpdateEstimateLabor');
        Route::post('estimate/resetlabor',                        'EstimController@doResetEstimateLabor');
        Route::post('estimate/deletelabor',                       'EstimController@doDeleteEstimateLabor');

        /* Less pages */
        Route::get('less/project-{project_id}',                   'CalcController@getLess');
        Route::get('less/summary/project-{project_id}',           'CalcController@getLessSummary');
        Route::get('less/endresult/project-{project_id}',         'CalcController@getLessEndresult');
        Route::post('less/updatelabor',                           'LessController@doUpdateLabor');
        Route::post('less/updateequipment',                       'LessController@doUpdateEquipment');
        Route::post('less/updatematerial',                        'LessController@doUpdateMaterial');
        Route::post('less/resetlabor',                            'LessController@doResetLabor');
        Route::post('less/resetmaterial',                         'LessController@doResetMaterial');
        Route::post('less/resetequipment',                        'LessController@doResetEquipment');

        /* More pages */
        Route::get('more/project-{project_id}', 'CalcController@getMore');
        Route::get('more/project-{project_id}/chapter-{chapter_id}/fav-{fav_id}', 'MoreController@getMoreWithFavorite');
        Route::get('more/summary/project-{project_id}', 'CalcController@getMoreSummary');
        Route::get('more/endresult/project-{project_id}', 'CalcController@getMoreEndresult');
        Route::post('more/newmaterial',                          'MoreController@doNewMaterial');
        Route::post('more/newequipment',                         'MoreController@doNewEquipment');
        Route::post('more/newlabor',                             'MoreController@doNewLabor');
        Route::post('more/updatematerial',                       'MoreController@doUpdateMaterial');
        Route::post('more/updateequipment',                      'MoreController@doUpdateEquipment');
        Route::post('more/updatelabor',                          'MoreController@doUpdateLabor');
        Route::post('more/deletematerial',                       'MoreController@doDeleteMaterial');
        Route::post('more/deleteequipment',                      'MoreController@doDeleteEquipment');
        Route::post('more/deletelabor',                          'MoreController@doDeleteLabor');
        Route::post('more/newactivity/{chapter_id}',             'MoreController@doNewActivity')->where('chapter_id', '[0-9]+');
        Route::post('more/newchapter/{project_id}',              'MoreController@doNewChapter');
        Route::post('more/deletechapter',                        'MoreController@doDeleteChapter');
        Route::post('more/moveactivity',                         'MoreController@doMoveActivity');
    });

    //TODO: move into namespaceRelation
    Route::get('import', function() {
        return view('base.import');
    });

    Route::post('import/save',                  'Relation\ImportController');
    Route::get('relation/export',               'Relation\ExportController');

    /* Module Group Relation */
    Route::group(['namespace' => $this->namespaceRelation, 'middleware' => 'payzone'], function() {

        /* Relation pages */
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
        Route::get('relation-{relation_id}/edit',       'RelationController@getEdit');
        Route::get('relation-{relation_id}/delete',     'RelationController@getDelete');
        Route::get('relation-{relation_id}/contact/new','RelationController@getNewContact');
        Route::get('relation-{relation_id}/contact-{contact_id}/edit', 'RelationController@getEditContact');
        Route::get('relation-{relation_id}/convert',    'RelationController@getConvert');
        Route::get('mycompany',                         'RelationController@getMyCompany');
        Route::post('mycompany/iban/update',            'UserController@doUpdateIban');
        Route::post('mycompany/contact/new',            'RelationController@doMyCompanyNewContact');
        Route::get('mycompany/contact/new', function() {
            return view('user.mycompany_contact');
        });
        Route::post('mycompany/quickstart/address', 'ZipcodeController@getExternalAddress');

        Route::get('relation-{relation_id}/contact-{contact_id}/vcard', 'RelationController@downloadVCard');
        Route::post('relation/updatemycompany', 'RelationController@doUpdateMyCompany');
        Route::post('relation/newmycompany', 'RelationController@doNewMyCompany');
        Route::post('relation/logo/save', 'RelationController@doNewLogo');
        Route::post('relation/agreement/save', 'RelationController@doNewAgreement');
    });

    /* Project pages */
    Route::get('project/new',                                   'ProjectController@getNew')->middleware('payzone'); 
    Route::get('project/relation/{relation_id}',                'ProjectController@getRelationDetails')->middleware('payzone'); 
    Route::post('project/new',                                  'ProjectController@doNew')->middleware('payzone');
    Route::post('project/update',                               'ProjectController@doUpdate')->middleware('payzone');
    Route::post('project/update/note',                          'ProjectController@doUpdateNote')->middleware('payzone');
    Route::post('project/update/communication',                 'ProjectController@doCommunication')->middleware('payzone');
    Route::post('project/updatecalc',                           'ProjectController@doUpdateProfit')->middleware('payzone');
    Route::post('project/updateadvanced',                       'ProjectController@doUpdateAdvanced')->middleware('payzone');
    Route::get('project',                                       'ProjectController@getAll')->middleware('payzone');
    Route::get('project-{project_id}/edit',                     'ProjectController@getEdit')->middleware('payzone');
    Route::get('project-{project_id}/copy',                     'ProjectController@getProjectCopy')->middleware('payzone');
    Route::post('project/updateworkexecution',                  'ProjectController@doUpdateWorkExecution')->middleware('payzone');
    Route::post('project/updateworkcompletion',                 'ProjectController@doUpdateWorkCompletion')->middleware('payzone');
    Route::post('project/updateprojectclose',                   'ProjectController@doUpdateProjectClose')->middleware('payzone');
    Route::get('project-{project_id}/updateprojectdilapidated', 'ProjectController@getUpdateProjectDilapidated')->middleware('payzone');
    Route::get('project-{project_id}/packingslip',              'ProjectController@getPackingSlip')->middleware('payzone');
    Route::get('project-{project_id}/packlist',                 'ProjectController@getPackList')->middleware('payzone');
    Route::get('project-{project_id}/printoverview',            'ProjectController@getPrintOverview')->middleware('payzone');
    Route::post('resource/upload', 'ProjectController@doUploadProjectDocument');//TODO: rename url

    /* Cost pages */
    Route::group(['middleware' => 'payzone'], function() {
        Route::get('timesheet',                                     'CostController@getTimesheet')->middleware('payzone');
        Route::post('timesheet/new',                                'CostController@doNewTimesheet')->middleware('payzone');
        Route::post('timesheet/delete',                             'CostController@doDeleteTimesheet')->middleware('payzone');
        
        Route::get('timesheet/activity/{project_id}/{type}',        'CostController@getActivityByType')->where('type', '[0-9]+')->middleware('payzone');
        Route::get('purchase',                                      'CostController@getPurchase')->middleware('payzone');
        Route::post('purchase/new',                                 'CostController@doNewPurchase')->middleware('payzone');
        Route::post('purchase/delete',                              'CostController@doDeletePurchase')->middleware('payzone');
    });

    /* Module Group Product */
    Route::group(['namespace' => $this->namespaceProducts, 'middleware' => 'payzone'], function() {
 
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
});
