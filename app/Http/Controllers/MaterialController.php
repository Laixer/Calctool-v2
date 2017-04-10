<?php

namespace CalculatieTool\Http\Controllers;

use Illuminate\Http\Request;

use \CalculatieTool\Models\ProductSubCategory;
use \CalculatieTool\Models\Supplier;
use \CalculatieTool\Models\SubGroup;
use \CalculatieTool\Models\Product;
// use \CalculatieTool\Models\Element;

use \Auth;
use \DB;

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
			'query' => array('min:3'),
			'group' => array('integer'),
			'wholesale' => array('required', 'integer'),
		]);

		$rtn_products = array();

		$products = [];
		if ($request->has('group')) {
			$products = Product::where('group_id',$request->get('group'))->where('supplier_id', $request->get('wholesale'))->take(200)->get();
		} else if ($request->has('query')) {
			if ($request->get('query') == '***') {
				$products = Product::where('supplier_id', $request->get('wholesale'))->take(200)->get();
			} else {
				$products = Product::where('description', 'LIKE', '%'.strtolower($request->get('query')).'%')->where('supplier_id', $request->get('wholesale'))->take(200)->get();
			}
		}
		foreach ($products as $product) {
			$isFav = $product->user()->where('user_id', Auth::id())->count('id');
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

		return response()->json($rtn_products);
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

		return response()->json(['success' => 1, 'id' => $material->id]);
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
			return response()->json(['success' => 0]);
		$supplier = Supplier::find($product->supplier_id);
		if (!$supplier || !$supplier->isOwner()) {
			return response()->json(['success' => 3]);
		}

		$product->description = strtolower($request->get('name'));
		$product->unit = $request->get('unit');
		$product->price = str_replace(',', '.', str_replace('.', '' , $request->get('rate')));
		$product->group_id = $request->get('group');

		$product->save();

		return response()->json(['success' => 1]);
	}

	public function doDelete(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer','min:0'),
		]);

		$product = Product::find($request->get('id'));
		if (!$product)
			return response()->json(['success' => 0]);
		$supplier = Supplier::find($product->supplier_id);
		if (!$supplier || !$supplier->isOwner()) {
			return response()->json(['success' => 3]);
		}

		$product->delete();

		return response()->json(['success' => 1]);
	}

	public function doFavorite(Request $request)
	{
		$this->validate($request, [
			'matid' => array('required','integer'),
		]);

		$exist_product = Auth::user()->productFavorite()->where('product_id','=',$request->get('matid'))->first();
		if ($exist_product) {
			Auth::user()->productFavorite()->detach($exist_product);
			return response()->json(['success' => 1]);
		}

		$product = Product::find($request->get('matid'));
		Auth::user()->productFavorite()->attach($product);

		return response()->json(['success' => 1]);
	}

	/*public function doNewElement(Request $request)
	{
		$this->validate($request, [
			'name' => array('required'),
		]);

		$element = new Element;
		$element->user_id = Auth::id();
		$element->name = $request->input('name');
		$element->description = $request->input('desc');

		$element->save();

		return response()->json(['success' => 1]);
	}*/

	public function doUploadCSV(Request $request)
	{
		$this->validate($request, [
			'csvfile' => array('required'),
		]);

		if ($request->hasFile('csvfile')) {
			$file = $request->file('csvfile');

			$mysupplier = Supplier::where('user_id','=',Auth::id())->first();
			if (!$mysupplier) {
				$mysupplier = Supplier::create(array('user_id' => Auth::id()));
			}

			$group = SubGroup::where('group_type','diversen (deel 1)')->first();

			$row = 0;
			if (($handle = fopen($file->getRealPath(), "r")) !== FALSE) {
				while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
					if ($row++ == 0)
						continue;
					
					if (count($data)<4)
						continue;
					
					$price = str_replace(',', '.', str_replace('.', '' , trim($data[2])));
					if (!$price)
						$price = 0;
					if (!is_numeric($price))
						$price = 0;

					Product::create(array(
						'description' => strtolower($data[0]),
						'unit' => $data[1],
						'price' => $price,
						'group_id' => $group->id,
						'supplier_id' => $mysupplier->id
					));
				}
				fclose($handle);
			}

			return back()->with('success', 'Materialenlijst geimporteerd');
		} else {
			// redirect our user back to the form with the errors from the validator
			return back()->withErrors('Geen CSV geupload');
		}
	}
	
	public function getListSubcat(Request $request, $type, $id) {
		$rs = [];
		if ($type == 'group') {
			$rs = DB::table('product_sub_category')->select('product_sub_category.id', 'sub_category_name as name')->join('product_category', 'product_sub_category.category_id', '=', 'product_category.id')->where('group_id',$id)->get();
		} else {
			$rs = ProductSubCategory::select('product_sub_category.id', 'sub_category_name as name')->where('category_id',$id)->get();
		}
		return response()->json($rs);
	}
}
