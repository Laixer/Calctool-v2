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

/* Application guest pages */
Route::group(['middleware' => 'guest'], function() {
	Route::get('register', 'AuthController@getRegister');
	Route::get('login', 'AuthController@getLogin');
	Route::post('login', 'AuthController@doLogin');
	Route::post('register', 'AuthController@doRegister');
	Route::get('confirm/{api}/{token}', 'AuthController@doActivate')->where('api', '[0-9a-z]{32}')->where('token', '[0-9a-z]{40}');
	Route::post('password/reset', 'AuthController@doBlockPassword');
	Route::get('password/{api}/{token}', 'AuthController@getPasswordReset')->where('api', '[0-9a-z]{32}')->where('token', '[0-9a-z]{40}');
	Route::post('password/{api}/{token}', 'AuthController@doNewPassword')->where('api', '[0-9a-z]{32}')->where('token', '[0-9a-z]{40}');
	
	Route::get('ex-project-overview/{token}', 'ClientController@getClientPage')->where('token', '[0-9a-z]{40}');
	Route::post('ex-project-overview/{token}/update', 'ClientController@doUpdateCommunication')->where('token', '[0-9a-z]{40}');
	Route::get('ex-project-overview/{token}/done', 'ClientController@doOfferAccept')->where('token', '[0-9a-z]{40}');
});

/* Frontend API */
Route::post('api/v1/register/usernamecheck', 'ApiController@doCheckUsernameEXist');

Route::group(['prefix' => 'api/v1', 'middleware' => 'auth'], function() {
	Route::get('/', 'ApiController@getApiRoot');
	Route::get('/projects', 'ApiController@getProjects');

	Route::get('/timesheet', 'ApiController@getTimesheet');
	Route::post('/timesheet/delete', 'ApiController@doTimesheetDelete');
	Route::post('/timesheet/new', 'ApiController@doTimesheetNew');

	Route::get('/purchase', 'ApiController@getPurchase');
	Route::post('/purchase/delete', 'ApiController@doPurchaseDelete');
	Route::post('/purchase/new', 'ApiController@doPurchaseNew');
});

/* Generic routes */
Route::get('about', 'GenericController@getAbout');
Route::get('faq', 'GenericController@getFaq');
Route::get('terms-and-conditions', 'GenericController@getTerms');
Route::get('privacy-policy', 'GenericController@getPrivacy');
Route::get('support', 'GenericController@getSupport');

/* Oauth2 REST API */
Route::group(['prefix' => 'oauth2', 'middleware' => ['check-authorization-params', 'auth']], function() {
	Route::get('authorize', 'AuthController@getOauth2Authorize');
	Route::post('authorize', 'AuthController@doOauth2Authorize');
});

Route::post('oauth2/access_token', function() {
	return response()->json(Authorizer::issueAccessToken());
});

Route::group(['prefix' => 'oauth2/rest', 'middleware' => 'oauth'], function() {
	/* Owner rest functions */
	Route::get('user', 'AuthController@getRestUser');
	Route::get('projects', 'AuthController@getRestUserProjects');
	Route::get('relations', 'AuthController@getRestUserRelations');

	/* Internal rest functions */
	Route::get('internal/user_all', 'AuthController@getRestAllUsers');
	Route::get('internal/project_all', 'AuthController@getRestAllProjects');
});	

/* Feedback/Support */
Route::post('feedback', 'FeedbackController@send');
Route::post('support', 'FeedbackController@sendSupport');

/* Payment callbacks */
Route::post('payment/webhook/', 'UserController@doPaymentUpdate');
Route::get('hidenextstep', 'AuthController@doHideNextStep');//TODO remove?

//TODO hack
Route::get('c4586v34674v4&vwasrt/footer_pdf', function() {
	return view('calc.footer_pdf');
});

