<?php

class ProjectController extends Controller {

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

	public function getEdit()
	{
		return View::make('user.edit_project');
	}

	public function doNew()
	{
		$rules = array(
			'name' => array('required','max:50'),
			'street' => array('required','alpha','max:60'),
			'address_number' => array('required','alpha_num','max:5'),
			'zipcode' => array('required','size:6'),
			'city' => array('required','alpha_num','max:35'),
			'province' => array('required','numeric'),
			'country' => array('required','numeric'),
			'hour_rate' => array('required','regex:/^\$?([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(.[0-9][0-9])?$/'),
			'more_hour_rate' => array('required','regex:/^\$?([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(.[0-9][0-9])?$/'),
			'profit_material_1' => array('required','numeric','between:0,200'),
			'profit_equipment_1' => array('required','numeric','between:0,200'),
			'profit_material_2' => array('required','numeric','between:0,200'),
			'profit_equipment_2' => array('required','numeric','between:0,200'),
			'more_profit_material_1' => array('required','numeric','between:0,200'),
			'more_profit_equipment_1' => array('required','numeric','between:0,200'),
			'more_profit_material_2' => array('required','numeric','between:0,200'),
			'more_profit_equipment_2' => array('required','numeric','between:0,200')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			$hour_rate = str_replace(',', '.', str_replace('.', '', Input::get('hour_rate')));
			if ($hour_rate<0 || $hour_rate>999) {
				return Redirect::back()->withErrors($validator)->withInput(Input::all());
			}

			$hour_rate_more = str_replace(',', '.', str_replace('.', '', Input::get('more_hour_rate')));
			if ($hour_rate_more<0 || $hour_rate_more>999) {
				return Redirect::back()->withErrors($validator)->withInput(Input::all());
			}

			$project = new Project;
			$project->project_name = Input::get('name');
			$project->address_street = Input::get('street');
			$project->address_number = Input::get('address_number');
			$project->address_postal = Input::get('zipcode');
			$project->address_city = Input::get('city');
			$project->note = Input::get('note');
			$project->hour_rate = $hour_rate;
			$project->hour_rate_more = $hour_rate_more;
			$project->profit_calc_contr_mat = Input::get('profit_material_1');
			$project->profit_calc_contr_equip = Input::get('profit_equipment_1');
			$project->profit_calc_subcontr_mat = Input::get('profit_material_2');
			$project->profit_calc_subcontr_equip = Input::get('profit_equipment_2');
			$project->profit_more_contr_mat = Input::get('more_profit_material_1');
			$project->profit_more_contr_equip = Input::get('more_profit_equipment_1');
			$project->profit_more_subcontr_mat = Input::get('more_profit_material_2');
			$project->profit_more_subcontr_equip = Input::get('more_profit_equipment_2');
			$project->user_id = Auth::id();
			$project->province_id = Input::get('province');
			$project->country_id = Input::get('country');
			$project->type_id = Input::get('type');
			$project->client_id = Input::get('contractor');

			$project->save();

			return Redirect::to('project-'.$project->id.'/edit')->with('success', 'Opgeslagen');
		}

	}

	public function getAll()
	{
		return View::make('user.project');
	}

