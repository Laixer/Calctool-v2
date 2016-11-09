<?php

namespace Calctool\Http\Controllers;

use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;

use \Calctool\Models\Payment;
use \Calctool\Models\User;
use \Calctool\Models\Audit;
use \Calctool\Models\Promotion;
use \Calctool\Models\UserGroup;
use \Calctool\Models\BankAccount;

use \Auth;
use \Redis;
use \Hash;
use \Mailgun;
use \DB;

class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /relation
	 *
	 * @return Response
	 */
	public function getMyAccountDeactivate(Request $request)
	{
		$user = Auth::user();
		$user->active = false;
		$user->save();

		Auth::logout();

		$data = array('email' => $user->email, 'firstname' => $user->firstname, 'lastname' => $user->lastname);
		Mailgun::send('mail.deactivate', $data, function($message) use ($data) {
			$message->to($data['email'], ucfirst($data['firstname']) . ' ' . ucfirst($data['lastname']));
			$message->subject('CalculatieTool.com - Account gedeactiveerd');
			$message->from('info@calculatietool.com', 'CalculatieTool.com');
			$message->replyTo('info@calculatietool.com', 'CalculatieTool.com');
		});

		Audit::CreateEvent('account.deactivate.success', 'Account deactivated by user', $user->id);

		if (!config('app.debug')) {
			$data = array(
				'email' => $user->email,
				'firstname' => $user->firstname,
				'lastname' => $user->lastname,
				'reason' => $request->get('reason'),
			);
			Mailgun::send('mail.inform_deactivate_user', $data, function($message) use ($data) {
				$message->to('info@calculatietool.com', 'CalculatieTool.com');
				$message->subject('CalculatieTool.com - Account deactivatie');
				$message->from('info@calculatietool.com', 'CalculatieTool.com');
				$message->replyTo('info@calculatietool.com', 'CalculatieTool.com');
			});
		}

		return redirect('/login');
	}

	public function getPayment(Request $request)
	{
		if (\App::environment('local')) {
			$errors = new MessageBag(['status' => ['Callback niet mogelijk op local dev']]);
			return redirect('myaccount')->withErrors($errors);
		}

		$amount = UserGroup::find(Auth::user()->user_group)->subscription_amount;
		$description = 'Verleng met een maand';
		$increment_months = 1;
		$promo_id = -1;

		if (Redis::exists('promo:'.Auth::user()->username)) {
			$promo = Promotion::find(Redis::get('promo:'.Auth::user()->username));
			if ($promo) {
				$amount = $promo->amount;
				$description .= ' Actie:' . $promo->name;
				$promo_id = $promo->id;
				Redis::del('promo:'.Auth::user()->username);
			}
		}

		$mollie = new \Mollie_API_Client;
		$mollie->setApiKey(config('services.mollie.key'));

		$token = sha1(mt_rand().time());

		try {
			$payment = $mollie->payments->create(array(
				"amount"		=> $amount,
				"description"	=> $description,
				"webhookUrl"	=> url('payment/webhook/'),
				"redirectUrl"	=> url('payment/order/'.$token),
				"metadata"		=> array(
					"token"		=> $token,
					"uid"		=> Auth::id(),
					"incr"		=> $increment_months,
					"promo"		=> $promo_id,
				),
			));
		} catch (\Mollie_API_Exception $e) {
			Audit::CreateEvent('account.payment.initiated.failed', 'Create payment failed with ' . $e->getMessage());

			$errors = new MessageBag(['status' => ['Aanmaken van een betaling is mislukt']]);
			return redirect('myaccount')->withErrors($errors);
		}

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

		Audit::CreateEvent('account.payment.initiated.success', 'Create payment ' . $payment->id . ' for ' . $amount);

		return redirect($payment->links->paymentUrl)->withCookie(cookie()->forget('_dccod'.Auth::id()));
	}

	public function getPaymentFree(Request $request)
	{
		if (UserGroup::find(Auth::user()->user_group)->subscription_amount > 0) {
			$errors = new MessageBag(['status' => ['Abonnement vereist betaling']]);
			return redirect('myaccount')->withErrors($errors);
		}

		$user = Auth::user();
		$expdate = $user->expiration_date;
		$user->expiration_date = date('Y-m-d', strtotime("+1 month", strtotime($expdate)));

		$user->save();

		Audit::CreateEvent('account.payment.free.success', 'Payment free succeeded');

		return redirect('myaccount')->with('success','Bedankt voor uw betaling');
	}

	public function doPaymentUpdate(Request $request)
	{
		$order = Payment::where('transaction','=',$request->get('id'))->where('status','=','open')->first();
		if (!$order) {
			return;
		}

		$mollie = new \Mollie_API_Client;
		$mollie->setApiKey(config('services.mollie.key'));

		$payment = $mollie->payments->get($order->transaction);
		if ($payment->metadata->token != $order->token)
			return;

		if ($payment->metadata->uid != $order->user_id)
			return;

		if ($payment->metadata->promo != -1) {
			$order->promotion_id = $payment->metadata->promo;
		}

		$order->status = $payment->status;
		$order->method = $payment->method;
		$order->save();

		if ($payment->isPaid()) {
			$user = User::find($order->user_id);
			$expdate = $user->expiration_date;
			$user->expiration_date = date('Y-m-d', strtotime("+".$order->increment." month", strtotime($expdate)));

			$user->save();

			$data = array('email' => $user->email, 'amount' => number_format($order->amount, 2,",","."), 'expdate' => date('j F Y', strtotime($user->expiration_date)), 'firstname' => $user->firstname, 'lastname' => $user->lastname);
			Mailgun::send('mail.paid', $data, function($message) use ($data) {
				$message->to($data['email'], ucfirst($data['firstname']) . ' ' . ucfirst($data['lastname']));
				$message->subject('CalculatieTool.com - Abonnement verlengd');
				$message->from('info@calculatietool.com', 'CalculatieTool.com');
				$message->replyTo('info@calculatietool.com', 'CalculatieTool.com');
			});

			Audit::CreateEvent('account.payment.callback.success', 'Payment ' . $payment->id . ' succeeded');
		}

		return response()->json(['success' => 1]);
	}

	public function getPaymentFinish(Request $request, $token)
	{
		$order = Payment::where('token','=',$token)->first();
		if (!$order) {
			$errors = new MessageBag(['status' => ['Transactie niet geldig']]);
			return redirect('myaccount')->withErrors($errors);
		}

		$mollie = new \Mollie_API_Client;
		$mollie->setApiKey(config('services.mollie.key'));

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
			'curr_secret' => array('required','bail'),
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
			$user = Auth::user();
			$data = array('email' => $user->email, 'firstname' => $user->firstname, 'lastname' => $user->lastname);
			Mailgun::send('mail.password_update', $data, function($message) use ($data) {
				$message->to($data['email'], ucfirst($data['firstname']) . ' ' . ucfirst($data['lastname']));
				$message->subject('CalculatieTool.com - Wachtwoord aangepast');
				$message->from('info@calculatietool.com', 'CalculatieTool.com');
				$message->replyTo('info@calculatietool.com', 'CalculatieTool.com');
			});
		}

		Audit::CreateEvent('account.security.update.success', 'Password and/or confidential information updated');

		return back()->with('success', 'Instellingen opgeslagen');
	}

	public function doUpdateNotepad(Request $request)
	{
		$user = Auth::user();
		if ($request->get('notepad')) {
			$user->notepad = $request->get('notepad');
			$user->save();
		}

		Audit::CreateEvent('account.notepad.update.success', 'Notepad updated');

		return back()->with('success', 'Opgeslagen');
	}

	public function doMyAccountUser(Request $request)
	{
		$this->validate($request, [
			'firstname' => array('max:30'),
			'email' => array('required','email','max:80'),
			'website' => array('url','max:180'),
			'mobile' => array('numeric'),
			'phone' => array('numeric'),
		]);

		/* General */
		$user = Auth::user();

		/* Contact */
		if ($request->get('firstname'))
			$user->firstname = $request->get('firstname');
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
			$user->mobile = substr($request->get('mobile'), 0, 9);
		if ($request->get('phone'))
			$user->phone = substr($request->get('phone'), 0, 9);
		if ($request->get('website'))
			$user->website = $request->get('website');

		$user->save();

		Audit::CreateEvent('account.update.success', 'Account information updated');

		return back()->with('success', 'Gegevens opgeslagen');
	}

	//TODO is this still used?
	public function doNew(Request $request)
	{
		$this->validate($request, [
			/* General */
			'username' => array('required'),
			'secret' => array('required'),

			/* Contact */
			'lastname' => array('required','max:50'),
			// 'firstname' => array('required','max:30'),
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
		$user->user_group = 100;

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

		$user = Auth::user();

		$data = array('email' => Auth::user()->email, 'firstname' => $user->firstname, 'lastname' => $user->lastname);
		Mailgun::send('mail.iban_update', $data, function($message) use ($data) {
			$message->to($data['email'], ucfirst($data['firstname']) . ' ' . ucfirst($data['lastname']));
			$message->subject('CalculatieTool.com - Betaalgegevens aangepast');
			$message->from('info@calculatietool.com', 'CalculatieTool.com');
			$message->replyTo('info@calculatietool.com', 'CalculatieTool.com');
		});

		Audit::CreateEvent('account.iban.update.success', 'IBAN and/or account name updated');

		return back()->with('success', 'Betalingsgegevens zijn aangepast');
	}

	public function doUpdatePreferences(Request $request)
	{
		$this->validate($request, [
			'pref_hourrate_calc' => array('regex:/^\$?([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(.[0-9][0-9])?$/'),
			'pref_hourrate_more' => array('regex:/^\$?([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(.[0-9][0-9])?$/'),
		]);

		$user = Auth::user();
		if ($request->get('pref_use_ct_numbering'))
			$user->pref_use_ct_numbering = true;
		else
			$user->pref_use_ct_numbering = false;

		if ($request->get('pref_hourrate_calc') != "") {
			$hour_rate = floatval(str_replace(',', '.', str_replace('.', '', $request->get('pref_hourrate_calc'))));
			if ($hour_rate<0 || $hour_rate>999) {
				return back()->withInput($request->all());
			}

			$user->pref_hourrate_calc = $hour_rate;
		}

		if ($request->get('pref_hourrate_more') != "") {
			$hour_rate_more = floatval(str_replace(',', '.', str_replace('.', '', $request->get('pref_hourrate_more'))));
			if ($hour_rate_more<0 || $hour_rate_more>999) {
				return back()->withInput($request->all());
			}

			$user->pref_hourrate_more = $hour_rate_more;
		}

		if ($request->get('pref_profit_calc_contr_mat') != "")
			$user->pref_profit_calc_contr_mat = str_replace(',', '.', str_replace('.', '' , $request->get('pref_profit_calc_contr_mat')));
		if ($request->get('pref_profit_calc_contr_equip') != "")
			$user->pref_profit_calc_contr_equip = str_replace(',', '.', str_replace('.', '' , $request->get('pref_profit_calc_contr_equip')));
		if ($request->get('pref_profit_calc_subcontr_mat') != "")
			$user->pref_profit_calc_subcontr_mat = str_replace(',', '.', str_replace('.', '' , $request->get('pref_profit_calc_subcontr_mat')));
		if ($request->get('pref_profit_calc_subcontr_equip') != "")
			$user->pref_profit_calc_subcontr_equip = str_replace(',', '.', str_replace('.', '' , $request->get('pref_profit_calc_subcontr_equip')));
		if ($request->get('pref_profit_more_contr_mat') != "")
			$user->pref_profit_more_contr_mat = str_replace(',', '.', str_replace('.', '' , $request->get('pref_profit_more_contr_mat')));
		if ($request->get('pref_profit_more_contr_equip') != "")
			$user->pref_profit_more_contr_equip = str_replace(',', '.', str_replace('.', '' , $request->get('pref_profit_more_contr_equip')));
		if ($request->get('pref_profit_more_subcontr_mat') != "")
			$user->pref_profit_more_subcontr_mat = str_replace(',', '.', str_replace('.', '' , $request->get('pref_profit_more_subcontr_mat')));
		if ($request->get('pref_profit_more_subcontr_equip') != "")
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
		if ($request->get('offernumber_prefix') != "")
			$user->offernumber_prefix = $request->get('offernumber_prefix');
		if ($request->get('invoicenumber_prefix') != "")
			$user->invoicenumber_prefix = $request->get('invoicenumber_prefix');
		if ($request->get('administration_cost') != "")
			$user->administration_cost = str_replace(',', '.', str_replace('.', '' , $request->get('administration_cost')));

		if (session()->has('swap_session')) {
			$user->timestamps = false;
		}

		$user->save();

		Audit::CreateEvent('account.preference.update.success', 'Account preferences updated');

		return back()->with('success', 'Voorkeuren opgeslagen');
	}

	public function doCheckPromotionCode(Request $request) {

		$promo = Promotion::where('code', strtoupper($request->get('code')))->where('active', true)->where('valid', '>=', date('Y-m-d H:i:s'))->first();
		if (!$promo)
			return response()->json(['success' => 0]);

		$order = Payment::where('user_id',Auth::id())->where('promotion_id',$promo->id)->first();
		if ($order)
			return response()->json(['success' => 0]);

		Redis::del('promo:'.Auth::user()->username);
		Redis::set('promo:'.Auth::user()->username, $promo->id);
		Redis::expire('promo:'.Auth::user()->username, 600);

		return response()->json(['success' => 1, 'amount' => $promo->amount, 'famount' => number_format($promo->amount, 0,",",".")]);
	}

	public function doLoadDemoProject() {
		\DemoProjectTemplate::setup(Auth::id());

		Audit::CreateEvent('account.demoproject.success', 'Demoproject loaded for user');

		return back()->with('success', 'Demoproject is geladen in je projectenoverzicht op het dashboard');
	}

	public function doRevokeApp(Request $request, $client_id) {
		$client = DB::table('oauth_sessions')
				->join('oauth_clients', 'oauth_sessions.client_id', '=', 'oauth_clients.id')
                ->where('oauth_sessions.id', $client_id)
                ->where('oauth_sessions.owner_id', Auth::id())
                ->first();

        if (!$client)
        	return back();

        DB::table('oauth_sessions')
                ->where('id', $client_id)
                ->where('owner_id', Auth::id())
                ->delete();

		Audit::CreateEvent('account.oauth2.app.revoke.success', 'Application access revoked for ' . $client->name);

		return back()->with('success', 'Applicatie toegang ingetrokken');
	}
}
