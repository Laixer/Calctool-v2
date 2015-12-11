<?php

namespace Calctool\Http\Controllers;

use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;

use \Calctool\Models\SysMessage;
use \Calctool\Models\Payment;
use \Calctool\Models\User;
use \Calctool\Models\OfferPost;
use \Calctool\Models\Resource;
use \Calctool\Models\MessageBox;
use \Calctool\Models\Audit;

use \Storage;
use \Auth;
use \Hash;
use \Redis;

class AdminController extends Controller {

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

	public function doNewAlert(Request $request)
	{
		$this->validate($request, [
			'level' => array('required'),
			'message' => array('required'),
		]);

		$alert = new SysMessage;
		$alert->level = $request->input('level');
		$alert->content = $request->input('message');
		$alert->active = true;

		$alert->save();

		return back()->with('success', 'Nieuwe alert is aangemaakt');

	}

	public function doDeleteAlert(Request $request)
	{
		$this->validate($request, [
			'id' => array('required'),
		]);

		$alert = SysMessage::find($request->input('id'));
		$alert->active = false;

		$alert->save();

		return json_encode(['success' => 1]);

	}

	public function doRefund(Request $request)
	{
		$this->validate($request, [
			'amount' => array('required'),
		]);

		$subtract = $request->input('amount');

		$mollie = new Mollie_API_Client;
		$mollie->setApiKey(env('MOLLIE_API', null));

		$payment = $mollie->payments->get($request->Input('transcode'));

		if ($subtract > ($payment->amount-$payment->amountRefunded))
			return back()->withErrors($validator)->withInput($request->all());

		$mollie->payments->refund($payment, $subtract);

		$order = Payment::where('transaction','=',$payment->id)->first();
		$order->status = $payment->status;
		$order->amount = $payment->amountRefunded;
		$order->save();

		if ($payment->amountRefunded == $payment->amount) {
			$user = User::find($order->user_id);
			$expdate = $user->expiration_date;
			$user->expiration_date = date('Y-m-d', strtotime("-".$order->increment." month", strtotime($expdate)));

			$data = array('email' => $user->email, 'amount' => number_format($order->amount, 2,",","."), 'username' => $user->username);
			Mailgun::send('mail.refund', $data, function($message) use ($data) {
				$message->to($data['email'], strtolower(trim($data['username'])))->subject('Calctool - Terugstorting');
			});

			$user->save();
		}

		return back()->with('success', 'Terugbetaling ingediend bij Payment Services Provider');
	}

	public function doNewUser(Request $request)
	{
		$request->merge(array('username' => strtolower(trim($request->input('username')))));
		$request->merge(array('email' => strtolower(trim($request->input('email')))));

		$this->validate($request, [
			/* General */
			'username' => array('required','unique:user_account'),
			'secret' => array('required'),
			'type' => array('required'),

			/* Contact */
			'lastname' => array('max:50'),
			'firstname' => array('max:30'),
			'mobile' => array('numeric'),
			'telephone' => array('numeric'),
			'email' => array('required','email','max:80','unique:user_account'),
			'website' => array('url','max:180'),

			/* Adress */
			'address_street' => array('alpha','max:60'),
			'address_number' => array('alpha_num','max:5'),
			'address_zipcode' => array('size:6'),
			'address_city' => array('alpha_num','max:35'),

			'expdate' => array('required'),
		]);

		/* General */
		$user = new User;
		$user->username = $request->input('username');
		$user->secret = Hash::make($request->input('secret'));
		$user->user_type = $request->input('type');

		/* Server */
		$user->api = md5(mt_rand());
		$user->token = sha1($user->secret);
		$user->referral_key = md5(mt_rand());
		$user->ip = \Calctool::remoteAddr();

		/* Contact */
		if ($request->input('firstname'))
			$user->firstname = $request->input('firstname');
		else
			$user->firstname = $user->username;
		if ($request->input('lastname'))
			$user->lastname = $request->input('lastname');
		$user->email = $request->input('email');
		if ($request->input('mobile'))
			$user->mobile = $request->input('mobile');
		if ($request->input('telephone'))
			$user->phone = $request->input('telephone');
		if ($request->input('website'))
			$user->website = $request->input('website');

		/* Overig */
		$user->expiration_date = $request->input('expdate');
		if ($request->input('note'))
			$user->note = $request->input('note');
		if ($request->input('notepad'))
			$user->notepad = $request->input('notepad');
		if ($request->input('confirmdate'))
			$user->confirmed_mail = $request->input('confirmdate');
		if ($request->input('bandate'))
			$user->banned = $request->input('bandate');
		if ($request->input('toggle-active'))
			$user->active = true;
		else
			$user->active = false;
		if ($request->input('toggle-api'))
			$user->api_access = true;
		else
			$user->api_access = false;
		if (!$request->input('gender') || $request->input('gender') == '-1')
			$user->gender = NULL;
		else
			$user->gender = $request->input('gender');

		$user->save();

		$log = new Audit;
		$log->ip = \Calctool::remoteAddr();
		$log->event = '[ADMIN] [CREATE] [SUCCESS] ' . Auth::user()->username;
		$log->user_id = $user->id;
		$log->save();

		return back()->with('success', 'Nieuwe gebruiker aangemaakt');
	}