	public function doUpdate()
	{
		$rules = array(
			'id' => array('required','integer'),
			'name' => array('required','max:50'),
			'street' => array('required','alpha','max:60'),
			'address_number' => array('required','alpha_num','max:5'),
			'zipcode' => array('required','size:6'),
			'city' => array('required','alpha_num','max:35'),
			'province' => array('required','numeric'),
			'country' => array('required','numeric'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			$project = Project::find(Input::get('id'));
			if (!$project || !$project->isOwner()) {
				return Redirect::back()->withInput(Input::all());
			}
			$project->project_name = Input::get('name');
			$project->address_street = Input::get('street');
			$project->address_number = Input::get('address_number');
			$project->address_postal = Input::get('zipcode');
			$project->address_city = Input::get('city');
			$project->note = Input::get('note');
			$project->province_id = Input::get('province');
			$project->country_id = Input::get('country');
			$project->client_id = Input::get('contractor');

			$project->save();

			return Redirect::back()->with('success', 'Aangepast');
		}

	}

	public function doUpdateProfit()
	{
		$rules = array(
			'id' => array('required','integer'),
			'hour_rate' => array('regex:/^\$?([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(.[0-9][0-9])?$/'),
			'more_hour_rate' => array('required','regex:/^\$?([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(.[0-9][0-9])?$/'),
			'profit_material_1' => array('numeric','between:0,200'),
			'profit_equipment_1' => array('numeric','between:0,200'),
			'profit_material_2' => array('numeric','between:0,200'),
			'profit_equipment_2' => array('numeric','between:0,200'),
			'more_profit_material_1' => array('required','numeric','between:0,200'),
			'more_profit_equipment_1' => array('required','numeric','between:0,200'),
			'more_profit_material_2' => array('required','numeric','between:0,200'),
			'more_profit_equipment_2' => array('required','numeric','between:0,200')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			$project = Project::find(Input::get('id'));
			if (!$project || !$project->isOwner()) {
				return Redirect::back()->withInput(Input::all());
			}

			$hour_rate = str_replace(',', '.', str_replace('.', '', Input::get('hour_rate')));
			if ($hour_rate<0 || $hour_rate>999) {
				return Redirect::back()->withErrors($validator)->withInput(Input::all());
			}

			$hour_rate_more = str_replace(',', '.', str_replace('.', '', Input::get('more_hour_rate')));
			if ($hour_rate_more<0 || $hour_rate_more>999) {
				return Redirect::back()->withErrors($validator)->withInput(Input::all());
			}

			if ($hour_rate)
				$project->hour_rate = $hour_rate;
			$project->hour_rate_more = $hour_rate_more;
			if (Input::get('profit_material_1'))
				$project->profit_calc_contr_mat = Input::get('profit_material_1');
			if (Input::get('profit_equipment_1'))
				$project->profit_calc_contr_equip = Input::get('profit_equipment_1');
			if (Input::get('profit_material_2'))
				$project->profit_calc_subcontr_mat = Input::get('profit_material_2');
			if (Input::get('profit_equipment_2'))
				$project->profit_calc_subcontr_equip = Input::get('profit_equipment_2');
			$project->profit_more_contr_mat = Input::get('more_profit_material_1');
			$project->profit_more_contr_equip = Input::get('more_profit_equipment_1');
			$project->profit_more_subcontr_mat = Input::get('more_profit_material_2');
			$project->profit_more_subcontr_equip = Input::get('more_profit_equipment_2');

			$project->save();

			return Redirect::back()->with('success', 'Aangepast');
		}

	}

	public function doUpdateWorkExecution()
	{
		$rules = array(
			'project' => array('required','integer'),
			'date' => array('required'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$project = Project::find(Input::get('project'));
			if (!$project || !$project->isOwner()) {
				return Redirect::back()->withInput(Input::all());
			}
			$project->work_execution = date('Y-m-d', strtotime(Input::get('date')));

			$project->save();

			return json_encode(['success' => 1]);
		}

	}

	public function doUpdateWorkCompletion()
	{
		$rules = array(
			'project' => array('required','integer'),
			'date' => array('required'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$project = Project::find(Input::get('project'));
			if (!$project || !$project->isOwner()) {
				return Redirect::back()->withInput(Input::all());
			}
			$project->work_completion = date('Y-m-d', strtotime(Input::get('date')));

			$project->save();

			return json_encode(['success' => 1]);
		}

	}

	public function doUpdateProjectClose()
	{
		$rules = array(
			'project' => array('required','integer'),
			'date' => array('required'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$project = Project::find(Input::get('project'));
			if (!$project || !$project->isOwner()) {
				return Redirect::back()->withInput(Input::all());
			}
			$project->project_close = date('Y-m-d', strtotime(Input::get('date')));

			$project->save();

			return json_encode(['success' => 1]);
		}

	}
}
