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

namespace BynqIO\CalculatieTool\Http\Controllers;

use Illuminate\Http\Request;

use \BynqIO\CalculatieTool\Models\Project;
use \BynqIO\CalculatieTool\Models\FavoriteActivity;
use \BynqIO\CalculatieTool\Models\FavoriteMaterial;
use \BynqIO\CalculatieTool\Models\FavoriteEquipment;
use \BynqIO\CalculatieTool\Models\FavoriteLabor;

class FavoriteController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /relation
	 *
	 * @return Response
	 */
	public function doDeleteActivity(Request $request)
	{
		$this->validate($request, [
			'activity' => array('required','integer','min:0')
		]);

		$activity = FavoriteActivity::find($request->input('activity'));
		if (!$activity || !$activity->isOwner())
			return response()->json(['success' => 0]);

		$activity->delete();

		return response()->json(['success' => 1]);
	}

	public function doUpdateNote(Request $request)
	{
		$this->validate($request, [
			'note' => array('required'),
			'activity' => array('required','integer')
		]);

		$activity = FavoriteActivity::find($request->input('activity'));
		if (!$activity || !$activity->isOwner())
			return response()->json(['success' => 0]);

		$activity->note = $request->get('note');

		$activity->save();

		return response()->json(['success' => 1]);
	}

	public function doRenameActivity(Request $request)
	{
		$this->validate($request, [
			'activity_name' => array('required','max:100'),
			'activity' => array('required','max:100'),
		]);

		$activity = FavoriteActivity::find($request->input('activity'));
		if (!$activity || !$activity->isOwner())
			return response()->json(['success' => 0]);

		$activity->activity_name = $request->get('activity_name');

		$activity->save();

		return back()->with('success', 'Werkzaamheid aangepast');
	}

	public function doNewMaterial(Request $request)
	{
		$this->validate($request, [
			'name' => array('required','max:100'),
			'unit' => array('required','max:10'),
			'rate' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0')
		]);

		$activity = FavoriteActivity::find($request->input('activity'));
		if (!$activity || !$activity->isOwner())
			return response()->json(['success' => 0]);

		$material = FavoriteMaterial::create(array(
			"material_name" => $request->get('name'),
			"unit" => $request->get('unit'),
			"rate" => str_replace(',', '.', str_replace('.', '' , $request->get('rate'))),
			"amount" => str_replace(',', '.', str_replace('.', '' , $request->get('amount'))),
			"activity_id" => $activity->id,
		));

		return response()->json(['success' => 1, 'id' => $material->id]);
	}

	public function doNewEquipment(Request $request)
	{
		$this->validate($request, [
			'name' => array('required','max:100'),
			'unit' => array('required','max:10'),
			'rate' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0')
		]);

		$activity = FavoriteActivity::find($request->input('activity'));
		if (!$activity || !$activity->isOwner())
			return response()->json(['success' => 0]);

		$equipment = FavoriteEquipment::create(array(
			"equipment_name" => $request->get('name'),
			"unit" => $request->get('unit'),
			"rate" => str_replace(',', '.', str_replace('.', '' , $request->get('rate'))),
			"amount" => str_replace(',', '.', str_replace('.', '' , $request->get('amount'))),
			"activity_id" => $activity->id,
		));

		return response()->json(['success' => 1, 'id' => $equipment->id]);
	}

	public function doNewLabor(Request $request)
	{
		$this->validate($request, [
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0')
		]);

		$activity = FavoriteActivity::find($request->input('activity'));
		if (!$activity || !$activity->isOwner())
			return response()->json(['success' => 0]);

		if ($request->get('rate'))
			$rate = str_replace(',', '.', str_replace('.', '' , $request->get('rate')));
		else
			$rate = 0;
		$labor = FavoriteLabor::create(array(
			"rate" => $rate,
			"amount" => str_replace(',', '.', str_replace('.', '' , $request->get('amount'))),
			"activity_id" => $activity->id,
		));

		return response()->json(['success' => 1, 'id' => $labor->id]);
	}

	public function doDeleteMaterial(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer','min:0'),
		]);

		$rec = FavoriteMaterial::find($request->input('id'));
		if (!$rec)
			return response()->json(['success' => 0]);
		$activity = FavoriteActivity::find($rec->activity_id);
		if (!$activity || !$activity->isOwner())
			return response()->json(['success' => 0]);

		$rec->delete();

		return response()->json(['success' => 1]);
	}

	public function doDeleteEquipment(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer','min:0'),
		]);

		$rec = FavoriteEquipment::find($request->input('id'));
		if (!$rec)
			return response()->json(['success' => 0]);
		$activity = FavoriteActivity::find($rec->activity_id);
		if (!$activity || !$activity->isOwner())
			return response()->json(['success' => 0]);

		$rec->delete();

		return response()->json(['success' => 1]);
	}

	public function doDeleteLabor(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer','min:0'),
		]);

		$rec = FavoriteLabor::find($request->input('id'));
		if (!$rec)
			return response()->json(['success' => 0]);
		$activity = FavoriteActivity::find($rec->activity_id);
		if (!$activity || !$activity->isOwner())
			return response()->json(['success' => 0]);

		$rec->delete();

		return response()->json(['success' => 1]);
	}

	public function doUpdateMaterial(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'name' => array('max:100'),
			'unit' => array('max:10'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/')
		]);

		$material = FavoriteMaterial::find($request->input('id'));
		if (!$material)
			return response()->json(['success' => 0]);
		$activity = FavoriteActivity::find($material->activity_id);
		if (!$activity || !$activity->isOwner())
			return response()->json(['success' => 0]);

		$material->material_name = $request->get('name');
		$material->unit = $request->get('unit');
		$material->rate = str_replace(',', '.', str_replace('.', '' , $request->get('rate')));
		$material->amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));

		$material->save();

		return response()->json(['success' => 1]);
	}

	public function doUpdateEquipment(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'name' => array('max:100'),
			'unit' => array('max:10'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/')
		]);

		$equipment = FavoriteEquipment::find($request->input('id'));
		if (!$equipment)
			return response()->json(['success' => 0]);
		$activity = FavoriteActivity::find($equipment->activity_id);
		if (!$activity || !$activity->isOwner())
			return response()->json(['success' => 0]);

		$equipment->equipment_name = $request->get('name');
		$equipment->unit = $request->get('unit');
		$equipment->rate = str_replace(',', '.', str_replace('.', '' , $request->get('rate')));
		$equipment->amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));

		$equipment->save();

		return response()->json(['success' => 1]);
	}

	public function doUpdateLabor(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/')
		]);

		$labor = FavoriteLabor::find($request->input('id'));
		if (!$labor)
			return response()->json(['success' => 0]);
		$activity = FavoriteActivity::find($labor->activity_id);
		if (!$activity || !$activity->isOwner())
			return response()->json(['success' => 0]);

		$rate = $request->get('rate');
		if (empty($rate)) {
			$rate = 0;
		} else {
			$rate = str_replace(',', '.', str_replace('.', '' , $rate));
		}

		$labor->rate = $rate;
		$labor->amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));

		$labor->save();

		return response()->json(['success' => 1]);
	}
}
