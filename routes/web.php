<?php

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

/* Application guest pages */
Route::group(['middleware' => ['guest', 'utm']], function() {
    Route::get('register',          'AuthController@getRegister');
    Route::get('login',             'AuthController@getLogin');
    Route::post('login',            'AuthController@doLogin');
    Route::post('register',         'AuthController@doRegister');
    Route::get('confirm/{token}',   'AuthController@doActivate')->where('token', '[0-9a-z]{40}');
    Route::post('password/reset',   'AuthController@doBlockPassword');
    Route::get('password/{token}',  'AuthController@getPasswordReset')->where('token', '[0-9a-z]{40}');
    Route::post('password/{token}', 'AuthController@doNewPassword')->where('token', '[0-9a-z]{40}');
    
    Route::get('ex-project-overview/{token}', 'ClientController@getClientPage')->where('token', '[0-9a-z]{40}');
    Route::post('ex-project-overview/{token}/update', 'ClientController@doUpdateCommunication')->where('token', '[0-9a-z]{40}');
    Route::get('ex-project-overview/{token}/done', 'ClientController@doOfferAccept')->where('token', '[0-9a-z]{40}');
});

/* Generic routes */
Route::get('about',   'GuestController@getAbout')->middleware('utm');

/* Oauth2 REST API */
Route::group(['prefix' => 'oauth2', 'middleware' => ['check-authorization-params', 'auth']], function() {
    Route::get('authorize',  'AuthController@getOauth2Authorize');
    Route::post('authorize', 'AuthController@doOauth2Authorize');
});

Route::post('oauth2/access_token', 'AuthController@doIssueAccessToken');

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

/* Support */
Route::post('support', 'SupportController@sendSupport');
Route::get('support',  'SupportController@getSupport');

/* Payment callbacks */
Route::post('payment/webhook/', 'PaymentController@doPaymentUpdate');

