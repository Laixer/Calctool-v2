<?php

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

			if (Input::get('group')!='0')
				$products = Product::where('description', 'LIKE', '%'.$query.'%')->where('group_id','=',Input::get('group'))->take(100)->get();
			else
				$products = Product::where('description', 'LIKE', '%'.$query.'%')->take(100)->get();
			foreach ($products as $product) {
				array_push($rtn_products, array(
					'id' => $product['id'],
					'unit' => $this->convertUnit($product['unit']),
					'unit_price' => $product['unit_price'],
					'price' => '&euro; '.number_format($product['price'], 2, ",","."),
					'pricenum' => number_format($product['price'], 2, ",","."),
					'package' => $this->convertPackage($product['package_length'], $product['package_height'], $product['package_width']),
					'minimum_quantity' => number_format($product['minimum_quantity'], 2, ",","."),
					'description' => $product['description'],
				));
			}

			return Response::json($rtn_products);
		}
	}

}
