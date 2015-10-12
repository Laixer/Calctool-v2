<?php

namespace Calctool\Http\Controllers;

class MaterialController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function getList()
	{
		return View::make('material.list');
	}

	private function convertUnit($unit)
	{
		switch ($unit) {
			case 'PCE':
				return 'Stuk';
			case 'MTR':
				return 'Meter';
			case 'MTK':
				return 'M<sup>2</sup>';

			default:
				return $unit;
		}
	}

	private function convertUnitPlain($unit)
	{
		switch ($unit) {
			case 'PCE':
				return 'Stuk';
			case 'MTR':
				return 'Meter';
			case 'MTK':
				return 'M2';

			default:
				return $unit;
		}
	}

	private function convertPackage($l, $h, $w)
	{
		$length = round($l);
		$height = round($h);
		$width = round($w);
		return $length.'x'.$height.'x'.$width;
	}

	public function doSearch()
	{
		$rules = array(
			'query' => array('required'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {

			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {
			$rtn_products = array();

			$query = strtolower(Input::get('query'));

			$suppliers = array();
			$mysupplier = Supplier::where('user_id','=',Auth::id())->first();
			if ($mysupplier) {
				array_push($suppliers, $mysupplier->id);
			}
			foreach (Supplier::whereNull('user_id')->get() as $supplier) {
				array_push($suppliers, $supplier->id);
			}

			if (Input::get('group') != '0') {
				$products = Product::where('description', 'LIKE', '%'.$query.'%')->whereIn('supplier_id', $suppliers)->where('group_id','=',Input::get('group'))->take(400)->get();
			} else {
				$products = Product::where('description', 'LIKE', '%'.$query.'%')->whereIn('supplier_id', $suppliers)->take(400)->get();
			}
			foreach ($products as $product) {
				$isFav = $product->user()->where('user_id','=',Auth::id())->count('id');
				array_push($rtn_products, array(
					'id' => $product['id'],
					'unit' => $this->convertUnit($product['unit']),
					'punit' => $this->convertUnitPlain($product['unit']),
					'unit_price' => $product['unit_price'],
					'price' => '&euro; '.number_format($product['price'], 2, ",","."),
					'tprice' => '&euro; '.number_format($product['total_price'], 2, ",","."),
					'pricenum' => number_format($product['price'], 2, ",","."),
					'package' => $this->convertPackage($product['package_length'], $product['package_height'], $product['package_width']),
					'minimum_quantity' => number_format($product['minimum_quantity'], 2, ",","."),
					'description' => ucfirst($product['description']),
					'favorite' => $isFav,
				));
			}

			return Response::json($rtn_products);
		}
	}

	public function doNew()
	{
		$rules = array(
			'name' => array('required','max:50'),
			'unit' => array('required','max:10'),
			'rate' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'group' => array('required', 'integer')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$mysupplier = Supplier::where('user_id','=',Auth::id())->first();
			if (!$mysupplier) {
				$mysupplier = Supplier::create(array(
					'supplier_name' => Auth::user()->username,
					'user_id' => Auth::id()
				));
			}

			$material = Product::create(array(
				'unit' => Input::get('unit'),
				'price' => str_replace(',', '.', str_replace('.', '' , Input::get('rate'))),
				'description' => strtolower(Input::get('name')),
				'group_id' => Input::get('group'),
				'supplier_id' => $mysupplier->id
			));

			return json_encode(['success' => 1, 'id' => $material->id]);
		}
	}

	public function doUpdate()
	{
		$rules = array(
			'id' => array('integer','min:0'),
			'name' => array('required','max:50'),
			'unit' => array('required','max:10'),
			'rate' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'group' => array('required', 'integer')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$product = Product::find(Input::get('id'));
			if (!$product)
				return json_encode(['success' => 0]);
			$supplier = Supplier::find($product->supplier_id);
			if (!$supplier || !$supplier->isOwner()) {
				return json_encode(['success' => 3]);
			}

			$product->description = Input::get('name');
			$product->unit = Input::get('unit');
			$product->price = str_replace(',', '.', str_replace('.', '' , Input::get('rate')));
			$product->group_id = Input::get('group');

			$product->save();

			return json_encode(['success' => 1]);
		}
	}

	public function doDelete()
	{
		$rules = array(
			'id' => array('required','integer','min:0'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$product = Product::find(Input::get('id'));
			if (!$product)
				return json_encode(['success' => 0]);
			$supplier = Supplier::find($product->supplier_id);
			if (!$supplier || !$supplier->isOwner()) {
				return json_encode(['success' => 3]);
			}

			$product->delete();

			return json_encode(['success' => 1]);
		}
	}

	public function doFavorite()
	{
		$rules = array(
			'matid' => array('required','integer'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$exist_product = Auth::user()->productFavorite()->where('product_id','=',Input::get('matid'))->first();
			if ($exist_product) {
				Auth::user()->productFavorite()->detach($exist_product);
				return json_encode(['success' => 1]);
			}

			$product = Product::find(Input::get('matid'));
			Auth::user()->productFavorite()->attach($product);

			return json_encode(['success' => 1]);
		}
	}
}