Route::group(['middleware' => 'auth'], function() {
    Route::get('/', 'HomeController')->name('dashboard');
    Route::get('admin/switch/back', 'AdminController@getSwitchSessionBack');
    Route::get('logout', 'AuthController@doLogout');
    Route::get('result/project-{project_id}', function(){
        return view('calc.result');
    })->middleware('payzone');
    Route::get('res-{resource_id}/download', 'ProjectController@downloadResource')->where('resource_id', '[0-9]+');
    Route::get('res-{resource_id}/delete', 'ProjectController@doDeleteResource')->where('resource_id', '[0-9]+');
    Route::post('resource/upload', 'ProjectController@doUploadProjectDocument');
    Route::get('myaccount', function() {
        return view('user.myaccount');
    });
    Route::get('import', function() {
        return view('base.import');
    });
    Route::get('get-help', function() {
        return view('base.get_help');
    });
    Route::post('import/save', 'AppsController@doImportRelation');
    Route::get('relation/export', 'AppsController@getExportRelation');
    Route::get('myaccount/deactivate', 'UserController@getMyAccountDeactivate');
    Route::get('myaccount/loaddemo', 'UserController@doLoadDemoProject');
    Route::get('myaccount/oauth/session/{client_id}/revoke', 'UserController@doRevokeApp');
    Route::post('myaccount/updateuser', 'UserController@doMyAccountUser');
    Route::post('myaccount/iban/new', 'UserController@doNewIban');
    Route::post('myaccount/security/update', 'UserController@doUpdateSecurity');
    Route::post('myaccount/preferences/update', 'UserController@doUpdatePreferences');
    Route::post('myaccount/notepad/save', 'UserController@doUpdateNotepad');

    Route::get('messagebox/message-{message}/read',   'MessageBoxController@doRead')->where('message', '[0-9]+');
    Route::get('messagebox/message-{message}/delete', 'MessageBoxController@doDelete')->where('message', '[0-9]+');
    Route::get('messagebox/message-{message}',        'MessageBoxController@getMessage')->where('message', '[0-9]+');
    Route::get('messagebox', function() {
        return view('user.messagebox');
    });
    Route::get('finance/overview', function() {
        return view('finance.overview');
    });
    Route::get('affiliate/5bdc2bbd-4021-4e12-9012-647385c28c05', function(){
        return view('user.affiliate');
    });

    /* Routes by PaymentController */
    Route::get('payment',                      'PaymentController@getPayment');
    Route::get('payment/order/{token}',        'PaymentController@getPaymentFinish')->where('token', '[0-9a-z]{40}');
    Route::post('payment/promocode',           'PaymentController@doCheckPromotionCode');
    Route::get('payment/increasefree',         'PaymentController@getPaymentFree');
    Route::get('payment/subscription/cancel',  'PaymentController@getSubscriptionCancel');

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
    Route::post('favorite/newmaterial',     'FavoriteController@doNewMaterial');
    Route::post('favorite/newequipment',    'FavoriteController@doNewEquipment');
    Route::post('favorite/newlabor',        'FavoriteController@doNewLabor');
    Route::post('favorite/deletematerial',  'FavoriteController@doDeleteMaterial');
    Route::post('favorite/deleteequipment', 'FavoriteController@doDeleteEquipment');
    Route::post('favorite/deletelabor',     'FavoriteController@doDeleteLabor');
    Route::post('favorite/updatematerial',  'FavoriteController@doUpdateMaterial');
    Route::post('favorite/updateequipment', 'FavoriteController@doUpdateEquipment');
    Route::post('favorite/updatelabor',     'FavoriteController@doUpdateLabor');
    Route::post('favorite/deleteactivity',  'FavoriteController@doDeleteActivity');
    Route::post('favorite/noteactivity',    'FavoriteController@doUpdateNote');
    Route::post('favorite/rename_activity', 'FavoriteController@doRenameActivity');

    /* Routes by InvoiceController */
    Route::post('invoice/updatecondition', 'InvoiceController@doUpdateCondition');
    Route::post('invoice/updatecode', 'InvoiceController@doUpdateCode');
    Route::post('invoice/updatedesc', 'InvoiceController@doUpdateDescription');
    Route::post('invoice/updateamount', 'InvoiceController@doUpdateAmount');
    Route::get('invoice/project-{project_id}', 'CalcController@getInvoiceAll')->middleware('payzone');
    Route::get('invoice/project-{project_id}/invoice-{invoice_id}', 'CalcController@getInvoice')->where('invoice_id', '[0-9]+');
    Route::get('invoice/project-{project_id}/term-invoice-{invoice_id}', 'CalcController@getTermInvoice')->where('invoice_id', '[0-9]+');
    Route::post('invoice/save', 'InvoiceController@doInvoiceVersionNew');
    Route::post('invoice/close', 'InvoiceController@doInvoiceClose');
    Route::post('invoice/pay', 'InvoiceController@doInvoicePay');
    Route::post('invoice/creditinvoice', 'InvoiceController@doCreditInvoiceNew');
    Route::post('invoice/invclose', 'InvoiceController@doInvoiceCloseAjax');
    Route::post('invoice/term/add', 'InvoiceController@doInvoiceNewTerm');
    Route::post('invoice/term/delete', 'InvoiceController@doInvoiceDeleteTerm');
    Route::get('invoice/project-{project_id}/invoice-version-{invoice_id}', function() {
        return View::make('calc.invoice_show_pdf');
    })->where('invoice_id', '[0-9]+');
    Route::get('invoice/project-{project_id}/pdf-invoice-{invoice_id}', function() {
        return View::make('calc.invoice_show_final_pdf');
    })->where('invoice_id', '[0-9]+');
    Route::get('invoice/project-{project_id}/invoice-{offer_id}/mail-preview', 'InvoiceController@getSendOfferPreview')->where('invoice_id', '[0-9]+')->middleware('payzone');
    Route::post('invoice/sendmail', 'InvoiceController@doSendOffer');
    Route::post('invoice/sendpost', 'InvoiceController@doSendPostOffer');

    Route::get('invoice/project-{project_id}/history-invoice-{invoice_id}', function() {
        return View::make('calc.invoice_version');
    })->where('invoice_id', '[0-9]+')->middleware('payzone');

    Route::get('offerversions/project-{project_id}', 'CalcController@getOfferAll')->middleware('payzone');
    Route::get('offer/project-{project_id}', 'CalcController@getOffer')->middleware('payzone');
    Route::post('offer/project-{project_id}', 'OfferController@doNewOffer');
    Route::get('offer/project-{project_id}/offer-{offer_id}', function() {
        return View::make('calc.offer_show_pdf');
    })->where('offer_id', '[0-9]+')->middleware('payzone');
    Route::get('offer/project-{project_id}/offer-{offer_id}/mail-preview', 'OfferController@getSendOfferPreview')->where('offer_id', '[0-9]+')->middleware('payzone');
    Route::post('offer/close', 'OfferController@doOfferClose');
    Route::post('offer/sendmail', 'OfferController@doSendOffer');
    Route::post('offer/sendpost', 'OfferController@doSendPostOffer');

    Route::get('invoice/pdf/project-{project_id}/invoice-{invoice_id}', 'CalcController@getInvoicePDF')->middleware('payzone');
    Route::get('invoice/pdf/project-{project_id}/invoice-{invoice_id}/download', 'CalcController@getInvoiceDownloadPDF')->middleware('payzone');
    Route::get('invoice/pdf/project-{project_id}/term-invoice-{invoice_id}/download', 'CalcController@getTermInvoiceDownloadPDF')->middleware('payzone');

    /* Calculation acions by calculation */
    Route::post('calculation/calc/newmaterial',     'CalcController@doNewCalculationMaterial');
    Route::post('calculation/calc/newequipment',    'CalcController@doNewCalculationEquipment');
    Route::post('calculation/calc/newlabor',        'CalcController@doNewCalculationLabor');
    Route::post('calculation/calc/deletematerial',  'CalcController@doDeleteCalculationMaterial');
    Route::post('calculation/calc/deleteequipment', 'CalcController@doDeleteCalculationEquipment');
    Route::post('calculation/calc/deletelabor',     'CalcController@doDeleteCalculationLabor');
    Route::post('calculation/calc/updatematerial',  'CalcController@doUpdateCalculationMaterial');
    Route::post('calculation/calc/updateequipment', 'CalcController@doUpdateCalculationEquipment');
    Route::post('calculation/calc/updatelabor',     'CalcController@doUpdateCalculationLabor');
    Route::post('calculation/calc/savefav',         'CalcController@doNewCalculationFavorite');
    Route::post('calculation/calc/rename_activity', 'CalcController@doRenameCalculationActivity');
    Route::post('calculation/calc/rename_chapter',  'CalcController@doRenameCalculationChapter');

    /* Estimate acions by calculation */
    Route::post('calculation/estim/newmaterial',     'CalcController@doNewEstimateMaterial');
    Route::post('calculation/estim/newequipment',    'CalcController@doNewEstimateEquipment');
    Route::post('calculation/estim/newlabor',        'CalcController@doNewEstimateLabor');
    Route::post('calculation/estim/deletematerial',  'CalcController@doDeleteEstimateMaterial');
    Route::post('calculation/estim/deleteequipment', 'CalcController@doDeleteEstimateEquipment');
    Route::post('calculation/estim/deletelabor',     'CalcController@doDeleteEstimateLabor');
    Route::post('calculation/estim/updatematerial',  'CalcController@doUpdateEstimateMaterial');
    Route::post('calculation/estim/updateequipment', 'CalcController@doUpdateEstimateEquipment');
    Route::post('calculation/estim/updatelabor',     'CalcController@doUpdateEstimateLabor');
    Route::post('calculation/estim/savefav',         'CalcController@doNewEstimateFavorite');

    /* Blancrow acions by calculation */
    Route::post('blancrow/newrow',    'BlancController@doNewRow');
    Route::post('blancrow/updaterow', 'BlancController@doUpdateRow');

    /* Calculation pages */
    Route::get('calculation/project-{project_id}', 'CalcController@getCalculation')->middleware('payzone');
    Route::get('calculation/project-{project_id}/chapter-{chapter_id}/fav-{fav_id}', 'CalcController@getCalculationWithFavorite')->middleware('payzone');
    Route::get('calculation/summary/project-{project_id}', 'CalcController@getCalculationSummary')->middleware('payzone');
    Route::get('calculation/endresult/project-{project_id}', 'CalcController@getCalculationEndresult')->middleware('payzone');
    Route::get('blancrow/project-{project_id}', 'BlancController@getBlanc')->middleware('payzone');
    Route::get('estimate/project-{project_id}', 'CalcController@getEstimate')->middleware('payzone');
    Route::get('estimate/project-{project_id}/chapter-{chapter_id}/fav-{fav_id}', 'CalcController@getEstimateWithFavorite')->middleware('payzone');
    Route::get('estimate/summary/project-{project_id}', 'CalcController@getEstimateSummary')->middleware('payzone');
    Route::get('estimate/endresult/project-{project_id}', 'CalcController@getEstimateEndresult')->middleware('payzone');

    /* Estimate acions by estimate */
    Route::post('estimate/newlabor',        'EstimController@doNewEstimateLabor');
    Route::post('estimate/newmaterial',     'EstimController@doNewEstimateMaterial');
    Route::post('estimate/updatematerial',  'EstimController@doUpdateEstimateMaterial');
    Route::post('estimate/deletematerial',  'EstimController@doDeleteEstimateMaterial');
    Route::post('estimate/resetmaterial',   'EstimController@doResetEstimateMaterial');
    Route::post('estimate/newequipment',    'EstimController@doNewEstimateEquipment');
    Route::post('estimate/updateequipment', 'EstimController@doUpdateEstimateEquipment');
    Route::post('estimate/deleteequipment', 'EstimController@doDeleteEstimateEquipment');
    Route::post('estimate/resetequipment',  'EstimController@doResetEstimateEquipment');
    Route::post('estimate/updatelabor',     'EstimController@doUpdateEstimateLabor');
    Route::post('estimate/resetlabor',      'EstimController@doResetEstimateLabor');
    Route::post('estimate/deletelabor',     'EstimController@doDeleteEstimateLabor');

    /* Less pages */
    Route::get('less/project-{project_id}',           'CalcController@getLess')->middleware('payzone');
    Route::get('less/summary/project-{project_id}',   'CalcController@getLessSummary')->middleware('payzone');
    Route::get('less/endresult/project-{project_id}', 'CalcController@getLessEndresult')->middleware('payzone');
    Route::post('less/updatelabor',                   'LessController@doUpdateLabor');
    Route::post('less/updateequipment',               'LessController@doUpdateEquipment');
    Route::post('less/updatematerial',                'LessController@doUpdateMaterial');
    Route::post('less/resetlabor',                    'LessController@doResetLabor');
    Route::post('less/resetmaterial',                 'LessController@doResetMaterial');
    Route::post('less/resetequipment',                'LessController@doResetEquipment');

    /* More pages */
    Route::get('more/project-{project_id}', 'CalcController@getMore')->middleware('payzone');
    Route::get('more/project-{project_id}/chapter-{chapter_id}/fav-{fav_id}', 'MoreController@getMoreWithFavorite')->middleware('payzone');
    Route::get('more/summary/project-{project_id}', 'CalcController@getMoreSummary')->middleware('payzone');
    Route::get('more/endresult/project-{project_id}', 'CalcController@getMoreEndresult')->middleware('payzone');
    Route::post('more/newmaterial', 'MoreController@doNewMaterial');
    Route::post('more/newequipment', 'MoreController@doNewEquipment');
    Route::post('more/newlabor', 'MoreController@doNewLabor');
    Route::post('more/updatematerial', 'MoreController@doUpdateMaterial');
    Route::post('more/updateequipment', 'MoreController@doUpdateEquipment');
    Route::post('more/updatelabor', 'MoreController@doUpdateLabor');
    Route::post('more/deletematerial', 'MoreController@doDeleteMaterial');
    Route::post('more/deleteequipment', 'MoreController@doDeleteEquipment');
    Route::post('more/deletelabor', 'MoreController@doDeleteLabor');
    Route::post('more/newactivity/{chapter_id}', 'MoreController@doNewActivity')->where('chapter_id', '[0-9]+');
    Route::post('more/newchapter/{project_id}', 'MoreController@doNewChapter');
    Route::post('more/deletechapter', 'MoreController@doDeleteChapter');
    Route::post('more/moveactivity', 'MoreController@doMoveActivity');

    /* Relation pages */
    Route::get('relation/new', 'RelationController@getNew')->middleware('payzone');
    Route::post('relation/new', 'RelationController@doNew');
    Route::post('relation/update', 'RelationController@doUpdate');
    Route::post('relation/contact/new', 'RelationController@doNewContact');
    Route::post('relation/contact/update', 'RelationController@doUpdateContact');
    Route::post('relation/contact/delete', 'RelationController@doDeleteContact');
    Route::post('relation/iban/update', 'RelationController@doUpdateIban');
    Route::post('relation/iban/new', 'RelationController@doNewIban');
    Route::post('relation/updatecalc', 'RelationController@doUpdateProfit');
    Route::get('relation', 'RelationController@getAll')->middleware('payzone');
    Route::get('relation-{relation_id}/edit', 'RelationController@getEdit')->where('relation_id', '[0-9]+')->middleware('payzone');
    Route::get('relation-{relation_id}/delete', 'RelationController@getDelete')->where('relation_id', '[0-9]+')->middleware('payzone');
    Route::get('relation-{relation_id}/contact/new','RelationController@getNewContact')->where('relation_id', '[0-9]+')->middleware('payzone');
    Route::get('relation-{relation_id}/contact-{contact_id}/edit', 'RelationController@getEditContact')->where('relation_id', '[0-9]+')->where('contact_id', '[0-9]+')->middleware('payzone');
    Route::get('relation-{relation_id}/convert', 'RelationController@getConvert')->where('relation_id', '[0-9]+')->middleware('payzone');
    Route::get('mycompany', 'RelationController@getMyCompany')->middleware('payzone');
    Route::post('mycompany/iban/update', 'UserController@doUpdateIban');
    Route::post('mycompany/contact/new', 'RelationController@doMyCompanyNewContact');
    Route::get('mycompany/contact/new', function() {
        return view('user.mycompany_contact');
    });
    Route::post('mycompany/quickstart/address', 'ZipcodeController@getExternalAddress');

    Route::get('relation-{relation_id}/contact-{contact_id}/vcard', 'RelationController@downloadVCard')->where('relation_id', '[0-9]+')->where('contact_id', '[0-9]+');
    Route::post('relation/updatemycompany', 'RelationController@doUpdateMyCompany');
    Route::post('relation/newmycompany', 'RelationController@doNewMyCompany');
    Route::post('relation/logo/save', 'RelationController@doNewLogo');
    Route::post('relation/agreement/save', 'RelationController@doNewAgreement');

    /* Wholesale */
    Route::get('wholesale',                       'WholesaleController@getAll')->middleware('payzone');
    Route::get('wholesale/new',                   'WholesaleController@getNew')->middleware('payzone');
    Route::post('wholesale/new',                  'WholesaleController@doNew');
    Route::post('wholesale/update',               'WholesaleController@doUpdate');
    Route::get('wholesale-{wholesale_id}/edit',   'WholesaleController@getEdit')->where('wholesale_id', '[0-9]+')->middleware('payzone');
    Route::get('wholesale-{wholesale_id}/show',   'WholesaleController@getShow')->where('wholesale_id', '[0-9]+')->middleware('payzone');
    Route::post('wholesale/iban/update',          'WholesaleController@doUpdateIban');
    Route::get('wholesale-{wholesale_id}/delete', 'WholesaleController@getDelete')->where('wholesale_id', '[0-9]+')->middleware('payzone');

    /* Project pages */
    Route::get('project/new',                                   'ProjectController@getNew')->middleware('payzone'); 
    Route::get('project/relation/{relation_id}',                'ProjectController@getRelationDetails')->middleware('payzone'); 
    Route::post('project/new',                                  'ProjectController@doNew');
    Route::post('project/update',                               'ProjectController@doUpdate');
    Route::post('project/update/note',                          'ProjectController@doUpdateNote');
    Route::post('project/update/communication',                 'ProjectController@doCommunication');
    Route::post('project/updatecalc',                           'ProjectController@doUpdateProfit');
    Route::post('project/updateadvanced',                       'ProjectController@doUpdateAdvanced');
    Route::get('project',                                       'ProjectController@getAll')->middleware('payzone');
    Route::get('project-{project_id}/edit',                     'ProjectController@getEdit')->middleware('payzone');
    Route::get('project-{project_id}/copy',                     'ProjectController@getProjectCopy')->middleware('payzone');
    Route::post('project/updateworkexecution',                  'ProjectController@doUpdateWorkExecution');
    Route::post('project/updateworkcompletion',                 'ProjectController@doUpdateWorkCompletion');
    Route::post('project/updateprojectclose',                   'ProjectController@doUpdateProjectClose');
    Route::get('project-{project_id}/updateprojectdilapidated', 'ProjectController@getUpdateProjectDilapidated');
    Route::get('project-{project_id}/packingslip',              'ProjectController@getPackingSlip');
    Route::get('project-{project_id}/packlist',                 'ProjectController@getPackList');
    Route::get('project-{project_id}/printoverview',            'ProjectController@getPrintOverview');

    /* Cost pages */
    Route::get('timesheet',                              'CostController@getTimesheet')->middleware('payzone');
    Route::post('timesheet/new',                         'CostController@doNewTimesheet');
    Route::post('timesheet/delete',                      'CostController@doDeleteTimesheet');
    Route::get('timesheet/activity/{project_id}/{type}', 'CostController@getActivityByType')->where('type', '[0-9]+')->middleware('payzone');
    Route::get('purchase',                               'CostController@getPurchase')->middleware('payzone');
    Route::post('purchase/new',                          'CostController@doNewPurchase');
    Route::post('purchase/delete',                       'CostController@doDeletePurchase');

    /* Material database */
    Route::get('material',                    'MaterialController@getList')->middleware('payzone');
    Route::get('material/subcat/{type}/{id}', 'MaterialController@getListSubcat');
    Route::post('material/search',            'MaterialController@doSearch');
    Route::post('material/newmaterial',       'MaterialController@doNew');
    Route::post('material/updatematerial',    'MaterialController@doUpdate');
    Route::post('material/deletematerial',    'MaterialController@doDelete');
    Route::post('material/favorite',          'MaterialController@doFavorite');
    Route::post('material/element/new',       'MaterialController@doNewElement');
    Route::post('material/upload',            'MaterialController@doUploadCSV');
});
