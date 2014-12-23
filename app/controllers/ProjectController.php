<?php

class ProjectController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /relation
	 *
	 * @return Response
	 */
	public function getNew()
	{
		return View::make('user.new_project');
	}

	public function doNew()
	{
		$rules = array(
			'name' => 'required|max:50',
			'street' => 'required|alpha|max:60',
			'address_number' => 'required|alpha_num|max:5',
			'zipcode' => 'required|size:6',
			'city' => 'required|alpha_num|max:35',
			'province' => 'required|numeric',
			'country' => 'required|numeric',
			'hour_rate' => 'required|numeric|between:0,1000',
			'more_hour_rate' => 'required|numeric|between:0,1000',
			'profit_material_1' => 'required|numeric|between:0,200',
			'profit_equipment_1' => 'required|numeric|between:0,200',
			'profit_material_2' => 'required|numeric|between:0,200',
			'profit_equipment_2' => 'required|numeric|between:0,200',
			'profit_material_3' => 'required|numeric|between:0,200',
			'profit_equipment_3' => 'required|numeric|between:0,200',
			'more_profit_material_1' => 'required|numeric|between:0,200',
			'more_profit_equipment_1' => 'required|numeric|between:0,200',
			'more_profit_material_2' => 'required|numeric|between:0,200',
			'more_profit_equipment_2' => 'required|numeric|between:0,200'
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			$project = new Project;
			$project->project_name = Input::get('name');
			$project->project_code = time();
			$project->address_street = Input::get('street');
			$project->address_number = Input::get('address_number');
			$project->address_postal = Input::get('zipcode');
			$project->address_city = Input::get('city');
			$project->note = Input::get('note');
			$project->hour_rate = Input::get('hour_rate');
			$project->hour_rate_more = Input::get('more_hour_rate');
			$project->profit_calc_contr_mat = Input::get('profit_material_1');
			$project->profit_calc_contr_equip = Input::get('profit_equipment_1');
			$project->profit_calc_subcontr_mat = Input::get('profit_material_2');
			$project->profit_calc_subcontr_equip = Input::get('profit_equipment_2');
			$project->profit_calc_estim_mat = Input::get('profit_material_3');
			$project->profit_calc_estim_equip = Input::get('profit_equipment_3');
			$project->profit_more_contr_mat = Input::get('more_profit_material_1');
			$project->profit_more_contr_equip = Input::get('more_profit_equipment_1');
			$project->profit_more_subcontr_mat = Input::get('more_profit_material_2');
			$project->profit_more_subcontr_equip = Input::get('more_profit_equipment_2');
			$project->user_id = Auth::user()->id;
			$project->province_id = Input::get('province');
			$project->country_id = Input::get('country');
			$project->type_id = Input::get('type');
			$project->client_id = Input::get('contractor');

			$project->save();

			return Redirect::back()->with('success', 1);
		}

	}

	public function getAll()
	{
		return View::make('user.project');
	}

}
