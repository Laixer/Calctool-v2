<?php

namespace Calctool\Http\Controllers;

use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;
use Longman\TelegramBot\Request as TRequest;

use \Calctool\Models\Payment;
use \Calctool\Models\User;
use \Calctool\Models\Telegram;
use \Calctool\Models\Audit;
use \Calctool\Models\Promotion;
use \Calctool\Models\BankAccount;

use \Auth;
use \Hash;
use \Mailgun;

class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /relation
	 *
	 * @return Response
	 */
	public function getMyAccountTelegramUnchain()
	{
		$tgram = Telegram::where('user_id','=',Auth::id())->first();
		if ($tgram)
			$tgram->delete();
		return redirect('/myaccount/telegram');
	}

	public function getMyAccountDeactivate()
	{
		$user = Auth::user();
		$user->active = false;
		$user->save();

		Auth::logout();

		$data = array('email' => $user->email, 'username' => $user->username);
		Mailgun::send('mail.deactivate', $data, function($message) use ($data) {
			$message->to($data['email'], strtolower(trim($data['username'])));
			$message->subject('CalculatieTool.com - Account gedeactiveerd');
			$message->from('info@calculatietool.com', 'CalculatieTool.com');
			$message->replyTo('info@calculatietool.com', 'CalculatieTool.com');
		});

		if ($_ENV['TELEGRAM_ENABLED']) {
			$tgram = Telegram::where('user_id','=',$user->id)->first();
			if ($tgram && $tgram->alert) {

				$telegram = new Longman\TelegramBot\Telegram($_ENV['TELEGRAM_API'], $_ENV['TELEGRAM_NAME']);
				TRequest::initialize($telegram);

				$data = array();
				$data['chat_id'] = $tgram->uid;
				$data['text'] = "Je CalculatieTool account was zojuist gedeactiveerd. Mocht dit niet bedoeling zijn geweest neem dan contact met ons op.";

				$result = TRequest::sendMessage($data);
			}
		}

		$log = new Audit;
		$log->ip = \Calctool::remoteAddr();
		$log->event = '[DEACTIVATE] [SUCCESS]';
		$log->user_id = $user->id;
		$log->save();

		return redirect('/login');
	}

	public function doMyAccountTelegramUpdate(Request $request)
	{
		$tgram = Telegram::where('user_id','=',Auth::id())->first();
		if ($tgram) {
			if ($request->get('toggle-alert'))
				$tgram->alert = true;
			else
				$tgram->alert = false;

			$tgram->save();
		}

		return back()->with('success', 'Instellingen opgeslagen');
	}

	public function getPayment(Request $request)
	{
		$amount = 27;
		$description = 'Verleng met een maand';
		$increment_months = 1;

		$promocode = $request->cookie('_dccod'.Auth::id());
		if ($promocode) {
			$promo = Promotion::find($promocode); //where
			$amount = $promo->amount;
			$description .= ' Actie:' . $promo->name;
		}

		$mollie = new \Mollie_API_Client;
		$mollie->setApiKey($_ENV['MOLLIE_API']);

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
		$log->ip = \Calctool::remoteAddr();
		$log->event = '[PAYMENT] [REQUESTED]';
		$log->user_id = Auth::id();
		$log->save();

		return redirect($payment->links->paymentUrl)->withCookie(cookie()->forget('_dccod'.Auth::id()));
	}

	public function doPaymentUpdate(Request $request)
	{
		$order = Payment::where('transaction','=',$request->get('id'))->where('status','=','open')->first();
		if (!$order) {
			return;
		}

		$mollie = new \Mollie_API_Client;
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
				$message->to($data['email'], strtolower(trim($data['username'])));
				$message->subject('CalculatieTool.com - Abonnement verlengd');
				$message->from('info@calculatietool.com', 'CalculatieTool.com');
				$message->replyTo('info@calculatietool.com', 'CalculatieTool.com');
			});

			if ($_ENV['TELEGRAM_ENABLED']) {
				$tgram = Telegram::where('user_id','=',$user->id)->first();
				if ($tgram && $tgram->alert) {

					// create Telegram API object
					$telegram = new Longman\TelegramBot\Telegram($_ENV['TELEGRAM_API'], $_ENV['TELEGRAM_NAME']);
					TRequest::initialize($telegram);

					$data = array();
					$data['chat_id'] = $tgram->uid;
					$data['text'] = "De betaling van " . number_format($order->amount, 2,",",".") . " is in goede orde ontvangen en je account is verlengt tot " . date('j F Y', strtotime($user->expiration_date));

					$result = TRequest::sendMessage($data);
				}
			}

			$log = new Audit;
			$log->ip = \Calctool::remoteAddr();
			$log->event = '[PAYMENT] [SUCCESS]';
			$log->user_id = $user->id();
			$log->save();

		}
		return json_encode(['success' => 1]);
	}

	public function getPaymentFinish(Request $request, $token)
	{
		$order = Payment::where('token','=',$token)->first();
		if (!$order) {
			$errors = new MessageBag(['status' => ['Transactie niet geldig']]);
			return redirect('myaccount')->withErrors($errors);
		}

		$mollie = new \Mollie_API_Client;
		$mollie->setApiKey($_ENV['MOLLIE_API']);

		$payment = $mollie->payments->get($order->transaction);
		if ($payment->isPaid()) {
			return redirect('myaccount')->with('success','Bedankt voor uw betaling');
		} else if ($payment->isOpen() || $payment->isPending()) {
			return redirect('myaccount')->with('success','Betaling is nog niet bevestigd, dit kan enkele dagen duren. Uw heeft in deze periode toegang tot uw account');
		} else if ($payment->isCancelled()) {
			$order->status = $payment->status;
			$order->save();
			$errors = new MessageBag(['status' => ['Betaling is afgebroken']]);
			return redirect('myaccount')->withErrors($errors);
		} else if ($payment->isExpired()) {
			$order->status = $payment->status;
			$order->save();
			$errors = new MessageBag(['status' => ['Betaling is verlopen']]);
			return redirect('myaccount')->withErrors($errors);
		}
		$errors = new MessageBag(['status' => ['Transactie niet afgerond ('.$payment->status.')']]);
		return redirect('myaccount')->withErrors($errors);
	}

	public function doUpdateSecurity(Request $request)
	{
		$this->validate($request, [
			'curr_secret' => array('required'),
			'secret' => array('confirmed','min:5'),
			'secret_confirmation' => array('min:5'),
		]);

		$user = Auth::user();

		$userdata = array(
			'username' 	=> $user->username,
			'password' 	=> $request->input('curr_secret'),
			'active' 	=> 1,
			'banned' 	=> NULL
		);

		if (!Auth::validate($userdata)) {
			$errors = new MessageBag(['status' => ['Huidige wachtwoord klopt niet']]);
			return back()->withErrors($errors);
		}

		if ($request->get('secret'))
			$user->secret = Hash::make($request->get('secret'));
		if ($request->get('toggle-api'))
			$user->api_access = true;
		else
			$user->api_access = false;

		$user->save();

		if ($request->get('secret')) {
			$data = array('email' => Auth::user()->email, 'username' => Auth::user()->username);
			Mailgun::send('mail.password_update', $data, function($message) use ($data) {
				$message->to($data['email'], strtolower(trim($data['username'])));
				$message->subject('CalculatieTool.com - Wachtwoord aangepast');
				$message->from('info@calculatietool.com', 'CalculatieTool.com');
				$message->replyTo('info@calculatietool.com', 'CalculatieTool.com');
			});

			if ($_ENV['TELEGRAM_ENABLED']) {
				$tgram = Telegram::where('user_id','=',$user->id)->first();
				if ($tgram && $tgram->alert) {

					$telegram = new Longman\TelegramBot\Telegram($_ENV['TELEGRAM_API'], $_ENV['TELEGRAM_NAME']);
					TRequest::initialize($telegram);

					$data = array();
					$data['chat_id'] = $tgram->uid;
					$data['text'] = "Het wachtwoord van je account voor de Calculatie Tool is aangepast";

					$result = TRequest::sendMessage($data);
				}
			}
		}

		$log = new Audit;
		$log->ip = \Calctool::remoteAddr();
		$log->event = '[SECURITY_UPDATE] [SUCCESS]';
		$log->user_id = Auth::id();
		$log->save();

		return back()->with('success', 'Instellingen opgeslagen');
	}

	public function doUpdateNotepad(Request $request)
	{
		$user = Auth::user();
		if ($request->get('notepad')) {
			$user->notepad = $request->get('notepad');
			$user->save();
		}

		return back()->with('success', 'Opgeslagen');
	}

	public function doMyAccountUser(Request $request)
	{
		$this->validate($request, [
			'firstname' => array('required','max:30'),
			'mobile' => array('numeric','max:14'),
			'phone' => array('numeric','max:14'),
			'email' => array('required','email','max:80'),
			'website' => array('url','max:180'),
		]);

		/* General */
		$user = Auth::user();

		/* Contact */
		$user->firstname = $request->get('firstname');$user->firstname = $request->get('firstname');
		if ($request->get('lastname'))
			$user->lastname = $request->get('lastname');
		if ($request->get('gender')) {
			if ($request->get('gender') == '-1')
				$user->gender = NULL;
			else
				$user->gender = $request->get('gender');
		}

		if ($user->email != $request->get('email')) {
			$email = strtolower(trim($request->input('email')));

			if (User::where('email',$email)->count()>0) {
				$errors = new MessageBag(['status' => ['Email wordt al gebruikt']]);
				return back()->withErrors($errors);
			}

			$user->email = $email;
		}

		if ($request->get('mobile'))
			$user->mobile = $request->get('mobile');
		if ($request->get('phone'))
			$user->phone = $request->get('phone');
		if ($request->get('website'))
			$user->website = $request->get('website');

		$user->save();

		return back()->with('success', 'Gegevens opgeslagen');
	}

	public function doNew(Request $request)
	{
		$this->validate($request, [
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
		]);

		/* General */
		$user = new User;
		$user->username = $request->get('username');
		$user->secret = Hash::make($request->get('secret'));
		$user->user_type = 1;//$request->get('user_type');

		/* Contact */
		$user->firstname = $request->get('firstname');
		$user->lastname = $request->get('lastname');
		$user->gender = $request->get('gender');
		$user->email = $request->get('email');
		$user->mobile = $request->get('mobiler');
		$user->phone = $request->get('telephone');
		$user->website = $request->get('website');

		/* Overig */
		$user->note = $request->get('note');

		/* System */
		$user->api = md5(mt_rand());
		$user->ip = \Calctool::remoteAddr();
		$user->referral_key = md5(mt_rand());

		$user->save();

		return back()->with('success', 'Nieuwe gebruiker aangemaakt');
	}

	public function doUpdateIban(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer'),
			'iban' => array('alpha_num'),
			'iban_name' => array('required','max:50')
		]);

		$relation = \Calctool\Models\Relation::find($request->input('id'));
		if (!$relation || !$relation->isOwner()) {
			return back()->withInput($request->all());
		}

		if (!$relation->iban && !$relation->iban_name) {
			$account = new BankAccount;
			$account->user_id = Auth::id();
			$account->account = $request->input('iban');
			$account->account_name = $request->input('iban_name');

			$account->save();
		}

		$relation->iban = $request->get('iban');
		$relation->iban_name = $request->get('iban_name');

		$relation->save();

		$data = array('email' => Auth::user()->email, 'username' => Auth::user()->username);
		Mailgun::send('mail.iban_update', $data, function($message) use ($data) {
			$message->to($data['email'], strtolower(trim($data['username'])));
			$message->subject('CalculatieTool.com - Betaalgegevens aangepast');
			$message->from('info@calculatietool.com', 'CalculatieTool.com');
			$message->replyTo('info@calculatietool.com', 'CalculatieTool.com');
		});

		if ($_ENV['TELEGRAM_ENABLED']) {
			$tgram = Telegram::where('user_id','=',Auth::id())->first();
			if ($tgram && $tgram->alert) {

				// create Telegram API object
				$telegram = new Longman\TelegramBot\Telegram($_ENV['TELEGRAM_API'], $_ENV['TELEGRAM_NAME']);
				TRequest::initialize($telegram);

				$data = array();
				$data['chat_id'] = $tgram->uid;
				$data['text'] = "Het IBAN rekeningnummer en/of de tenaamstelling is aangepast op Calculatie Tool";

				$result = TRequest::sendMessage($data);
			}
		}

		$log = new Audit;
		$log->ip = $_SERVER['REMOTE_ADDR'];
		$log->event = '[IBAN_UPDATE] [SUCCESS]';
		$log->user_id = Auth::id();
		$log->save();

		return back()->with('success', 'Betalingsgegevens zijn aangepast');
	}

	public function doUpdatePreferences(Request $request)
	{
		$user = Auth::user();
		if ($request->get('pref_mailings_optin'))
			$user->pref_mailings_optin = true;
		else
			$user->pref_mailings_optin = false;

		if ($request->get('pref_hourrate_calc'))
			$user->pref_hourrate_calc = str_replace(',', '.', str_replace('.', '' , $request->get('pref_hourrate_calc')));
		if ($request->get('pref_hourrate_more'))
			$user->pref_hourrate_more = str_replace(',', '.', str_replace('.', '' , $request->get('pref_hourrate_more')));
		if ($request->get('pref_profit_calc_contr_mat'))
			$user->pref_profit_calc_contr_mat = str_replace(',', '.', str_replace('.', '' , $request->get('pref_profit_calc_contr_mat')));
		if ($request->get('pref_profit_calc_contr_equip'))
			$user->pref_profit_calc_contr_equip = str_replace(',', '.', str_replace('.', '' , $request->get('pref_profit_calc_contr_equip')));
		if ($request->get('pref_profit_calc_subcontr_mat'))
			$user->pref_profit_calc_subcontr_mat = str_replace(',', '.', str_replace('.', '' , $request->get('pref_profit_calc_subcontr_mat')));
		if ($request->get('pref_profit_calc_subcontr_equip'))
			$user->pref_profit_calc_subcontr_equip = str_replace(',', '.', str_replace('.', '' , $request->get('pref_profit_calc_subcontr_equip')));
		if ($request->get('pref_profit_more_contr_mat'))
			$user->pref_profit_more_contr_mat = str_replace(',', '.', str_replace('.', '' , $request->get('pref_profit_more_contr_mat')));
		if ($request->get('pref_profit_more_contr_equip'))
			$user->pref_profit_more_contr_equip = str_replace(',', '.', str_replace('.', '' , $request->get('pref_profit_more_contr_equip')));
		if ($request->get('pref_profit_more_subcontr_mat'))
			$user->pref_profit_more_subcontr_mat = str_replace(',', '.', str_replace('.', '' , $request->get('pref_profit_more_subcontr_mat')));
		if ($request->get('pref_profit_more_subcontr_equip'))
			$user->pref_profit_more_subcontr_equip = str_replace(',', '.', str_replace('.', '' , $request->get('pref_profit_more_subcontr_equip')));

		if ($request->get('pref_email_offer'))
			$user->pref_email_offer = $request->get('pref_email_offer');
		if ($request->get('pref_offer_description'))
			$user->pref_offer_description = $request->get('pref_offer_description');
		if ($request->get('pref_closure_offer'))
			$user->pref_closure_offer = $request->get('pref_closure_offer');
		if ($request->get('pref_email_invoice'))
			$user->pref_email_invoice = $request->get('pref_email_invoice');
		if ($request->get('pref_invoice_description'))
			$user->pref_invoice_description = $request->get('pref_invoice_description');
		if ($request->get('pref_invoice_closure'))
			$user->pref_invoice_closure = $request->get('pref_invoice_closure');
		if ($request->get('pref_email_invoice_first_reminder'))
			$user->pref_email_invoice_first_reminder = $request->get('pref_email_invoice_first_reminder');
		if ($request->get('pref_email_invoice_last_reminder'))
			$user->pref_email_invoice_last_reminder = $request->get('pref_email_invoice_last_reminder');
		if ($request->get('pref_email_invoice_first_demand'))
			$user->pref_email_invoice_first_demand = $request->get('pref_email_invoice_first_demand');
		if ($request->get('pref_email_invoice_last_demand'))
			$user->pref_email_invoice_last_demand = $request->get('pref_email_invoice_last_demand');
		if ($request->get('offernumber_prefix'))
			$user->offernumber_prefix = $request->get('offernumber_prefix');
		if ($request->get('invoicenumber_prefix'))
			$user->invoicenumber_prefix = $request->get('invoicenumber_prefix');
		if ($request->get('administration_cost'))
			$user->administration_cost = str_replace(',', '.', str_replace('.', '' , $request->get('administration_cost')));

		$user->save();

		$log = new Audit;
		$log->ip = \Calctool::remoteAddr();
		$log->event = '[PREFSUPDATE] [SUCCESS]';
		$log->user_id = $user->id;
		$log->save();

		return back()->with('success', 'Voorkeuren opgeslagen');
	}

	public function doCheckPromotionCode(Request $request) {

		$promo = Promotion::where('code', strtoupper($request->get('code')))->where('active', true)->where('valid', '>=', date('Y-m-d H:i:s'))->first();
		if (!$promo)
			return json_encode(['success' => 0]);

		return response()->json(['success' => 1, 'amount' => $promo->amount, 'famount' => number_format($promo->amount, 0,",",".")])->withCookie(cookie('_dccod'.Auth::id(), $promo->id, 30));
	}
}