	public function doUpdateUser(Request $request, $user_id)
	{
		$this->validate($request, [
			'username' => array('required'),
			'email' => array('required','email','max:80'),
		]);

		/* General */
		$user = User::find($user_id);
		if ($request->input('username')) {
			if ($user->username != $request->get('username')) {
				$username = strtolower(trim($request->input('username')));

				if (User::where('username',$username)->count()>0) {
					$errors = new MessageBag(['status' => ['Gebruikersnaam wordt al gebruikt']]);
					return back()->withErrors($errors);
				}

				$user->username = $username;
			}
		}
		if ($request->input('secret'))
			$user->secret = Hash::make($request->input('secret'));
		if ($request->input('type'))
			$user->user_type = $request->input('type');

		/* Contact */
		if ($request->input('firstname'))
			$user->firstname = $request->input('firstname');
		else
			$user->firstname = $user->username;
		if ($request->input('lastname'))
			$user->lastname = $request->input('lastname');
		if ($request->input('email')) {
			if ($user->email != $request->get('email')) {
				$email = strtolower(trim($request->input('email')));

				if (User::where('email',$email)->count()>0) {
					$errors = new MessageBag(['status' => ['Email wordt al gebruikt']]);
					return back()->withErrors($errors);
				}

				$user->email = $email;
			}
		}
		if ($request->input('mobile'))
			$user->mobile = $request->input('mobile');
		if ($request->input('telephone'))
			$user->phone = $request->input('telephone');
		if ($request->input('website'))
			$user->website = $request->input('website');

		/* Overig */
		if ($request->input('expdate'))
			$user->expiration_date = $request->input('expdate');
		if ($request->input('note'))
			$user->note = $request->input('note');
		if ($request->input('notepad'))
			$user->notepad = $request->input('notepad');
		if ($request->input('confirmdate'))
			$user->confirmed_mail = $request->input('confirmdate');
		if ($request->input('bandate'))
			$user->banned = $request->input('bandate');
		else
			$user->banned = null;
		if ($request->input('toggle-active'))
			$user->active = true;
		else
			$user->active = false;
		if ($request->input('toggle-api'))
			$user->api_access = true;
		else
			$user->api_access = false;
		if (!$request->input('gender') || $request->input('gender') == '-1')
			$user->gender = NULL;
		else
			$user->gender = $request->input('gender');

		$user->save();

		$log = new Audit;
		$log->ip = \Calctool::remoteAddr();
		$log->event = '[ADMIN] [UPDATE_PROFILE] [SUCCESS] ' . Auth::user()->username;
		$log->user_id = $user->id;
		$log->save();

		return back()->with('success', 'Gegevens gebruiker aangepast');
	}

	public function getSwitchSession(Request $request, $user_id)
	{
		if (!Auth::user()->isAdmin())
			return back();

		$cookie = cookie('swpsess', Auth::id(), 180);

		Auth::loginUsingId($user_id);

		return redirect('/')->withCookie($cookie);

	}

	public function getSwitchSessionBack(Request $request)
	{
		$swap_session = $request->cookie('swpsess');
		if (!$swap_session)
			return back();

		$user = User::find($swap_session);
		if (!$user->isAdmin())
			return back();

		Auth::loginUsingId($user->id);

		return redirect('/')->withCookie(cookie()->forget('swpsess'));

	}

	public function doDeleteResource(Request $request)
	{
		$this->validate($request, [
			'id' => array('required'),
		]);

		$resource = Resource::find($request->input('id'));
		$resource->unlinked = true;

		unlink($resource->file_location);

		$resource->save();

		return json_encode(['success' => 1]);
	}

	public function doOfferPostDone(Request $request)
	{
		$this->validate($request, [
			'id' => array('required'),
		]);

		$post = OfferPost::find($request->input('id'));
		$post->sent_date = date('d-m-Y');

		$post->save();

		return json_encode(['success' => 1]);
	}

	public function doSendNotification(Request $request)
	{
		$this->validate($request, [
			'user' => array('required'),
			'subject' => array('required'),
			'message' => array('required'),
		]);

		if ($request->input('user') == -1)
			return back();

		$message = new MessageBox;
		$message->subject = $request->input('subject');
		$message->message = nl2br($request->input('message'));
		$message->from_user = Auth::id();
		$message->user_id =	$request->input('user');

		$message->save();

		return back()->with('success', 'Bericht verstuurd');
	}

	public function doTruncateLog()
	{
		if (!Auth::user()->isAdmin())
			return back();

		file_put_contents("../storage/logs/laravel.log", "");

		return back()->with('success', 'Getruncate');
	}

	public function getDemoProject(Request $request, $user_id)
	{
		\DemoProjectTemplate::setup($user_id);

		return back()->with('success', 'Demo-project ingevoegd');
	}

	public function getValidationProject(Request $request, $user_id)
	{
		\ValidationProjectTemplate::setup($user_id);

		return back()->with('success', 'Validatiie-project ingevoegd');
	}

	public function getStabuProject(Request $request, $user_id)
	{
		\StabuProjectTemplate::setup($user_id);

		return back()->with('success', 'STABU-project ingevoegd');
	}

	public function getSessionDeblock(Request $request, $user_id)
	{
		$username = User::find($user_id)->username;
		Redis::del('auth:'.$username.':fail', 'auth:'.$username.':block');

		return back()->with('success', 'sessie met succes gedeblokkeerd, de user kan weer inloggen.');
	}
}