Route::group(['middleware' => 'auth'], function() {
	Route::get('/', ['middleware' => 'payzone', function() {
		if (Auth::user()->isSystem()) {
			return redirect('/admin');
		}
		
		return view('base.home');
	}]);
	Route::get('admin/switch/back', 'AdminController@getSwitchSessionBack');
	Route::get('logout', 'AuthController@doLogout');
	Route::get('result/project-{project_id}', function(){
		return view('calc.result');
	})->where('project_id', '[0-9]+')->middleware('payzone');
	Route::get('res-{resource_id}/download', 'ProjectController@downloadResource')->where('resource_id', '[0-9]+');
	Route::get('apps', 'AppsController@getAppsDashboard');
	Route::get('myaccount', function() {
		return view('user.myaccount');
	});
	Route::get('myaccount/telegram', function() {
		return view('user.myaccount_telegram');
	});
	Route::get('myaccount/telegram/unchain', 'UserController@getMyAccountTelegramUnchain');
	Route::get('myaccount/deactivate', 'UserController@getMyAccountDeactivate');
	Route::get('myaccount/loaddemo', 'UserController@doLoadDemoProject');
	Route::get('myaccount/oauth/session/{client_id}/revoke', 'UserController@doRevokeApp');
	Route::post('myaccount/telegram/update', 'UserController@doMyAccountTelegramUpdate');
	Route::post('myaccount/updateuser', 'UserController@doMyAccountUser');
	Route::post('myaccount/iban/new', 'UserController@doNewIban');
	Route::post('myaccount/security/update', 'UserController@doUpdateSecurity');
	Route::post('myaccount/preferences/update', 'UserController@doUpdatePreferences');
	Route::post('myaccount/notepad/save', 'UserController@doUpdateNotepad');

	Route::get('messagebox/message-{message}/read', 'MessageBoxController@doRead')->where('message', '[0-9]+');
	Route::get('messagebox/message-{message}/delete', 'MessageBoxController@doDelete')->where('message', '[0-9]+');
	Route::get('messagebox/message-{message}', 'MessageBoxController@getMessage')->where('message', '[0-9]+');
	Route::get('messagebox', function() {
		return view('user.messagebox');
	});

	Route::get('payment', 'UserController@getPayment');
	Route::get('payment/order/{token}', 'UserController@getPaymentFinish')->where('token', '[0-9a-z]{40}');
	Route::post('payment/promocode', 'UserController@doCheckPromotionCode');
	Route::get('payment/increasefree', 'UserController@getPaymentFree');

	/* Actions by calculation */
	Route::post('calculation/newchapter/{project_id}', 'CalcController@doNewChapter')->where('project_id', '[0-9]+');
	Route::post('calculation/calc/newactivity/{chapter_id}', 'CalcController@doNewCalculationActivity')->where('chapter_id', '[0-9]+');
	Route::post('calculation/estim/newactivity/{chapter_id}', 'CalcController@doNewEstimateActivity')->where('chapter_id', '[0-9]+');
	Route::post('calculation/updatepart', 'CalcController@doUpdatePart');
	Route::post('calculation/updatetax', 'CalcController@doUpdateTax');
	Route::post('calculation/updateestimatetax', 'CalcController@doUpdateEstimateTax');
	Route::post('calculation/noteactivity', 'CalcController@doUpdateNote');
	Route::post('calculation/deleteactivity', 'CalcController@doDeleteActivity');
	Route::post('calculation/deletechapter', 'CalcController@doDeleteChapter');
	Route::post('calculation/activity/usetimesheet', 'CalcController@doUpdateUseTimesheet');

	Route::post('invoice/updatecondition', 'InvoiceController@doUpdateCondition');
	Route::post('invoice/updatecode', 'InvoiceController@doUpdateCode');
	Route::post('invoice/updatedesc', 'InvoiceController@doUpdateDescription');
	Route::post('invoice/updateamount', 'InvoiceController@doUpdateAmount');
	Route::get('invoice/project-{project_id}', 'CalcController@getInvoiceAll')->where('project_id', '[0-9]+')->middleware('payzone');
	Route::get('invoice/project-{project_id}/invoice-{invoice_id}', 'CalcController@getInvoice')->where('project_id', '[0-9]+')->where('invoice_id', '[0-9]+');
	Route::get('invoice/project-{project_id}/term-invoice-{invoice_id}', 'CalcController@getTermInvoice')->where('project_id', '[0-9]+')->where('invoice_id', '[0-9]+');
	Route::post('invoice/save', 'InvoiceController@doInvoiceVersionNew');
	Route::post('invoice/close', 'InvoiceController@doInvoiceClose');
	Route::post('invoice/pay', 'InvoiceController@doInvoicePay');
	Route::post('invoice/invclose', 'InvoiceController@doInvoiceCloseAjax');
	Route::post('invoice/term/add', 'InvoiceController@doInvoiceNewTerm');
	Route::post('invoice/term/delete', 'InvoiceController@doInvoiceDeleteTerm');
	Route::get('invoice/project-{project_id}/invoice-version-{invoice_id}', function() {
		return View::make('calc.invoice_show_pdf');
	})->where('project_id', '[0-9]+')->where('invoice_id', '[0-9]+');
	Route::get('invoice/project-{project_id}/pdf-invoice-{invoice_id}', function() {
		return View::make('calc.invoice_show_final_pdf');
	})->where('project_id', '[0-9]+')->where('invoice_id', '[0-9]+');
	Route::get('invoice/project-{project_id}/invoice-{offer_id}/mail-preview', 'InvoiceController@getSendOfferPreview')->where('project_id', '[0-9]+')->where('invoice_id', '[0-9]+')->middleware('payzone');
	Route::post('invoice/sendmail', 'InvoiceController@doSendOffer');
	Route::post('invoice/sendpost', 'InvoiceController@doSendPostOffer');

	Route::get('invoice/project-{project_id}/history-invoice-{invoice_id}', function() {
		return View::make('calc.invoice_version');
	})->where('project_id', '[0-9]+')->where('invoice_id', '[0-9]+')->middleware('payzone');

	Route::get('offerversions/project-{project_id}', 'CalcController@getOfferAll')->where('project_id', '[0-9]+')->middleware('payzone');
	Route::get('offer/project-{project_id}', 'CalcController@getOffer')->where('project_id', '[0-9]+')->middleware('payzone');
	Route::post('offer/project-{project_id}', 'OfferController@doNewOffer');
	Route::get('offer/project-{project_id}/offer-{offer_id}', function() {
		return View::make('calc.offer_show_pdf');
	})->where('project_id', '[0-9]+')->where('offer_id', '[0-9]+')->middleware('payzone');
	Route::get('offer/project-{project_id}/offer-{offer_id}/mail-preview', 'OfferController@getSendOfferPreview')->where('project_id', '[0-9]+')->where('offer_id', '[0-9]+')->middleware('payzone');
	Route::post('offer/close', 'OfferController@doOfferClose');
	Route::post('offer/sendmail', 'OfferController@doSendOffer');
	Route::post('offer/sendpost', 'OfferController@doSendPostOffer');

	Route::get('invoice/pdf/project-{project_id}/invoice-{invoice_id}', 'CalcController@getInvoicePDF')->where('project_id', '[0-9]+')->middleware('payzone');
	Route::get('invoice/pdf/project-{project_id}/invoice-{invoice_id}/download', 'CalcController@getInvoiceDownloadPDF')->where('project_id', '[0-9]+')->middleware('payzone');
	Route::get('invoice/pdf/project-{project_id}/term-invoice-{invoice_id}/download', 'CalcController@getTermInvoiceDownloadPDF')->where('project_id', '[0-9]+')->middleware('payzone');

	/* Calculation acions by calculation */
	Route::post('calculation/calc/newmaterial', 'CalcController@doNewCalculationMaterial');
	Route::post('calculation/calc/newequipment', 'CalcController@doNewCalculationEquipment');
	Route::post('calculation/calc/newlabor', 'CalcController@doNewCalculationLabor');
	Route::post('calculation/calc/deletematerial', 'CalcController@doDeleteCalculationMaterial');
	Route::post('calculation/calc/deleteequipment', 'CalcController@doDeleteCalculationEquipment');
	Route::post('calculation/calc/deletelabor', 'CalcController@doDeleteCalculationLabor');
	Route::post('calculation/calc/updatematerial', 'CalcController@doUpdateCalculationMaterial');
	Route::post('calculation/calc/updateequipment', 'CalcController@doUpdateCalculationEquipment');
	Route::post('calculation/calc/updatelabor', 'CalcController@doUpdateCalculationLabor');

	/* Estimate acions by calculation */
	Route::post('calculation/estim/newmaterial', 'CalcController@doNewEstimateMaterial');
	Route::post('calculation/estim/newequipment', 'CalcController@doNewEstimateEquipment');
	Route::post('calculation/estim/newlabor', 'CalcController@doNewEstimateLabor');
	Route::post('calculation/estim/deletematerial', 'CalcController@doDeleteEstimateMaterial');
	Route::post('calculation/estim/deleteequipment', 'CalcController@doDeleteEstimateEquipment');
	Route::post('calculation/estim/deletelabor', 'CalcController@doDeleteEstimateLabor');
	Route::post('calculation/estim/updatematerial', 'CalcController@doUpdateEstimateMaterial');
	Route::post('calculation/estim/updateequipment', 'CalcController@doUpdateEstimateEquipment');
	Route::post('calculation/estim/updatelabor', 'CalcController@doUpdateEstimateLabor');

	/* Blancrow acions by calculation */
	Route::post('blancrow/newrow', 'BlancController@doNewRow');
	Route::post('blancrow/updaterow', 'BlancController@doUpdateRow');

	/* Calculation pages */
	Route::get('calculation/project-{project_id}', 'CalcController@getCalculation')->where('project_id', '[0-9]+')->middleware('payzone');
	Route::get('calculation/summary/project-{project_id}', 'CalcController@getCalculationSummary')->where('project_id', '[0-9]+')->middleware('payzone');
	Route::get('calculation/endresult/project-{project_id}', 'CalcController@getCalculationEndresult')->where('project_id', '[0-9]+')->middleware('payzone');
	Route::get('blancrow/project-{project_id}', 'BlancController@getBlanc')->where('project_id', '[0-9]+')->middleware('payzone');
	Route::get('estimate/project-{project_id}', 'CalcController@getEstimate')->where('project_id', '[0-9]+')->middleware('payzone');
	Route::get('estimate/summary/project-{project_id}', 'CalcController@getEstimateSummary')->where('project_id', '[0-9]+')->middleware('payzone');
	Route::get('estimate/endresult/project-{project_id}', 'CalcController@getEstimateEndresult')->where('project_id', '[0-9]+')->middleware('payzone');

	/* Estimate acions by estimate */
	Route::post('estimate/newlabor', 'EstimController@doNewEstimateLabor');
	Route::post('estimate/newmaterial', 'EstimController@doNewEstimateMaterial');
	Route::post('estimate/updatematerial', 'EstimController@doUpdateEstimateMaterial');
	Route::post('estimate/deletematerial', 'EstimController@doDeleteEstimateMaterial');
	Route::post('estimate/resetmaterial', 'EstimController@doResetEstimateMaterial');
	Route::post('estimate/newequipment', 'EstimController@doNewEstimateEquipment');
	Route::post('estimate/updateequipment', 'EstimController@doUpdateEstimateEquipment');
	Route::post('estimate/deleteequipment', 'EstimController@doDeleteEstimateEquipment');
	Route::post('estimate/resetequipment', 'EstimController@doResetEstimateEquipment');
	Route::post('estimate/updatelabor', 'EstimController@doUpdateEstimateLabor');
	Route::post('estimate/resetlabor', 'EstimController@doResetEstimateLabor');
	Route::post('estimate/deletelabor', 'EstimController@doDeleteEstimateLabor');

	/* Less pages */
	Route::get('less/project-{project_id}', 'CalcController@getLess')->where('project_id', '[0-9]+')->middleware('payzone');
	Route::get('less/summary/project-{project_id}', 'CalcController@getLessSummary')->where('project_id', '[0-9]+')->middleware('payzone');
	Route::get('less/endresult/project-{project_id}', 'CalcController@getLessEndresult')->where('project_id', '[0-9]+')->middleware('payzone');
	Route::post('less/updatelabor', 'LessController@doUpdateLabor');
	Route::post('less/updateequipment', 'LessController@doUpdateEquipment');
	Route::post('less/updatematerial', 'LessController@doUpdateMaterial');
	Route::post('less/resetlabor', 'LessController@doResetLabor');
	Route::post('less/resetmaterial', 'LessController@doResetMaterial');
	Route::post('less/resetequipment', 'LessController@doResetEquipment');

	/* More pages */
	Route::get('more/project-{project_id}', 'CalcController@getMore')->middleware('payzone');
	Route::get('more/summary/project-{project_id}', 'CalcController@getMoreSummary')->where('project_id', '[0-9]+')->middleware('payzone');
	Route::get('more/endresult/project-{project_id}', 'CalcController@getMoreEndresult')->where('project_id', '[0-9]+')->middleware('payzone');
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
	Route::post('more/newchapter/{project_id}', 'MoreController@doNewChapter')->where('project_id', '[0-9]+');
	Route::post('more/deletechapter', 'MoreController@doDeleteChapter');

	/* Relation pages */
	Route::get('relation/new', 'RelationController@getNew')->middleware('payzone');
	Route::post('relation/new', 'RelationController@doNew');
	Route::post('relation/update', 'RelationController@doUpdate');
	Route::post('relation/contact/new', 'RelationController@doNewContact');
	Route::post('relation/contact/update', 'RelationController@doUpdateContact');
	Route::post('relation/contact/delete', 'RelationController@doDeleteContact');
	Route::post('relation/iban/update', 'RelationController@doUpdateIban');
	Route::post('relation/iban/new', 'RelationController@doNewIban');
	Route::get('relation', 'RelationController@getAll')->middleware('payzone');
	Route::get('relation-{relation_id}/edit', 'RelationController@getEdit')->where('relation_id', '[0-9]+')->middleware('payzone');
	Route::get('relation-{relation_id}/delete', 'RelationController@getDelete')->where('relation_id', '[0-9]+')->middleware('payzone');
	Route::get('relation-{relation_id}/contact/new','RelationController@getNewContact')->where('relation_id', '[0-9]+')->middleware('payzone');
	Route::get('relation-{relation_id}/contact-{contact_id}/edit', 'RelationController@getEditContact')->where('relation_id', '[0-9]+')->where('contact_id', '[0-9]+')->middleware('payzone');
	Route::get('mycompany', 'RelationController@getMyCompany')->middleware('payzone');
	Route::post('mycompany/iban/update', 'UserController@doUpdateIban');
	Route::post('mycompany/contact/new', 'RelationController@doMyCompanyNewContact');
	Route::get('mycompany/contact/new', function() {
		return view('user.mycompany_contact');
	});
	Route::post('mycompany/quickstart', 'QuickstartController@doNewMyCompanyQuickstart');
	Route::post('mycompany/cashbook/account/new', 'CashbookController@doNewAccount');
	Route::post('mycompany/cashbook/new', 'CashbookController@doNewCashRow');
	Route::post('mycompany/quickstart/address', 'QuickstartController@getExternalAddress');

	Route::get('relation-{relation_id}/contact-{contact_id}/vcard', 'RelationController@downloadVCard')->where('relation_id', '[0-9]+')->where('contact_id', '[0-9]+');
	Route::post('relation/updatemycompany', 'RelationController@doUpdateMyCompany');
	Route::post('relation/newmycompany', 'RelationController@doNewMyCompany');
	Route::post('relation/logo/save', 'RelationController@doNewLogo');

	/* Wholesale */
	Route::get('wholesale', 'WholesaleController@getAll')->middleware('payzone');
	Route::get('wholesale/new', 'WholesaleController@getNew')->middleware('payzone');
	Route::post('wholesale/new', 'WholesaleController@doNew');
	Route::post('wholesale/update', 'WholesaleController@doUpdate');
	Route::get('wholesale-{wholesale_id}/edit', 'WholesaleController@getEdit')->where('wholesale_id', '[0-9]+')->middleware('payzone');
	Route::get('wholesale-{wholesale_id}/show')->where('wholesale_id', '[0-9]+')->middleware('payzone');
	Route::post('wholesale/iban/update', 'WholesaleController@doUpdateIban');
	Route::get('wholesale-{wholesale_id}/delete', 'WholesaleController@getDelete')->where('wholesale_id', '[0-9]+')->middleware('payzone');

	/* Project pages */
	Route::get('project/new', 'ProjectController@getNew')->middleware('payzone'); 
	Route::get('project/relation/{relation_id}', 'ProjectController@getRelationDetails')->middleware('payzone'); 
	Route::post('project/new', 'ProjectController@doNew');
	Route::post('project/update', 'ProjectController@doUpdate');
	Route::post('project/update/note', 'ProjectController@doUpdateNote');
	Route::post('project/update/communication', 'ProjectController@doCommunication');
	Route::post('project/updatecalc', 'ProjectController@doUpdateProfit');
	Route::post('project/updateadvanced', 'ProjectController@doUpdateAdvanced');
	Route::get('project', 'ProjectController@getAll')->middleware('payzone');
	Route::get('project-{project_id}/edit', 'ProjectController@getEdit')->where('project_id', '[0-9]+')->middleware('payzone');
	Route::post('project/updateworkexecution', 'ProjectController@doUpdateWorkExecution');
	Route::post('project/updateworkcompletion', 'ProjectController@doUpdateWorkCompletion');
	Route::post('project/updateprojectclose', 'ProjectController@doUpdateProjectClose');

	/* Cost pages */
	Route::get('timesheet', 'CostController@getTimesheet')->middleware('payzone');
	Route::post('timesheet/new', 'CostController@doNewTimesheet');
	Route::post('timesheet/delete', 'CostController@doDeleteTimesheet');
	Route::get('timesheet/activity/{project_id}/{type}', 'CostController@getActivityByType')->where('project_id', '[0-9]+')->where('type', '[0-9]+')->middleware('payzone');
	Route::get('purchase', 'CostController@getPurchase')->middleware('payzone');
	Route::post('purchase/new', 'CostController@doNewPurchase');
	Route::post('purchase/delete', 'CostController@doDeletePurchase');

	/* Material database */
	Route::get('material', 'MaterialController@getList')->middleware('payzone');
	Route::post('material/search', 'MaterialController@doSearch');
	Route::post('material/newmaterial', 'MaterialController@doNew');
	Route::post('material/updatematerial', 'MaterialController@doUpdate');
	Route::post('material/deletematerial', 'MaterialController@doDelete');
	Route::post('material/favorite', 'MaterialController@doFavorite');
	Route::post('material/element/new', 'MaterialController@doNewElement');
	Route::post('material/upload', 'MaterialController@doUploadCSV');
});

