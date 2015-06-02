<?php

class MaterialController extends BaseController {

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

			$products = Product::where('description', 'LIKE', '%'.Input::get('query').'%')->take(100)->get();
			foreach ($products as $product) {
				array_push($rtn_products, array(
					'id' => $product['id'],
					'unit' => $this->convertUnit($product['unit']),
					'unit_price' => $product['unit_price'],
					'price' => '&euro; '.number_format($product['price'], 2, ",","."),
					'package' => $this->convertPackage($product['package_length'], $product['package_height'], $product['package_width']),
					'minimum_quantity' => $product['minimum_quantity'],
					'description' => $product['description'],
				));
			}

			return Response::json($rtn_products);
		}
	}

}
