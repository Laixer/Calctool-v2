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

Route::get('login', function(){
	return view('auth.login');
});

Route::group(['middleware' => 'guest'], function() {
	Route::get('register', function(){
		return view('auth.registration');
	});
	Route::post('login', 'AuthController@doLogin');
	Route::post('register', 'AuthController@doRegister');
	Route::get('confirm/{api}/{token}', 'AuthController@doActivate')->where('api', '[0-9a-z]{32}')->where('token', '[0-9a-z]{40}');
	Route::post('password/reset', 'AuthController@doBlockPassword');
	Route::get('password/{api}/{token}', function() {
		return view('auth.password');
	})->where('api', '[0-9a-z]{32}')->where('token', '[0-9a-z]{40}');
	Route::post('password/{api}/{token}', 'AuthController@doNewPassword')->where('api', '[0-9a-z]{32}')->where('token', '[0-9a-z]{40}');

	Route::get('ex-project-overview/{token}', function() {
		return view('user.client_page');
	})->where('token', '[0-9a-z]{40}');
	Route::post('ex-project-overview/{token}/update', 'ClientController@doUpdateCommunication')->where('token', '[0-9a-z]{40}');
	Route::get('ex-project-overview/{token}/done', 'ClientController@doOfferAccept')->where('token', '[0-9a-z]{40}');
});

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

Route::get('about', function() {
	return view('generic.about');
});
Route::get('faq', function() {
	return view('generic.faq');
});
Route::get('terms-and-conditions', function() {
	return view('generic.terms');
});
Route::get('privacy-policy', function() {
	return view('generic.privacy');
});

Route::post('feedback', 'FeedbackController@send');

Route::post('payment/webhook/', 'UserController@doPaymentUpdate');
Route::get('hidenextstep', 'AuthController@doHideNextStep');

Route::get('c4586v34674v4&vwasrt/footer_pdf', function() {
	return view('calc.footer_pdf');
});