Route::group(['before' => 'admin', 'prefix' => 'admin','middleware' => 'admin'], function()
{
	/* Admin */
	Route::get('/', function() {
		return view('admin.dashboard');
	});
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
	Route::get('user-{user_id}/demo', 'AdminController@getDemoProject');
	Route::get('user-{user_id}/validation', 'AdminController@getValidationProject');
	Route::get('user-{user_id}/stabu', 'AdminController@getStabuProject');
	Route::get('user-{user_id}/deblock', 'AdminController@getSessionDeblock');
	Route::post('user-{user_id}/edit', 'AdminController@doUpdateUser');
	Route::post('user-{user_id}/adminlog/new', 'AdminController@doNewAdminLog');
	Route::get('group', function() {
		return view('admin.group');
	});
	Route::get('group-{group_id}/edit', function() {
		return view('admin.edit_group');
	});
	Route::post('group-{group_id}/edit', 'AdminController@doUpdateGroup');
	Route::get('group/new', function() {
		return view('admin.new_group');
	});
	Route::post('group/new', 'AdminController@doNewGroup');
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
	Route::post('application/{client_id}/edit', 'AdminController@doUpdateApplication');
	Route::post('application/new', 'AdminController@doNewApplication');
	Route::post('snailmail/offer/done', 'AdminController@doOfferPostDone');
	Route::post('snailmail/invoice/done', 'AdminController@doInvoicePostDone');
	Route::get('resource', function() {
		return view('admin.resource');
	});
	Route::post('resource/delete', 'AdminController@doDeleteResource');
	Route::get('documentation/{dir?}/{page?}', 'AdminController@getDocumentation');
	Route::get('log', function() {
		return view('admin.log');
	});
	Route::get('log/truncate', 'AdminController@doTruncateLog');
});

// Route::any('telegram', function(){
// 	if (env('TELEGRAM_ENABLED')) {
// 		try {
// 			// create Telegram API object
// 			$telegram = new Longman\TelegramBot\Telegram(env('TELEGRAM_API'), env('TELEGRAM_NAME'));

// 			$telegram->handle();
// 		} catch (Longman\TelegramBot\Exception\TelegramException $e) {
// 			Log::error($e->getMessage());
// 		}
// 	}
// });
