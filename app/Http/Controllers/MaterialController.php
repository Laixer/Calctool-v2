<?php

namespace Calctool\Http\Controllers;

use Illuminate\Http\Request;

use \Calctool\Models\Supplier;
use \Calctool\Models\Product;
use \Calctool\Models\Element;

use \Auth;

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
		return view('material.list');
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

	public function doSearch(Request $request)
	{
		$this->validate($request, [
			'query' => array('required'),
		]);

		$rtn_products = array();

		$query = strtolower($request->get('query'));

		$suppliers = array();
		$mysupplier = Supplier::where('user_id','=',Auth::id())->first();
		if ($mysupplier) {
			array_push($suppliers, $mysupplier->id);
		}
		foreach (Supplier::whereNull('user_id')->get() as $supplier) {
			array_push($suppliers, $supplier->id);
		}

		if ($request->get('group') != '0') {
			$products = Product::where('description', 'LIKE', '%'.$query.'%')->whereIn('supplier_id', $suppliers)->where('group_id','=',$request->get('group'))->take(400)->get();
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

		return json_encode($rtn_products);
	}

	public function doNew(Request $request)
	{
		$this->validate($request, [
			'name' => array('required','max:50'),
			'unit' => array('required','max:10'),
			'rate' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'group' => array('required', 'integer')
		]);

		$mysupplier = Supplier::where('user_id','=',Auth::id())->first();
		if (!$mysupplier) {
			$mysupplier = Supplier::create(array(
				'user_id' => Auth::id()
			));
		}

		$material = Product::create(array(
			'unit' => $request->get('unit'),
			'price' => str_replace(',', '.', str_replace('.', '' , $request->get('rate'))),
			'description' => strtolower($request->get('name')),
			'group_id' => $request->get('group'),
			'supplier_id' => $mysupplier->id
		));

		return json_encode(['success' => 1, 'id' => $material->id]);
	}

	public function doUpdate(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'name' => array('required','max:50'),
			'unit' => array('required','max:10'),
			'rate' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'group' => array('required', 'integer')
		]);

		$product = Product::find($request->get('id'));
		if (!$product)
			return json_encode(['success' => 0]);
		$supplier = Supplier::find($product->supplier_id);
		if (!$supplier || !$supplier->isOwner()) {
			return json_encode(['success' => 3]);
		}

		$product->description = $request->get('name');
		$product->unit = $request->get('unit');
		$product->price = str_replace(',', '.', str_replace('.', '' , $request->get('rate')));
		$product->group_id = $request->get('group');

		$product->save();

		return json_encode(['success' => 1]);
	}

	public function doDelete(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer','min:0'),
		]);

		$product = Product::find($request->get('id'));
		if (!$product)
			return json_encode(['success' => 0]);
		$supplier = Supplier::find($product->supplier_id);
		if (!$supplier || !$supplier->isOwner()) {
			return json_encode(['success' => 3]);
		}

		$product->delete();

		return json_encode(['success' => 1]);
	}

	public function doFavorite(Request $request)
	{
		$this->validate($request, [
			'matid' => array('required','integer'),
		]);

		$exist_product = Auth::user()->productFavorite()->where('product_id','=',$request->get('matid'))->first();
		if ($exist_product) {
			Auth::user()->productFavorite()->detach($exist_product);
			return json_encode(['success' => 1]);
		}

		$product = Product::find($request->get('matid'));
		Auth::user()->productFavorite()->attach($product);

		return json_encode(['success' => 1]);
	}

	public function doNewElement(Request $request)
	{
		$this->validate($request, [
			'name' => array('required'),
		]);

		$element = new Element;
		$element->user_id = Auth::id();
		$element->name = $request->input('name');
		$element->description = $request->input('desc');

		$element->save();

		return json_encode(['success' => 1]);
	}
}