Route::group(['middleware' => 'auth'], function()
{
	/* Generic pages */
	Route::get('/', ['middleware' => 'payzone', function() {
		return view('base.home');
	}]);
	Route::get('admin/switch/back', 'AdminController@getSwitchSessionBack');
	Route::get('logout', function() {
		Auth::logout();
		return redirect('login');
	});
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

	/* Actions by calculation */
	Route::post('calculation/newchapter/{project_id}', array('as' => 'calculation', 'uses' => 'CalcController@doNewChapter'))->where('project_id', '[0-9]+');
	Route::post('calculation/calc/newactivity/{chapter_id}', array('as' => 'calculation', 'uses' => 'CalcController@doNewCalculationActivity'))->where('chapter_id', '[0-9]+');
	Route::post('calculation/estim/newactivity/{chapter_id}', array('as' => 'calculation', 'uses' => 'CalcController@doNewEstimateActivity'))->where('chapter_id', '[0-9]+');
	Route::post('calculation/updatepart', array('as' => 'calculation', 'uses' => 'CalcController@doUpdatePart'));
	Route::post('calculation/updatetax', array('as' => 'calculation', 'uses' => 'CalcController@doUpdateTax'));
	Route::post('calculation/updateestimatetax', array('as' => 'calculation', 'uses' => 'CalcController@doUpdateEstimateTax'));
	Route::post('calculation/noteactivity', array('as' => 'calculation', 'uses' => 'CalcController@doUpdateNote'));
	Route::post('calculation/deleteactivity', array('as' => 'calculation', 'uses' => 'CalcController@doDeleteActivity'));
	Route::post('calculation/deletechapter', array('as' => 'calculation', 'uses' => 'CalcController@doDeleteChapter'));
	Route::post('calculation/activity/usetimesheet', 'CalcController@doUpdateUseTimesheet');

	Route::post('invoice/updatecondition', array('as' => 'invoice', 'uses' => 'InvoiceController@doUpdateCondition'));
	Route::post('invoice/updatecode', array('as' => 'invoice', 'uses' => 'InvoiceController@doUpdateCode'));
	Route::post('invoice/updatedesc', array('as' => 'invoice', 'uses' => 'InvoiceController@doUpdateDescription'));
	Route::post('invoice/updateamount', array('as' => 'invoice', 'uses' => 'InvoiceController@doUpdateAmount'));
	Route::get('invoice/project-{project_id}', array('as' => 'invoice', 'uses' => 'CalcController@getInvoiceAll'))->where('project_id', '[0-9]+')->middleware('payzone');
	Route::get('invoice/project-{project_id}/invoice-{invoice_id}', array('as' => 'invoice', 'uses' => 'CalcController@getInvoice'))->where('project_id', '[0-9]+')->where('invoice_id', '[0-9]+');
	Route::get('invoice/project-{project_id}/term-invoice-{invoice_id}', array('as' => 'invoice', 'uses' => 'CalcController@getTermInvoice'))->where('project_id', '[0-9]+')->where('invoice_id', '[0-9]+');
	Route::post('invoice/save', 'InvoiceController@doInvoiceVersionNew');
	Route::post('invoice/close', 'InvoiceController@doInvoiceClose');
	Route::post('invoice/pay', array('as' => 'invoice', 'uses' => 'InvoiceController@doInvoicePay'));
	Route::post('invoice/invclose', array('as' => 'invoice', 'uses' => 'InvoiceController@doInvoiceCloseAjax'));
	Route::post('invoice/term/add', array('as' => 'invoice', 'uses' => 'InvoiceController@doInvoiceNewTerm'));
	Route::post('invoice/term/delete', array('as' => 'invoice', 'uses' => 'InvoiceController@doInvoiceDeleteTerm'));
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

	Route::get('offerversions/project-{project_id}', array('as' => 'invoice', 'uses' => 'CalcController@getOfferAll'))->where('project_id', '[0-9]+')->middleware('payzone');
	Route::get('offer/project-{project_id}', array('as' => 'invoice', 'uses' => 'CalcController@getOffer'))->where('project_id', '[0-9]+')->middleware('payzone');
	Route::post('offer/project-{project_id}', array('as' => 'invoice', 'uses' => 'OfferController@doNewOffer'));
	Route::get('offer/project-{project_id}/offer-{offer_id}', function() {
		return View::make('calc.offer_show_pdf');
	})->where('project_id', '[0-9]+')->where('offer_id', '[0-9]+')->middleware('payzone');
	Route::get('offer/project-{project_id}/offer-{offer_id}/mail-preview', 'OfferController@getSendOfferPreview')->where('project_id', '[0-9]+')->where('offer_id', '[0-9]+')->middleware('payzone');
	Route::post('offer/close', 'OfferController@doOfferClose');
	Route::post('offer/sendmail', 'OfferController@doSendOffer');
	Route::post('offer/sendpost', 'OfferController@doSendPostOffer');

	Route::get('invoice/pdf/project-{project_id}/invoice-{invoice_id}', array('as' => 'invoice', 'uses' => 'CalcController@getInvoicePDF'))->where('project_id', '[0-9]+')->middleware('payzone');
	Route::get('invoice/pdf/project-{project_id}/invoice-{invoice_id}/download', array('as' => 'invoice', 'uses' => 'CalcController@getInvoiceDownloadPDF'))->where('project_id', '[0-9]+')->middleware('payzone');
	Route::get('invoice/pdf/project-{project_id}/term-invoice-{invoice_id}/download', array('as' => 'invoice', 'uses' => 'CalcController@getTermInvoiceDownloadPDF'))->where('project_id', '[0-9]+')->middleware('payzone');

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
	Route::post('estimate/newlabor', array('as' => 'calculation', 'uses' => 'EstimController@doNewEstimateLabor'));
	Route::post('estimate/newmaterial', array('as' => 'calculation', 'uses' => 'EstimController@doNewEstimateMaterial'));
	Route::post('estimate/updatematerial', array('as' => 'calculation', 'uses' => 'EstimController@doUpdateEstimateMaterial'));
	Route::post('estimate/deletematerial', array('as' => 'calculation', 'uses' => 'EstimController@doDeleteEstimateMaterial'));
	Route::post('estimate/resetmaterial', array('as' => 'calculation', 'uses' => 'EstimController@doResetEstimateMaterial'));
	Route::post('estimate/newequipment', array('as' => 'calculation', 'uses' => 'EstimController@doNewEstimateEquipment'));
	Route::post('estimate/updateequipment', array('as' => 'calculation', 'uses' => 'EstimController@doUpdateEstimateEquipment'));
	Route::post('estimate/deleteequipment', array('as' => 'calculation', 'uses' => 'EstimController@doDeleteEstimateEquipment'));
	Route::post('estimate/resetequipment', array('as' => 'calculation', 'uses' => 'EstimController@doResetEstimateEquipment'));
	Route::post('estimate/updatelabor', array('as' => 'calculation', 'uses' => 'EstimController@doUpdateEstimateLabor'));
	Route::post('estimate/resetlabor', array('as' => 'calculation', 'uses' => 'EstimController@doResetEstimateLabor'));
	Route::post('estimate/deletelabor', array('as' => 'calculation', 'uses' => 'EstimController@doDeleteEstimateLabor'));

	/* Less pages */
	Route::get('less/project-{project_id}', array('as' => 'less', 'uses' => 'CalcController@getLess'))->where('project_id', '[0-9]+')->middleware('payzone');
	Route::get('less/summary/project-{project_id}', 'CalcController@getLessSummary')->where('project_id', '[0-9]+')->middleware('payzone');
	Route::get('less/endresult/project-{project_id}', 'CalcController@getLessEndresult')->where('project_id', '[0-9]+')->middleware('payzone');
	Route::post('less/updatelabor', array('as' => 'less', 'uses' => 'LessController@doUpdateLabor'));
	Route::post('less/updateequipment', array('as' => 'less', 'uses' => 'LessController@doUpdateEquipment'));
	Route::post('less/updatematerial', array('as' => 'less', 'uses' => 'LessController@doUpdateMaterial'));
	Route::post('less/resetlabor', array('as' => 'less', 'uses' => 'LessController@doResetLabor'));
	Route::post('less/resetmaterial', array('as' => 'less', 'uses' => 'LessController@doResetMaterial'));
	Route::post('less/resetequipment', array('as' => 'less', 'uses' => 'LessController@doResetEquipment'));

	/* More pages */
	Route::get('more/project-{project_id}', array('as' => 'more', 'uses' => 'CalcController@getMore'))->middleware('payzone');
	Route::get('more/summary/project-{project_id}', 'CalcController@getMoreSummary')->where('project_id', '[0-9]+')->middleware('payzone');
	Route::get('more/endresult/project-{project_id}', 'CalcController@getMoreEndresult')->where('project_id', '[0-9]+')->middleware('payzone');
	Route::post('more/newmaterial', array('as' => 'more', 'uses' => 'MoreController@doNewMaterial'));
	Route::post('more/newequipment', array('as' => 'more', 'uses' => 'MoreController@doNewEquipment'));
	Route::post('more/newlabor', array('as' => 'more', 'uses' => 'MoreController@doNewLabor'));
	Route::post('more/updatematerial', array('as' => 'more', 'uses' => 'MoreController@doUpdateMaterial'));
	Route::post('more/updateequipment', array('as' => 'more', 'uses' => 'MoreController@doUpdateEquipment'));
	Route::post('more/updatelabor', array('as' => 'more', 'uses' => 'MoreController@doUpdateLabor'));
	Route::post('more/deletematerial', array('as' => 'more', 'uses' => 'MoreController@doDeleteMaterial'));
	Route::post('more/deleteequipment', array('as' => 'more', 'uses' => 'MoreController@doDeleteEquipment'));
	Route::post('more/deletelabor', array('as' => 'more', 'uses' => 'MoreController@doDeleteLabor'));
	Route::post('more/newactivity/{chapter_id}', array('as' => 'more', 'uses' => 'MoreController@doNewActivity'))->where('chapter_id', '[0-9]+');
	Route::post('more/newchapter/{project_id}', array('uses' => 'MoreController@doNewChapter'))->where('project_id', '[0-9]+');
	Route::post('more/deletechapter', array('uses' => 'MoreController@doDeleteChapter'));

	/* Relation pages */
	Route::get('relation/new', array('as' => 'relation.new', 'uses' => 'RelationController@getNew'))->middleware('payzone');
	Route::post('relation/new', array('as' => 'relation.new', 'uses' => 'RelationController@doNew'));
	Route::post('relation/update', array('as' => 'relation.update', 'uses' => 'RelationController@doUpdate'));
	Route::post('relation/contact/new', array('as' => 'relation.new', 'uses' => 'RelationController@doNewContact'));
	Route::post('relation/contact/update', array('as' => 'contact.update', 'uses' => 'RelationController@doUpdateContact'));
	Route::post('relation/contact/delete', array('as' => 'contact.update', 'uses' => 'RelationController@doDeleteContact'));
	Route::post('relation/iban/update', array('as' => 'iban.update', 'uses' => 'RelationController@doUpdateIban'));
	Route::post('relation/iban/new', array('as' => 'iban.update', 'uses' => 'RelationController@doNewIban'));
	Route::get('relation', array('as' => 'relation', 'uses' => 'RelationController@getAll'))->middleware('payzone');
	Route::get('relation-{relation_id}/edit', array('as' => 'relation.edit', 'uses' => 'RelationController@getEdit'))->where('relation_id', '[0-9]+')->middleware('payzone');
	Route::get('relation-{relation_id}/delete', array('uses' => 'RelationController@getDelete'))->where('relation_id', '[0-9]+')->middleware('payzone');
	Route::get('relation-{relation_id}/contact/new', array('as' => 'relation.contact.new', 'uses' => 'RelationController@getNewContact'))->where('relation_id', '[0-9]+')->middleware('payzone');
	Route::get('relation-{relation_id}/contact-{contact_id}/edit', array('as' => 'contact.edit', 'uses' => 'RelationController@getEditContact'))->where('relation_id', '[0-9]+')->where('contact_id', '[0-9]+')->middleware('payzone');
	Route::get('mycompany', array('as' => 'mycompany', 'uses' => 'RelationController@getMyCompany'))->middleware('payzone');
	Route::post('mycompany/iban/update', array('as' => 'iban.update', 'uses' => 'UserController@doUpdateIban'));
	Route::post('mycompany/contact/new', array('as' => 'relation.new', 'uses' => 'RelationController@doMyCompanyNewContact'));
	Route::get('mycompany/contact/new', function() {
		return view('user.mycompany_contact');
	});
	Route::post('mycompany/quickstart', 'QuickstartController@doNewMyCompanyQuickstart');
	Route::post('mycompany/cashbook/account/new', 'CashbookController@doNewAccount');
	Route::post('mycompany/cashbook/new', 'CashbookController@doNewCashRow');
	Route::post('mycompany/quickstart/address', 'QuickstartController@getExternalAddress');

	Route::get('relation-{relation_id}/contact-{contact_id}/vcard', array('uses' => 'RelationController@downloadVCard'))->where('relation_id', '[0-9]+')->where('contact_id', '[0-9]+');
	Route::post('relation/updatemycompany', array('as' => 'relation.update', 'uses' => 'RelationController@doUpdateMyCompany'));
	Route::post('relation/newmycompany', array('as' => 'relation.new', 'uses' => 'RelationController@doNewMyCompany'));
	Route::post('relation/logo/save', array('as' => 'relation.logo', 'uses' => 'RelationController@doNewLogo'));

	/* Wholesale */
	Route::get('wholesale', function() {
		return view('user.wholesale');
	})->middleware('payzone');
	Route::get('wholesale/new', function() {
		return view('user.new_wholesale');
	})->middleware('payzone');
	Route::post('wholesale/new', array('uses' => 'WholesaleController@doNew'));
	Route::post('wholesale/update', array('uses' => 'WholesaleController@doUpdate'));
	Route::get('wholesale-{wholesale_id}/edit', function() {
		return view('user.edit_wholesale');
	})->where('wholesale_id', '[0-9]+')->middleware('payzone');
	Route::get('wholesale-{wholesale_id}/show', function() {
		return view('user.show_wholesale');
	})->where('wholesale_id', '[0-9]+')->middleware('payzone');
	Route::post('wholesale/iban/update', array('uses' => 'WholesaleController@doUpdateIban'));
	Route::get('wholesale-{wholesale_id}/delete', array('uses' => 'WholesaleController@getDelete'))->where('wholesale_id', '[0-9]+')->middleware('payzone');

	/* Project pages */
	Route::get('project/new', 'ProjectController@getNew')->middleware('payzone'); 
	Route::post('project/new', array('as' => 'project.new', 'uses' => 'ProjectController@doNew'));
	Route::post('project/update', array('as' => 'project.update', 'uses' => 'ProjectController@doUpdate'));
	Route::post('project/update/note', array('as' => 'project.update', 'uses' => 'ProjectController@doUpdateNote'));
	Route::post('project/update/communication', array('as' => 'project.update', 'uses' => 'ProjectController@doCommunication'));
	Route::post('project/updatecalc', array('as' => 'project.update', 'uses' => 'ProjectController@doUpdateProfit'));
	Route::post('project/updateadvanced', array('as' => 'project.update', 'uses' => 'ProjectController@doUpdateAdvanced'));
	Route::get('project', array('as' => 'project', 'uses' => 'ProjectController@getAll'))->middleware('payzone');
	Route::get('project-{project_id}/edit', array('as' => 'project.edit', 'uses' => 'ProjectController@getEdit'))->where('project_id', '[0-9]+')->middleware('payzone');
	Route::post('project/updateworkexecution', array('as' => 'project.edit', 'uses' => 'ProjectController@doUpdateWorkExecution'));
	Route::post('project/updateworkcompletion', array('as' => 'project.edit', 'uses' => 'ProjectController@doUpdateWorkCompletion'));
	Route::post('project/updateprojectclose', array('as' => 'project.edit', 'uses' => 'ProjectController@doUpdateProjectClose'));

	/* Cost pages */
	Route::get('timesheet', array('as' => 'timesheet', 'uses' => 'CostController@getTimesheet'))->middleware('payzone');
	Route::post('timesheet/new', array('as' => 'timesheet', 'uses' => 'CostController@doNewTimesheet'));
	Route::post('timesheet/delete', array('as' => 'timesheet', 'uses' => 'CostController@doDeleteTimesheet'));
	Route::get('timesheet/activity/{project_id}/{type}', array('uses' => 'CostController@getActivityByType'))->where('project_id', '[0-9]+')->where('type', '[0-9]+')->middleware('payzone');
	Route::get('purchase', array('as' => 'purchase', 'uses' => 'CostController@getPurchase'))->middleware('payzone');
	Route::post('purchase/new', array('as' => 'purchase', 'uses' => 'CostController@doNewPurchase'));
	Route::post('purchase/delete', array('as' => 'timesheet', 'uses' => 'CostController@doDeletePurchase'));

	/* Material database */
	Route::get('material', array('as' => 'material', 'uses' => 'MaterialController@getList'))->middleware('payzone');
	Route::post('material/search', array('as' => 'material', 'uses' => 'MaterialController@doSearch'));
	Route::post('material/newmaterial', array('uses' => 'MaterialController@doNew'));
	Route::post('material/updatematerial', array('uses' => 'MaterialController@doUpdate'));
	Route::post('material/deletematerial', array('uses' => 'MaterialController@doDelete'));
	Route::post('material/favorite', array('uses' => 'MaterialController@doFavorite'));
	Route::post('material/element/new', array('uses' => 'MaterialController@doNewElement'));
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
	Route::post('user/new', array('as' => 'user', 'uses' => 'AdminController@doNewUser'));
	Route::get('user', function() {
		return view('admin.user');
	});
	Route::get('user-{user_id}/edit', function() {
		return view('admin.edit_user');
	});
	Route::get('user-{user_id}/switch', array('as' => 'user', 'uses' => 'AdminController@getSwitchSession'));
	Route::get('user-{user_id}/demo', array('as' => 'user', 'uses' => 'AdminController@getDemoProject'));
	Route::get('user-{user_id}/validation', array('as' => 'user', 'uses' => 'AdminController@getValidationProject'));
	Route::get('user-{user_id}/stabu', array('as' => 'user', 'uses' => 'AdminController@getStabuProject'));
	Route::get('user-{user_id}/deblock', array('as' => 'user', 'uses' => 'AdminController@getSessionDeblock'));
	Route::post('user-{user_id}/edit', array('as' => 'user', 'uses' => 'AdminController@doUpdateUser'));
	Route::get('alert', function() {
		return view('admin.alert');
	});
	Route::post('alert/new', array('as' => 'user', 'uses' => 'AdminController@doNewAlert'));
	Route::post('alert/delete', array('as' => 'user', 'uses' => 'AdminController@doDeleteAlert'));
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
	Route::post('snailmail/offer/done', 'AdminController@doOfferPostDone');
	Route::post('snailmail/invoice/done', 'AdminController@doInvoicePostDone');
	Route::get('resource', function() {
		return view('admin.resource');
	});
	Route::post('resource/delete', array('as' => 'user', 'uses' => 'AdminController@doDeleteResource'));
	Route::get('log', function() {
		return view('admin.log');
	});
	Route::get('log/truncate', array('as' => 'user', 'uses' => 'AdminController@doTruncateLog'));
});

Route::any('telegram', function(){
	if (env('TELEGRAM_ENABLED')) {
		try {
			// create Telegram API object
			$telegram = new Longman\TelegramBot\Telegram(env('TELEGRAM_API'), env('TELEGRAM_NAME'));

			$telegram->handle();
		} catch (Longman\TelegramBot\Exception\TelegramException $e) {
			Log::error($e->getMessage());
		}
	}
});
