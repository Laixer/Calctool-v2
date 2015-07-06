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
Route::get('register', array('before' => 'guest', 'as' => 'register', 'uses' => 'AuthController@getRegister'));
Route::post('register', array('before' => 'guest', 'as' => 'register', 'uses' => 'AuthController@doRegister'));
Route::get('confirm/{api}/{token}', array('before' => 'guest', 'as' => 'register', 'uses' => 'AuthController@doActivate'))->where('api', '[0-9a-z]{32}')->where('token', '[0-9a-z]{40}');
Route::post('password/reset', array('before' => 'guest', 'as' => 'reset', 'uses' => 'AuthController@doBlockPassword'));
Route::get('password/{api}/{token}', array('before' => 'guest', 'as' => 'register', 'uses' => 'AuthController@getNewPassword'))->where('api', '[0-9a-z]{32}')->where('token', '[0-9a-z]{40}');
Route::post('password/{api}/{token}', array('before' => 'guest', 'as' => 'register', 'uses' => 'AuthController@doNewPassword'))->where('api', '[0-9a-z]{32}')->where('token', '[0-9a-z]{40}');

Route::any('about', function()
{
	return View::make('generic.about');
});

Route::any('support', function()
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

Route::any('countdown', function()
{
	return View::make('generic.countdown');
});

Route::group(array('before' => 'auth'), function()
{
	/* Generic pages */
	Route::get('/', array('uses' => 'HomeController@getHome'));
	Route::get('logout', array('as' => 'logout', 'uses' => 'AuthController@doLogout'));
	Route::get('result/project-{project_id}', array('as' => 'result', 'uses' => 'ResultController@getResult'))->where('project_id', '[0-9]+');
	Route::get('myaccount', array('as' => 'account', 'uses' => 'UserController@getMyAccount'));
	Route::post('myaccount/updateuser', array('as' => 'account', 'uses' => 'UserController@doMyAccountUser'));

	/* Actions by calculation */
	Route::post('calculation/newchapter/{project_id}', array('as' => 'calculation', 'uses' => 'CalcController@doNewChapter'))->where('project_id', '[0-9]+');
	Route::post('calculation/calc/newactivity/{chapter_id}', array('as' => 'calculation', 'uses' => 'CalcController@doNewCalculationActivity'))->where('chapter_id', '[0-9]+');
	Route::post('calculation/estim/newactivity/{chapter_id}', array('as' => 'calculation', 'uses' => 'CalcController@doNewEstimateActivity'))->where('chapter_id', '[0-9]+');
	Route::post('calculation/updatepart', array('as' => 'calculation', 'uses' => 'CalcController@doUpdatePart'));
	Route::post('calculation/updatetax', array('as' => 'calculation', 'uses' => 'CalcController@doUpdateTax'));
	Route::post('calculation/deleteactivity', array('as' => 'calculation', 'uses' => 'CalcController@doDeleteActivity'));
	Route::post('calculation/deletechapter', array('as' => 'calculation', 'uses' => 'CalcController@doDeleteChapter'));

	Route::post('invoice/updatecondition', array('as' => 'invoice', 'uses' => 'InvoiceController@doUpdateCondition'));
	Route::post('invoice/updatecode', array('as' => 'invoice', 'uses' => 'InvoiceController@doUpdateCode'));
	Route::post('invoice/updatedesc', array('as' => 'invoice', 'uses' => 'InvoiceController@doUpdateDescription'));
	Route::post('invoice/updateamount', array('as' => 'invoice', 'uses' => 'InvoiceController@doUpdateAmount'));
	Route::get('invoice/project-{project_id}', array('as' => 'invoice', 'uses' => 'CalcController@getInvoiceAll'))->where('project_id', '[0-9]+');
	Route::get('invoice/project-{project_id}/invoice-{invoice_id}', array('as' => 'invoice', 'uses' => 'CalcController@getInvoice'))->where('project_id', '[0-9]+')->where('invoice_id', '[0-9]+');
	Route::get('invoice/project-{project_id}/term-invoice-{invoice_id}', array('as' => 'invoice', 'uses' => 'CalcController@getTermInvoice'))->where('project_id', '[0-9]+')->where('invoice_id', '[0-9]+');
	Route::get('invoice/project-{project_id}/invoice-{invoice_id}/pdf', array('as' => 'invoicepdf', 'uses' => 'CalcController@getInvoicepdf'))->where('project_id', '[0-9]+')->where('invoice_id', '[0-9]+');
	Route::post('invoice/close', array('as' => 'invoice', 'uses' => 'InvoiceController@doInvoiceClose'));
	Route::post('invoice/pay', array('as' => 'invoice', 'uses' => 'InvoiceController@doInvoicePay'));
	Route::post('invoice/term/add', array('as' => 'invoice', 'uses' => 'InvoiceController@doInvoiceNewTerm'));
	Route::post('invoice/term/delete', array('as' => 'invoice', 'uses' => 'InvoiceController@doInvoiceDeleteTerm'));

	Route::get('offer/project-{project_id}', array('as' => 'invoice', 'uses' => 'CalcController@getOffer'))->where('project_id', '[0-9]+');
	Route::post('offer/project-{project_id}', array('as' => 'invoice', 'uses' => 'OfferController@doNewOffer'));
	Route::post('offer/close', array('as' => 'invoice', 'uses' => 'OfferController@doOfferClose'));

	Route::get('offer/pdf/project-{project_id}', array('as' => 'invoice', 'uses' => 'CalcController@getOfferPDF'))->where('project_id', '[0-9]+');

	/* Calculation acions by calculation */
	Route::post('calculation/calc/newmaterial', array('as' => 'calculation', 'uses' => 'CalcController@doNewCalculationMaterial'));
	Route::post('calculation/calc/newequipment', array('as' => 'calculation', 'uses' => 'CalcController@doNewCalculationEquipment'));
	Route::post('calculation/calc/newlabor', array('as' => 'calculation', 'uses' => 'CalcController@doNewCalculationLabor'));
	Route::post('calculation/calc/deletematerial', array('as' => 'calculation', 'uses' => 'CalcController@doDeleteCalculationMaterial'));
	Route::post('calculation/calc/deleteequipment', array('as' => 'calculation', 'uses' => 'CalcController@doDeleteCalculationEquipment'));
	Route::post('calculation/calc/updatematerial', array('as' => 'calculation', 'uses' => 'CalcController@doUpdateCalculationMaterial'));
	Route::post('calculation/calc/updateequipment', array('as' => 'calculation', 'uses' => 'CalcController@doUpdateCalculationEquipment'));
	Route::post('calculation/calc/updatelabor', array('as' => 'calculation', 'uses' => 'CalcController@doUpdateCalculationLabor'));

	/* Estimate acions by calculation */
	Route::post('calculation/estim/newmaterial', array('as' => 'calculation', 'uses' => 'CalcController@doNewEstimateMaterial'));
	Route::post('calculation/estim/newequipment', array('as' => 'calculation', 'uses' => 'CalcController@doNewEstimateEquipment'));
	Route::post('calculation/estim/newlabor', array('as' => 'calculation', 'uses' => 'CalcController@doNewEstimateLabor'));
	Route::post('calculation/estim/deletematerial', array('as' => 'calculation', 'uses' => 'CalcController@doDeleteEstimateMaterial'));
	Route::post('calculation/estim/deleteequipment', array('as' => 'calculation', 'uses' => 'CalcController@doDeleteEstimateEquipment'));
	Route::post('calculation/estim/updatematerial', array('as' => 'calculation', 'uses' => 'CalcController@doUpdateEstimateMaterial'));
	Route::post('calculation/estim/updateequipment', array('as' => 'calculation', 'uses' => 'CalcController@doUpdateEstimateEquipment'));
	Route::post('calculation/estim/updatelabor', array('as' => 'calculation', 'uses' => 'CalcController@doUpdateEstimateLabor'));

	/* Calculation pages */
	Route::get('calculation/project-{project_id}', array('as' => 'calculation', 'uses' => 'CalcController@getCalculation'))->where('project_id', '[0-9]+');
	Route::get('estimate/project-{project_id}', array('as' => 'estimate', 'uses' => 'CalcController@getEstimate'))->where('project_id', '[0-9]+');

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
	Route::get('less/project-{project_id}', array('as' => 'less', 'uses' => 'CalcController@getLess'))->where('project_id', '[0-9]+');
	Route::post('less/updatelabor', array('as' => 'less', 'uses' => 'LessController@doUpdateLabor'));
	Route::post('less/updateequipment', array('as' => 'less', 'uses' => 'LessController@doUpdateEquipment'));
	Route::post('less/updatematerial', array('as' => 'less', 'uses' => 'LessController@doUpdateMaterial'));
	Route::post('less/resetlabor', array('as' => 'less', 'uses' => 'LessController@doResetLabor'));
	Route::post('less/resetmaterial', array('as' => 'less', 'uses' => 'LessController@doResetMaterial'));
	Route::post('less/resetequipment', array('as' => 'less', 'uses' => 'LessController@doResetEquipment'));

	/* More pages */
	Route::get('more/project-{project_id}', array('as' => 'more', 'uses' => 'CalcController@getMore'));
	Route::post('more/newmaterial', array('as' => 'more', 'uses' => 'MoreController@doNewMaterial'));
	Route::post('more/newequipment', array('as' => 'more', 'uses' => 'MoreController@doNewEquipment'));
	Route::post('more/newlabor', array('as' => 'more', 'uses' => 'MoreController@doNewLabor'));
	Route::post('more/updatematerial', array('as' => 'more', 'uses' => 'MoreController@doUpdateMaterial'));
	Route::post('more/updateequipment', array('as' => 'more', 'uses' => 'MoreController@doUpdateEquipment'));
	Route::post('more/updatelabor', array('as' => 'more', 'uses' => 'MoreController@doUpdateLabor'));
	Route::post('more/deletematerial', array('as' => 'more', 'uses' => 'MoreController@doDeleteMaterial'));
	Route::post('more/deleteequipment', array('as' => 'more', 'uses' => 'MoreController@doDeleteEquipment'));
	Route::post('more/deletelabor', array('as' => 'more', 'uses' => 'MoreController@doDeleteLabor'));
	Route::post('more/newactivity/{chapter_id}', array('as' => 'more', 'uses' => 'MoreController@doNewMoreActivity'))->where('chapter_id', '[0-9]+');

	/* Relation pages */
	Route::get('relation/new', array('as' => 'relation.new', 'uses' => 'RelationController@getNew'));
	Route::post('relation/new', array('as' => 'relation.new', 'uses' => 'RelationController@doNew'));
	Route::post('relation/update', array('as' => 'relation.update', 'uses' => 'RelationController@doUpdate'));
	Route::post('relation/contact/new', array('as' => 'relation.new', 'uses' => 'RelationController@doNewContact'));
	Route::post('relation/contact/update', array('as' => 'contact.update', 'uses' => 'RelationController@doUpdateContact'));
	Route::post('relation/iban/update', array('as' => 'iban.update', 'uses' => 'RelationController@doUpdateIban'));
	Route::post('relation/iban/new', array('as' => 'iban.update', 'uses' => 'RelationController@doNewIban'));
	Route::get('relation', array('as' => 'relation', 'uses' => 'RelationController@getAll'));
	Route::get('relation-{relation_id}/edit', array('as' => 'relation.edit', 'uses' => 'RelationController@getEdit'))->where('relation_id', '[0-9]+');
	Route::get('relation-{relation_id}/contact/new', array('as' => 'relation.contact.new', 'uses' => 'RelationController@getNewContact'))->where('relation_id', '[0-9]+');
	Route::get('relation-{relation_id}/contact-{contact_id}/edit', array('as' => 'contact.edit', 'uses' => 'RelationController@getEditContact'))->where('relation_id', '[0-9]+')->where('contact_id', '[0-9]+');
	Route::get('mycompany', array('as' => 'mycompany', 'uses' => 'RelationController@getMyCompany'));
	Route::post('relation/updatemycompany', array('as' => 'relation.update', 'uses' => 'RelationController@doUpdateMyCompany'));
	Route::post('relation/newmycompany', array('as' => 'relation.new', 'uses' => 'RelationController@doNewMyCompany'));

	/* Project pages */
	Route::get('project/new', array('as' => 'project.new', 'uses' => 'ProjectController@getNew'));
	Route::post('project/new', array('as' => 'project.new', 'uses' => 'ProjectController@doNew'));
	Route::post('project/update', array('as' => 'project.update', 'uses' => 'ProjectController@doUpdate'));
	Route::get('project', array('as' => 'project', 'uses' => 'ProjectController@getAll'));
	Route::get('project-{project_id}/edit', array('as' => 'project.edit', 'uses' => 'ProjectController@getEdit'))->where('project_id', '[0-9]+');
	Route::post('project/updateworkexecution', array('as' => 'project.edit', 'uses' => 'ProjectController@doUpdateWorkExecution'));

	/* Cost pages */
	Route::get('timesheet', array('as' => 'timesheet', 'uses' => 'CostController@getTimesheet'));
	Route::post('timesheet/new', array('as' => 'timesheet', 'uses' => 'CostController@doNewTimesheet'));
	Route::post('timesheet/delete', array('as' => 'timesheet', 'uses' => 'CostController@doDeleteTimesheet'));
	Route::get('purchase', array('as' => 'purchase', 'uses' => 'CostController@getPurchase'));
	Route::post('purchase/new', array('as' => 'purchase', 'uses' => 'CostController@doNewPurchase'));

	/* Material database */
	Route::get('material', array('as' => 'material', 'uses' => 'MaterialController@getList'));
	Route::post('material/search', array('as' => 'material', 'uses' => 'MaterialController@doSearch'));
});

Route::group(array('before' => 'admin'), function()
{
	/* Admin */
	Route::get('admin', array('as' => 'admin', 'uses' => 'AdminController@getDashboard'));
	Route::get('admin/user/new', array('as' => 'user', 'uses' => 'UserController@getNew'));
	Route::post('admin/user/new', array('as' => 'user', 'uses' => 'UserController@doNew'));
	Route::get('admin/user', array('as' => 'user', 'uses' => 'UserController@getAll'));
});
