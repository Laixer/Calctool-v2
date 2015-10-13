<?php

namespace Calctool\Http\Controllers;

use Illuminate\Support\MessageBag;
use Longman\TelegramBot\Request;

class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /relation
	 *
	 * @return Response
	 */
	public function getAll()
	{
		return view('admin.user');
	}

	public function getNew()
	{
		return view('admin.new_user');
	}

	public function getMyAccount()
	{
		return view('user.myaccount');
	}

	public function getMyAccountTelegram()
	{
		return response()->view('user.myaccount_telegram');
	}

	public function getPayment()
	{
		return response()->view('user.payment');
	}

	public function getMyAccountTelegramUnchain()
	{
		$tgram = Telegram::where('user_id','=',Auth::id())->first();
		if ($tgram)
			$tgram->delete();
		return Redirect::to('/myaccount/telegram');
	}

	public function getMyAccountDeactivate()
	{
		$user = Auth::user();
		$user->active = false;
		$user->save();

		Auth::logout();

		$data = array('email' => $user->email, 'username' => $user->username);
		Mailgun::send('mail.deactivate', $data, function($message) use ($data) {
			$message->to($data['email'], strtolower(trim($data['username'])))->subject('Calctool - Account gedeactiveerd');
		});

		if ($_ENV['TELEGRAM_ENABLED']) {
			$tgram = Telegram::where('user_id','=',$user->id)->first();
			if ($tgram && $tgram->alert) {

				$telegram = new Longman\TelegramBot\Telegram($_ENV['TELEGRAM_API'], $_ENV['TELEGRAM_NAME']);
				Request::initialize($telegram);

				$data = array();
				$data['chat_id'] = $tgram->uid;
				$data['text'] = "Je CalculatieTool account was zojuist gedeactiveerd. Mocht dit niet bedoeling zijn geweest neem dan contact met ons op.";

				$result = Request::sendMessage($data);
			}
		}

		$log = new Audit;
		$log->ip = $_SERVER['REMOTE_ADDR'];
		$log->event = '[DEACTIVATE] [SUCCESS]';
		$log->user_id = $user->id;
		$log->save();

		return Redirect::to('/login');
	}

	public function doMyAccountTelegramUpdate()
	{

		$tgram = Telegram::where('user_id','=',Auth::id())->first();
		if ($tgram) {
			if (Input::get('toggle-alert'))
				$tgram->alert = true;
			else
				$tgram->alert = false;

			$tgram->save();
		}

		return Redirect::back()->with('success', 'Instellingen opgeslagen');
	}

	public function doPayment()
	{
		$rules = array(
			'payoption' => array('required'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			$mollie = new Mollie_API_Client;
			$mollie->setApiKey($_ENV['MOLLIE_API']);

			$amount = 0;
			$description = 'None';
			$increment_months = 0;
			switch (Input::get('payoption')) {
				case 1:
					$amount = 36.24;
					$description = 'Verleng met een maand';
					$increment_months = 1;
					break;
				case 3:
					$amount = 97.84;
					$description = 'Verleng met 4 maanden';
					$increment_months = 3;
					break;
				case 6:
					$amount = 184.83;
					$description = 'Verleng met 6 maanden';
					$increment_months = 6;
					break;
				case 12:
					$amount = 347.90;
					$description = 'Verleng met 12 maanden';
					$increment_months = 12;
					break;
				default:
					$errors = new MessageBag(['status' => ['Geen geldige optie']]);
					return Redirect::to('myaccount')->withErrors($errors);
			}

			$token = sha1(mt_rand().time());

			$payment = $mollie->payments->create(array(
				"amount"      => $amount,
				"description" => $description,
				"webhookUrl" => url('payment/webhook/'),
				"redirectUrl" => url('payment/order/'.$token),
				"metadata"    => array(
				"token" => $token,
				"uid" => Auth::id(),
				"incr" => $increment_months
				),
			));

			$order = new Payment;
			$order->transaction = $payment->id;
			$order->token = $token;
			$order->amount = $amount;
			$order->status = $payment->status;
			$order->increment = $increment_months;
			$order->description = $description;
			$order->method = '';
			$order->user_id = Auth::id();

			$order->save();

			$log = new Audit;
			$log->ip = $_SERVER['REMOTE_ADDR'];
			$log->event = '[PAYMENT] [REQUESTED]';
			$log->user_id = Auth::id();
			$log->save();

			return Redirect::to($payment->links->paymentUrl);
		}
	}

	public function doPaymentUpdate()
	{
		$order = Payment::where('transaction','=',Input::get('id'))->where('status','=','open')->first();
		if (!$order) {
			return;
		}

		$mollie = new Mollie_API_Client;
		$mollie->setApiKey($_ENV['MOLLIE_API']);

		$payment = $mollie->payments->get($order->transaction);
		if ($payment->metadata->token != $order->token)
			return;

		if ($payment->metadata->uid != $order->user_id)
			return;

		$order->status = $payment->status;
		$order->method = $payment->method;
		$order->save();

		if ($payment->isPaid()) {
			$user = User::find($order->user_id);
			$expdate = $user->expiration_date;
			$user->expiration_date = date('Y-m-d', strtotime("+".$order->increment." month", strtotime($expdate)));

			$user->save();

			$data = array('email' => $user->email, 'amount' => number_format($order->amount, 2,",","."), 'expdate' => date('j F Y', strtotime($user->expiration_date)), 'username' => $user->username);
			Mailgun::send('mail.paid', $data, function($message) use ($data) {
				$message->to($data['email'], strtolower(trim($data['username'])))->subject('Calctool - Abonement verlengt');
			});

			if ($_ENV['TELEGRAM_ENABLED']) {
				$tgram = Telegram::where('user_id','=',$user->id)->first();
				if ($tgram && $tgram->alert) {

					// create Telegram API object
					$telegram = new Longman\TelegramBot\Telegram($_ENV['TELEGRAM_API'], $_ENV['TELEGRAM_NAME']);
					Request::initialize($telegram);

					$data = array();
					$data['chat_id'] = $tgram->uid;
					$data['text'] = "De betaling van " . number_format($order->amount, 2,",",".") . " is in goede orde ontvangen en je account is verlengt tot " . date('j F Y', strtotime($user->expiration_date));

					$result = Request::sendMessage($data);
				}
			}

			$log = new Audit;
			$log->ip = $_SERVER['REMOTE_ADDR'];
			$log->event = '[PAYMENT] [SUCCESS]';
			$log->user_id = $user->id();
			$log->save();

		}
		return json_encode(['success' => 1]);
	}

	public function getPaymentFinish()
	{
		$order = Payment::where('token','=',Route::Input('token'))->first();
		if (!$order) {
			$errors = new MessageBag(['status' => ['Transactie niet geldig']]);
			return Redirect::to('myaccount')->withErrors($errors);
		}

		$mollie = new Mollie_API_Client;
		$mollie->setApiKey($_ENV['MOLLIE_API']);

		$payment = $mollie->payments->get($order->transaction);
		if ($payment->isPaid()) {
			return Redirect::to('myaccount')->with('success','Bedankt voor uw betaling');
		} else if ($payment->isOpen() || $payment->isPending()) {
			return Redirect::to('myaccount')->with('success','Betaling is nog niet bevestigd, dit kan enkele dagen duren');
		} else if ($payment->isCancelled()) {
			$order->status = $payment->status;
			$order->save();
			$errors = new MessageBag(['status' => ['Betaling is afgebroken']]);
			return Redirect::to('myaccount')->withErrors($errors);
		} else if ($payment->isExpired()) {
			$order->status = $payment->status;
			$order->save();
			$errors = new MessageBag(['status' => ['Betaling is verlopen']]);
			return Redirect::to('myaccount')->withErrors($errors);
		}
		$errors = new MessageBag(['status' => ['Transactie niet afgerond ('.$payment->status.')']]);
		return Redirect::to('myaccount')->withErrors($errors);
	}

	public function doUpdateSecurity()
	{
		$rules = array(
			'secret' => array('confirmed','min:5'),
			'secret_confirmation' => array('min:5'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			$user = Auth::user();
			if (Input::get('secret'))
				$user->secret = Hash::make(Input::get('secret'));
			if (Input::get('toggle-api'))
				$user->api_access = true;
			else
				$user->api_access = false;

			$user->save();

			if (Input::get('secret')) {
				$data = array('email' => Auth::user()->email, 'username' => Auth::user()->username);
				Mailgun::send('mail.password_update', $data, function($message) use ($data) {
					$message->to($data['email'], strtolower(trim($data['username'])))->subject('Calctool - Wachtwoord aangepast');
				});

				if ($_ENV['TELEGRAM_ENABLED']) {
					$tgram = Telegram::where('user_id','=',$user->id)->first();
					if ($tgram && $tgram->alert) {

						// create Telegram API object
						$telegram = new Longman\TelegramBot\Telegram($_ENV['TELEGRAM_API'], $_ENV['TELEGRAM_NAME']);
						Request::initialize($telegram);

						$data = array();
						$data['chat_id'] = $tgram->uid;
						$data['text'] = "Het wachtwoord van je account voor de Calculatie Tool is aangepast";

						$result = Request::sendMessage($data);
					}
				}
			}

			$log = new Audit;
			$log->ip = $_SERVER['REMOTE_ADDR'];
			$log->event = '[SECURITY_UPDATE] [SUCCESS]';
			$log->user_id = Auth::id();
			$log->save();

			return Redirect::back()->with('success', 'Instellingen opgeslagen');
		}
	}

	public function doUpdateNotepad()
	{
		$user = Auth::user();
		if (Input::get('notepad')) {
			$user->notepad = Input::get('notepad');
			$user->save();
		}

		return Redirect::back()->with('success', 'Opgeslagen');
	}

	public function doMyAccountUser()
	{
		$rules = array(
			'firstname' => array('required','max:30'),
			'mobile' => array('numeric','max:14'),
			'phone' => array('numeric','max:14'),
			'email' => array('required','email','max:80'),
			'website' => array('url','max:180'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			/* General */
			$user = Auth::user();

			/* Contact */
			$user->firstname = Input::get('firstname');$user->firstname = Input::get('firstname');
			if (Input::get('lastname'))
				$user->lastname = Input::get('lastname');
			if (Input::get('gender')) {
				if (Input::get('gender') == '-1')
					$user->gender = NULL;
				else
					$user->gender = Input::get('gender');
			}
			$user->email = Input::get('email');
			if (Input::get('mobile'))
				$user->mobile = Input::get('mobile');
			if (Input::get('phone'))
				$user->phone = Input::get('phone');
			if (Input::get('website'))
				$user->website = Input::get('website');

			$user->save();

			return Redirect::back()->with('success', 'Gegevens opgeslagen');
		}
	}

	public function doNew()
	{
		$rules = array(
			/* General */
			'username' => array('required'),
			'secret' => array('required'),

			/* Contact */
			'lastname' => array('required','max:50'),
			'firstname' => array('required','max:30'),
			'gender' => array('required'),
			'mobile' => array('alpha_num','max:14'),
			'telephone' => array('alpha_num','max:14'),
			'email' => array('required','email','max:80'),
			'website' => array('url','max:180'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			/* General */
			$user = new User;
			$user->username = Input::get('username');
			$user->secret = Hash::make(Input::get('secret'));
			$user->user_type = 1;//Input::get('user_type');

			/* Contact */
			$user->firstname = Input::get('firstname');
			$user->lastname = Input::get('lastname');
			$user->gender = Input::get('gender');
			$user->email = Input::get('email');
			$user->mobile = Input::get('mobiler');
			$user->phone = Input::get('telephone');
			$user->website = Input::get('website');

			/* Overig */
			$user->note = Input::get('note');

			/* System */
			$user->api = md5(mt_rand());
			$user->ip = $_SERVER['REMOTE_ADDR'];
			$user->referral_key = md5(mt_rand());

			$user->save();

			return Redirect::back()->with('success', 1);
		}
	}

	public function doUpdateIban()
	{
		$rules = array(
			'id' => array('required','integer'),
			'iban' => array('alpha_num'),
			'iban_name' => array('required','max:50')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {
			$iban = Iban::find(Input::get('id'));
			if (!$iban || !$iban->isOwner()) {
				return Redirect::back()->withInput(Input::all());
			}
			$iban->iban = Input::get('iban');
			$iban->iban_name = Input::get('iban_name');

			$iban->save();

			$data = array('email' => Auth::user()->email, 'username' => Auth::user()->username);
			Mailgun::send('mail.iban_update', $data, function($message) use ($data) {
				$message->to($data['email'], strtolower(trim($data['username'])))->subject('Calctool - Betaalgegevens aangepast');
			});

			if ($_ENV['TELEGRAM_ENABLED']) {
				$tgram = Telegram::where('user_id','=',$user->id)->first();
				if ($tgram && $tgram->alert) {

					// create Telegram API object
					$telegram = new Longman\TelegramBot\Telegram($_ENV['TELEGRAM_API'], $_ENV['TELEGRAM_NAME']);
					Request::initialize($telegram);

					$data = array();
					$data['chat_id'] = $tgram->uid;
					$data['text'] = "Het IBAN rekeningnummer en/of de tenaamstelling is aangepast op Calculatie Tool";

					$result = Request::sendMessage($data);
				}
			}

			$log = new Audit;
			$log->ip = $_SERVER['REMOTE_ADDR'];
			$log->event = '[IBAN_UPDATE] [SUCCESS]';
			$log->user_id = Auth::id();
			$log->save();

			return Redirect::back()->with('success', 1);
		}
	}

	public function doNewIban()
	{
		$rules = array(
			'iban' => array('alpha_num'),
			'iban_name' => array('required','max:50')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {
			$iban = new Iban;
			$iban->iban = Input::get('iban');
			$iban->iban_name = Input::get('iban_name');
			$iban->user_id = Auth::id();

			$iban->save();

			return Redirect::back()->with('success', 1);
		}
	}

	public function doUpdatePreferences()
	{
		$user = Auth::user();
		if (Input::get('pref_mailings_optin'))
			$user->pref_mailings_optin = true;
		else
			$user->pref_mailings_optin = false;

		if (Input::get('pref_hourrate_calc'))
			$user->pref_hourrate_calc = str_replace(',', '.', str_replace('.', '' , Input::get('pref_hourrate_calc')));
		if (Input::get('pref_hourrate_more'))
			$user->pref_hourrate_more = str_replace(',', '.', str_replace('.', '' , Input::get('pref_hourrate_more')));
		if (Input::get('pref_profit_calc_contr_mat'))
			$user->pref_profit_calc_contr_mat = str_replace(',', '.', str_replace('.', '' , Input::get('pref_profit_calc_contr_mat')));
		if (Input::get('pref_profit_calc_contr_equip'))
			$user->pref_profit_calc_contr_equip = str_replace(',', '.', str_replace('.', '' , Input::get('pref_profit_calc_contr_equip')));
		if (Input::get('pref_profit_calc_subcontr_mat'))
			$user->pref_profit_calc_subcontr_mat = str_replace(',', '.', str_replace('.', '' , Input::get('pref_profit_calc_subcontr_mat')));
		if (Input::get('pref_profit_calc_subcontr_equip'))
			$user->pref_profit_calc_subcontr_equip = str_replace(',', '.', str_replace('.', '' , Input::get('pref_profit_calc_subcontr_equip')));
		if (Input::get('pref_profit_more_contr_mat'))
			$user->pref_profit_more_contr_mat = str_replace(',', '.', str_replace('.', '' , Input::get('pref_profit_more_contr_mat')));
		if (Input::get('pref_profit_more_contr_equip'))
			$user->pref_profit_more_contr_equip = str_replace(',', '.', str_replace('.', '' , Input::get('pref_profit_more_contr_equip')));
		if (Input::get('pref_profit_more_subcontr_mat'))
			$user->pref_profit_more_subcontr_mat = str_replace(',', '.', str_replace('.', '' , Input::get('pref_profit_more_subcontr_mat')));
		if (Input::get('pref_profit_more_subcontr_equip'))
			$user->pref_profit_more_subcontr_equip = str_replace(',', '.', str_replace('.', '' , Input::get('pref_profit_more_subcontr_equip')));

		if (Input::get('pref_email_offer'))
			$user->pref_email_offer = Input::get('pref_email_offer');
		if (Input::get('pref_offer_description'))
			$user->pref_offer_description = Input::get('pref_offer_description');
		if (Input::get('pref_closure_offer'))
			$user->pref_closure_offer = Input::get('pref_closure_offer');
		if (Input::get('pref_email_invoice'))
			$user->pref_email_invoice = Input::get('pref_email_invoice');
		if (Input::get('pref_invoice_description'))
			$user->pref_invoice_description = Input::get('pref_invoice_description');
		if (Input::get('pref_invoice_closure'))
			$user->pref_invoice_closure = Input::get('pref_invoice_closure');
		if (Input::get('pref_email_invoice_first_reminder'))
			$user->pref_email_invoice_first_reminder = Input::get('pref_email_invoice_first_reminder');
		if (Input::get('pref_email_invoice_last_reminder'))
			$user->pref_email_invoice_last_reminder = Input::get('pref_email_invoice_last_reminder');
		if (Input::get('pref_email_invoice_first_demand'))
			$user->pref_email_invoice_first_demand = Input::get('pref_email_invoice_first_demand');
		if (Input::get('pref_email_invoice_last_demand'))
			$user->pref_email_invoice_last_demand = Input::get('pref_email_invoice_last_demand');
		if (Input::get('offernumber_prefix'))
			$user->offernumber_prefix = Input::get('offernumber_prefix');
		if (Input::get('invoicenumber_prefix'))
			$user->invoicenumber_prefix = Input::get('invoicenumber_prefix');
		if (Input::get('administration_cost'))
			$user->administration_cost = str_replace(',', '.', str_replace('.', '' , Input::get('administration_cost')));

		$user->save();

		$log = new Audit;
		$log->ip = $_SERVER['REMOTE_ADDR'];
		$log->event = '[PREFSUPDATE] [SUCCESS]';
		$log->user_id = $user->id;
		$log->save();

		return Redirect::back()->with('success', 'Voorkeuren opgeslagen');
	}
}
