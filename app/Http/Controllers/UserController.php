<?php

namespace CalculatieTool\Http\Controllers;

use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;

use \CalculatieTool\Models\Payment;
use \CalculatieTool\Models\User;
use \CalculatieTool\Models\Project;
use \CalculatieTool\Models\Audit;
use \CalculatieTool\Models\Promotion;
use \CalculatieTool\Models\UserGroup;
use \CalculatieTool\Models\BankAccount;
use \CalculatieTool\Models\Resource;
use \CalculatieTool\Models\CTInvoice;
use \CalculatieTool\Models\Contact;
use \CalculatieTool\Models\Relation;

use \Auth;
use \Redis;
use \Hash;
use \Mail;
use \DB;
use \PDF;

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
		Mail::send('mail.deactivate', $data, function($message) use ($data) {
			$message->to($data['email'], ucfirst($data['firstname']) . ' ' . ucfirst($data['lastname']));
			$message->subject('CalculatieTool.com - Account gedeactiveerd');
			$message->from('info@calculatietool.com', 'CalculatieTool.com');
			$message->replyTo('support@calculatietool.com', 'CalculatieTool.com');
		});

		Audit::CreateEvent('account.deactivate.success', 'Account deactivated by user', $user->id);

		if (!config('app.debug')) {
			$data = array(
				'email' => $user->email,
				'firstname' => $user->firstname,
				'lastname' => $user->lastname,
				'reason' => $request->get('reason'),
			);
			Mail::send('mail.inform_deactivate_user', $data, function($message) use ($data) {
				$message->to('administratie@calculatietool.com', 'CalculatieTool.com');
				$message->subject('CalculatieTool.com - Account deactivatie');
				$message->from('info@calculatietool.com', 'CalculatieTool.com');
				$message->replyTo('administratie@calculatietool.com', 'CalculatieTool.com');
			});
		}

		return redirect('/login');
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
			Mail::send('mail.password_update', $data, function($message) use ($data) {
				$message->to($data['email'], ucfirst($data['firstname']) . ' ' . ucfirst($data['lastname']));
				$message->subject('CalculatieTool.com - Wachtwoord aangepast');
				$message->from('info@calculatietool.com', 'CalculatieTool.com');
				$message->replyTo('support@calculatietool.com', 'CalculatieTool.com');
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
			'iban' => array('alpha_num','max:25'),
			'iban_name' => array('required','max:50')
		]);

		$relation = \CalculatieTool\Models\Relation::find($request->input('id'));
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
		Mail::send('mail.iban_update', $data, function($message) use ($data) {
			$message->to($data['email'], ucfirst($data['firstname']) . ' ' . ucfirst($data['lastname']));
			$message->subject('CalculatieTool.com - Betaalgegevens aangepast');
			$message->from('info@calculatietool.com', 'CalculatieTool.com');
			$message->replyTo('support@calculatietool.com', 'CalculatieTool.com');
		});

		Audit::CreateEvent('account.iban.update.success', 'IBAN and/or account name updated');

		return back()->with('success', 'Betalingsgegevens zijn aangepast');
	}

	public function doUpdatePreferences(Request $request)
	{
		$this->validate($request, [
			'pref_hourrate_calc' => array('regex:/^\$?([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(.[0-9][0-9])?$/'),
			'pref_hourrate_more' => array('regex:/^\$?([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(.[0-9][0-9])?$/'),
			'offernumber_prefix' => array('max:10'),
			'invoicenumber_prefix' => array('max:10'),
			'pref_profit_calc_contr_mat' => array('numeric','between:0,200'),
			'pref_profit_more_contr_mat' => array('numeric','between:0,200'),
			'pref_profit_calc_contr_equip' => array('numeric','between:0,200'),
			'pref_profit_more_contr_equip' => array('numeric','between:0,200'),
			'pref_profit_calc_subcontr_mat' => array('numeric','between:0,200'),
			'pref_profit_more_subcontr_mat' => array('numeric','between:0,200'),
			'pref_profit_calc_subcontr_equip' => array('numeric','between:0,200'),
			'pref_profit_more_subcontr_equip' => array('numeric','between:0,200'),
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
			$user->pref_email_offer = htmlspecialchars($request->get('pref_email_offer'));
		if ($request->get('pref_offer_description'))
			$user->pref_offer_description = htmlspecialchars($request->get('pref_offer_description'));
		if ($request->get('pref_extracondition_offer'))
			$user->pref_extracondition_offer = htmlspecialchars($request->get('pref_extracondition_offer'));
		if ($request->get('pref_closure_offer'))
			$user->pref_closure_offer = htmlspecialchars($request->get('pref_closure_offer'));
		
		if ($request->get('pref_email_invoice'))
			$user->pref_email_invoice = htmlspecialchars($request->get('pref_email_invoice'));
		if ($request->get('pref_invoice_description'))
			$user->pref_invoice_description = htmlspecialchars($request->get('pref_invoice_description'));
		if ($request->get('pref_invoice_closure'))
			$user->pref_invoice_closure = htmlspecialchars($request->get('pref_invoice_closure'));
		
		if ($request->get('pref_email_invoice_first_reminder'))
			$user->pref_email_invoice_first_reminder = htmlspecialchars($request->get('pref_email_invoice_first_reminder'));
		if ($request->get('pref_email_invoice_last_reminder'))
			$user->pref_email_invoice_last_reminder = htmlspecialchars($request->get('pref_email_invoice_last_reminder'));
		if ($request->get('pref_email_invoice_first_demand'))
			$user->pref_email_invoice_first_demand = htmlspecialchars($request->get('pref_email_invoice_first_demand'));
		if ($request->get('pref_email_invoice_last_demand'))
			$user->pref_email_invoice_last_demand = htmlspecialchars($request->get('pref_email_invoice_last_demand'));
		
		if ($request->get('offernumber_prefix') != "")
			$user->offernumber_prefix = $request->get('offernumber_prefix');
		if ($request->get('invoicenumber_prefix') != "")
			$user->invoicenumber_prefix = $request->get('invoicenumber_prefix');
		if ($request->get('administration_cost') != "")
			$user->administration_cost = str_replace(',', '.', str_replace('.', '' , $request->get('administration_cost')));
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

		$project = Project::where('user_id', Auth::id())->orderBy('created_at', 'desc')->first();
		if (!$project)
			return back();

		return redirect('/project-' . $project->id . '/edit');
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
