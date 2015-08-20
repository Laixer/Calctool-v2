<?php

class OfferController extends Controller {

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

	public function doNewOffer()
	{
		$rules = array(
			'deliver' => array('required','integer','min:0'),
			'terms' => array('integer','min:0'),
			'valid' => array('required','integer','min:0'),
			'to_contact' => array('required'),
			'from_contact' => array('required'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {
			$offer = new Offer;
			$offer->to_contact_id = Input::get('to_contact');
			$offer->from_contact_id = Input::get('from_contact');
			$offer->description = Input::get('description');
			$offer->closure = Input::get('closure');
			if (Input::get('toggle-payment'))
				$offer->downpayment = Input::get('toggle-payment');
			if (Input::get('amount'))
				$offer->downpayment_amount = Input::get('amount');
			$offer->auto_email_reminder = false;
			$offer->deliver_id = Input::get('deliver');
			$offer->valid_id = Input::get('valid');
			if (Input::get('terms'))
				$offer->invoice_quantity = Input::get('terms');
			$offer->project_id = Route::Input('project_id');

			$options = [];
			if (Input::get('toggle-note'))
				$options['description'] = 1;
			if (Input::get('toggle-subcontr'))
				$options['total'] = 1;
			if (Input::get('toggle-activity'))
				$options['specification'] = 1;

			$offer->option_query = http_build_query($options);

			$offer->save();

			$newname = Auth::id().'-'.substr(md5(uniqid()), 0, 5).'-'.OfferController::getOfferCode(Route::Input('project_id')).'-offer.pdf';
			$pdf = PDF::loadView('calc.offer_pdf');
			$pdf->save('user-content/'.$newname);

			$resource = new Resource;
			$resource->resource_name = $newname;
			$resource->file_location = 'user-content/' . $newname;
			$resource->file_size = File::size('user-content/' . $newname);
			$resource->user_id = Auth::user()->id;
			$resource->description = 'Offerteversie';

			$resource->save();

			$offer->resource_id = $resource->id;

			$offer->save();

			Auth::user()->offer_counter++;
			Auth::user()->save();

			return Redirect::back()->with('success', 'Opgeslagen');
		}

	}

	public function doOfferClose()
	{
		$rules = array(
			'name' => array('required'),
			'value' => array('required'),
			'pk' => array('required','integer'),
			'project_id' => array('required','integer'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			$offer = Offer::find(Input::get('pk'));
			$offer->offer_finish = date('Y-m-d', strtotime(Input::get('value')));
			$offer->save();

			$first_id = 0;

			for ($i=0; $i < $offer->invoice_quantity; $i++) {
				$invoice = new Invoice;
				$invoice->priority = $i;
				$invoice->invoice_code = InvoiceController::getInvoiceCodeConcept(Input::get('project_id'));
				$invoice->payment_condition = 30;
				$invoice->offer_id = $offer->id;
				if (($i+1) == $offer->invoice_quantity)
					$invoice->isclose = true;
				if ($i == 0 && $offer->downpayment)
					$invoice->amount = $offer->downpayment_amount;
				$invoice->save();
				if ($i == 0)
					$first_id = $invoice->id;
			}

			if ($offer->invoice_quantity>1) {
				$invamount = 0;
				$invtotal = ResultEndresult::totalProject(Project::find(Input::get('project_id')));
				if ($offer->downpayment)
					$invamount = $offer->downpayment_amount;
				$invtotal-=$invamount;
				$input = array('id' => $first_id, 'project' => Input::get('project_id'), 'amount' => $invamount, 'totaal' => $invtotal);
				return App::make('InvoiceController')->doUpdateAmount(Input::merge($input));
			}

			return json_encode(['success' => 1]);
		}

	}

	/* id = $project->id */
	public static function getOfferCode($id)
	{
		return sprintf("%s%05d-%03d-%s", Auth::user()->offernumber_prefix, $id, Auth::user()->offer_counter, date('y'));
	}
}
