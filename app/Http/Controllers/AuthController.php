<?php

namespace Calctool\Http\Controllers;

use Illuminate\Http\Request;
use Longman\TelegramBot\Telegram as TTelegram;
use Longman\TelegramBot\Request as TRequest;
use Illuminate\Validation\ValidationException;

use \Calctool\Models\User;
use \Calctool\Models\Project;
use \Calctool\Models\UserType;
use \Calctool\Models\Audit;
use \Calctool\Models\Telegram;
use \Calctool\Models\MessageBox;
use \Calctool\Models\Relation;
use \Calctool\Models\RelationType;
use \Calctool\Models\RelationKind;
use \Calctool\Models\Contact;
use \Calctool\Models\ContactFunction;

use \Auth;
use \Redis;
use \Cache;
use \Hash;
use \Mailgun;
use \Authorizer;
use \DB;

class AuthController extends Controller {

	private function getCacheBlockItem()
	{
		if (isset($_SERVER['REMOTE_ADDR']))
			return 'blockremote' . base64_encode($_SERVER['REMOTE_ADDR']);

		return 'blockremotelocal';
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Route
	 */
	public function getLogin()
	{
		if (Cache::has($this->getCacheBlockItem())) {
			if (Cache::get($this->getCacheBlockItem()) >=5) {
				return view('auth.login')->withErrors(['auth' => ['Toegang geblokkeerd voor 15 minuten. Probeer later opnieuw.']]);
			}
		}

		return view('auth.login');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Route
	 */
	public function getRegister(Request $request)
	{
		if ($request->has('client_referer')) {
			return view('auth.registration', ['client_referer' => $request->get('client_referer')]);
		}

		return view('auth.registration');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Route
	 */
	public function getPasswordReset()
	{
		return view('auth.password');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Route
	 */
	public function doLogin(Request $request)
	{
		$username = strtolower(trim($request->input('username')));
		$userdata = array(
			'username' 	=> $username,
			'password' 	=> $request->input('secret'),
			'active' 	=> 1,
			'banned' 	=> NULL
		);

		$userdata2 = array(
			'email' 	=> $username,
			'password' 	=> $request->input('secret'),
			'active' 	=> 1,
			'banned' 	=> NULL
		);

		$remember = $request->input('rememberme') ? true : false;

		if (Cache::has($this->getCacheBlockItem())) {
			if (Cache::get($this->getCacheBlockItem()) >=5) {
				return back()->withErrors(['auth' => ['Toegang geblokkeerd voor 15 minuten. Probeer later opnieuw.']]);
			}
		}

		if (Auth::attempt($userdata, $remember) || Auth::attempt($userdata2, $remember)) {

			/* Email must be confirmed */
			if (!Auth::user()->confirmed_mail) {
				Auth::logout();
				return back()->withErrors(['mail' => ['Email nog niet bevestigd']])->withInput($request->except('secret'));
			}

			Audit::CreateEvent('auth.login.succces', 'Login with: ' . \Calctool::remoteAgent());

			if ($request->has('redirect')) {
				return redirect(urldecode($request->get('redirect')));
			}

			if (Auth::user()->isSystem()) {
				return redirect('/admin');
			}

			/* Redirect to dashboard*/
			return redirect('/');
		} else {

			if (Cache::has($this->getCacheBlockItem())) {
				Cache::increment($this->getCacheBlockItem());
			} else {
				Cache::put($this->getCacheBlockItem(), 1, 15);
			}
	
			return back()->withErrors(['password' => ['Gebruikersnaam en/of wachtwoord verkeerd']])->withInput($request->except('secret'))->withCookie(cookie()->forget('swpsess'));
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Route
	 */
	public function doRegister(Request $request)
	{
		$request->merge(array('username' => strtolower(trim($request->input('username')))));
		$request->merge(array('email' => strtolower(trim($request->input('email')))));
		
		$referral_user = null;
		$expiration_date = date('Y-m-d', strtotime("+1 month", time()));
		if ($request->has('client_referer')) {
			$referral_user = User::where('referral_key', $request->get('client_referer'))->first();
			if ($referral_user) {
				$expiration_date = date('Y-m-d', strtotime("+3 month", time()));
			}
		}

		$this->validate($request, [
			'username' => array('required','max:30','unique:user_account'),
			'email' => array('required','max:80','email','unique:user_account'),
			'secret' => array('required','confirmed','min:5'),
			'secret_confirmation' => array('required','min:5'),
			'contact_name' => array('required','max:50'),
			'contact_firstname' => array('max:30'),
			'company_name' => array('required','max:50'),
		]);

		$user = new User;
		$user->username = $request->get('username');
		$user->secret = Hash::make($request->get('secret'));
		$user->firstname = $user->username;
		$user->api = md5(mt_rand());
		$user->token = sha1($user->secret);
		$user->referral_key = md5(mt_rand());
		$user->ip = \Calctool::remoteAddr();
		$user->email = $request->get('email');
		$user->expiration_date = $expiration_date;
		$user->user_type = UserType::where('user_type','=','user')->first()->id;
		$user->user_group = 100;
		$user->firstname = $request->get('contact_firstname');
		$user->lastname = $request->get('contact_name');

		$user->save();

		/* General relation */
		$relation = new Relation;
		$relation->user_id = $user->id;
		$relation->debtor_code = mt_rand(1000000, 9999999);

		/* My company */
		$relation->kind_id = RelationKind::where('kind_name','zakelijk')->first()->id;
		$relation->company_name = $request->input('company_name');
		$relation->type_id = RelationType::where('type_name', 'aannemer')->first()->id;
		$relation->email = $user->email;

		$relation->save();

		$user->self_id = $relation->id;
		$user->save();

		/* Contact */
		$contact = new Contact;
		$contact->firstname = $request->input('contact_firstname');
		$contact->lastname = $request->input('contact_name');
		$contact->email = $user->email;
		$contact->relation_id = $relation->id;
		$contact->function_id = ContactFunction::where('function_name','eigenaar')->first()->id;

		$contact->save();

		$data = array('email' => $user->email, 'api' => $user->api, 'token' => $user->token, 'firstname' => $user->firstname, 'lastname' => $user->lastname);
		Mailgun::send('mail.confirm', $data, function($message) use ($data) {
			$message->to($data['email'], ucfirst($data['firstname']) . ' ' . ucfirst($data['lastname']));
			$message->subject('CalculatieTool.com - Account activatie');
			if (!config('app.debug')) {
				$message->bcc('info@calculatietool.com', 'CalculatieTool.com');
			}
			$message->from('info@calculatietool.com', 'CalculatieTool.com');
			$message->replyTo('info@calculatietool.com', 'CalculatieTool.com');
		});

		$user->save();

		Audit::CreateEvent('account.new.success', 'Created new account from template', $user->id);

		if ($referral_user) {
			$referral_user->expiration_date = $expiration_date;

			$referral_user->save();

			Audit::CreateEvent('account.referralkey.used.success', 'Referral key used', $referral_user->id);
		}

		return back()->with('success', 'Account aangemaakt, er is een bevestingsmail verstuurd');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Route
	 */
	public function doNewPassword(Request $request, $api, $token)
	{
		$this->validate($request, [
			'secret' => array('required','confirmed','min:5'),
			'secret_confirmation' => array('required','min:5'),
		]);

		$user = User::where('token','=',$token)->where('api','=',$api)->first();
		if (!$user) {
			$errors = new MessageBag(['activate' => ['Activatielink is niet geldig']]);
			return redirect('login')->withErrors($errors);
		}
		$user->secret = Hash::make($request->get('secret'));
		$user->active = true;
		$user->token = sha1($user->secret);
		$user->save();

		Audit::CreateEvent('auth.update.password.success', 'Updated with: ' . \Calctool::remoteAgent(), $user->id);

		Auth::login($user);
		return redirect('/');
	}

	private function informAdmin(User $newuser)
	{
		if (env('TELEGRAM_ENABLED') && env('TELEGRAM_ENABLED') == "true") {
			$telegram = new TTelegram(env('TELEGRAM_API'), env('TELEGRAM_NAME'));
			TRequest::initialize($telegram);

			foreach (User::where('user_type','=',UserType::where('user_type','=','admin')->first()->id)->get() as $admin) {
				$tgram = Telegram::where('user_id','=',$admin->id)->first();
				if ($tgram && $tgram->alert) {

					$text  = "Nieuwe gebruiker aangemeld\n";
					$text .= "Gebruikersnaam: " . $newuser->username . "\n";
					$text .= "Email: " . $newuser->email . "\n";

					$data = array();
					$data['chat_id'] = $tgram->uid;
					$data['text'] = $text;

					$result = TRequest::sendMessage($data);
				}
			}
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Route
	 */
	public function doActivate(Request $request, $api, $token)
	{
		$user = User::where('token','=',$token)->where('api','=',$api)->first();
		if (!$user) {
			$errors = new MessageBag(['activate' => ['Activatielink is niet geldig']]);
			return redirect('login')->withErrors($errors);
		}
		if ($user->confirmed_mail) {
			$errors = new MessageBag(['activate' => ['Account is al geactiveerd']]);
			return redirect('login')->withErrors($errors);
		}
		$user->confirmed_mail = date('Y-m-d H:i:s');
		$user->save();

		\VoorbeeldRelatieTemplate::setup($user->id);

		$this->informAdmin($user);

		$message = new MessageBox;
		$message->subject = 'Welkom ' . $user->username;
		$message->message = 'Beste ' . $user->username . ',<br /><br />Welkom bij de CalculatieTool.com,<br /><br />

		Je account is aangemaakt met alles dat de CalculatieTool.com te bieden heeft. Geen verborgen of afgeschermde delen en alles is beschikbaar. Hieronder valt ook onze GRATIS service jouw offertes en facturen door ons te laten versturen met de post. Jouw persoonlijke account is dus klaar voor gebruik, succes met het Calculeren, Offreren, Registreren, Facturen en Administreren met deze al-in-one-tool!
			<br>
			<br>
		Graag willen we nog even een paar dingen uitleggen voordat je aan de slag gaat. Het programma is iets anders van opzet dan de doorsnee calculatie apps. Het grootste verschil zit hem in de module calculeren. Deze is opgezet volgens het <i>biervilt principe</i>. Dit houdt in dat je geen normeringen kunt toe passen. 
			<br>
			<br>
		Je geeft gewoon letterlijk op wat je nodig hebt voor de arbeid, het materiaal en het materieel. Wij richten ons echt op de ZZP markt en daar kwam deze wens van calculeren uit naar voren. Het programma is heel duidelijk van opzet en je hebt het echt zo onder knie, desalniettemin kunnen er altijd vagen zijn. Stel deze gerust, dan proberen wij deze zo snel mogelijk te beantwoorden.
			<br>
			<br>
		Probeer het programma echt even te doorgronden. Er wordt je echt een hoop werk uit handen genomen als je het onder de knie hebt en je zal er net als andere gebruikers een hoop plezier aan beleven.
			<br>
			<br>
		Er zijn diverse filmpjes die de modules duidelijk maken. 
			<br>
			<br>
		De prijs die wij na de 30 dagen proefperiode hanteren is blijvend laag en je kan altijd  alle functionaliteiten gebruiken die het programma biedt.
			<br>
			<br>
			<br />Wanneer de Quickstart pop-up of de pagina <a href="/mycompany">mijn bedrijf</a> wordt ingevuld kan je direct aan de slag met je eerste project.<br /><br />Groet, Maikel van de CalculatieTool.com';

		$message->from_user = User::where('username', 'admin')->first()['id'];
		if (empty($message->from_user)) {
			$message->from_user = User::first()['id'];
		}
		$message->user_id =	$user->id;

		$message->save();

		$data = array('email' => $user->email, 'firstname' => $user->firstname, 'lastname' => $user->lastname);
		Mailgun::send('mail.letushelp', $data, function($message) use ($data) {
			$message->to($data['email'], ucfirst($data['firstname']) . ' ' . ucfirst($data['lastname']));
			$message->subject('CalculatieTool.com - Bedankt');
			$message->from('info@calculatietool.com', 'CalculatieTool.com');
			$message->replyTo('info@calculatietool.com', 'CalculatieTool.com');
		});

		Audit::CreateEvent('auth.activate.success', 'Activated with: ' . \Calctool::remoteAgent(), $user->id);

		Auth::login($user);

		return redirect('/?nstep=intro')->withCookie(cookie('_xintr'.$user->id, '1', 60*24*7));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Route
	 */
	public function doBlockPassword(Request $request)
	{
		$this->validate($request, [
			'email' => array('required','max:80','email')
		]);

		$user = User::where('email','=',$request->get('email'))->first();
		if (!$user)
			return redirect('login')->with('success', 1);
		$user->secret = Hash::make(mt_rand());
		$user->active = false;
		$user->api = md5(mt_rand());

		$data = array('email' => $user->email, 'api' => $user->api, 'token' => $user->token, 'firstname' => $user->firstname, 'lastname' => $user->lastname);
		Mailgun::send('mail.password', $data, function($message) use ($data) {
			$message->to($data['email'], ucfirst($data['firstname']) . ' ' . ucfirst($data['lastname']));
			$message->subject('CalculatieTool.com - Wachtwoord herstellen');
			$message->from('info@calculatietool.com', 'CalculatieTool.com');
			$message->replyTo('info@calculatietool.com', 'CalculatieTool.com');
		});

		$user->save();

		Audit::CreateEvent('auth.reset.password.mail.success', 'Reset with: ' . \Calctool::remoteAgent(), $user->id);

		return redirect('login')->with('success', 'Wachtwoord geblokkeerd');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Route
	 */
	public function doLogout()
	{
		Audit::CreateEvent('auth.logout.success', 'User destroyed current session');
		Auth::logout();
		return redirect('/login');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Route
	 */
	public function doIssueAccessToken(Request $request)
	{
		$client_id = $request->get('client_id');
		$grant_type = $request->get('grant_type');

		$grants = DB::table('oauth_clients')
							->where('id', $client_id)
							->select('grant_authorization_code', 'grant_implicit', 'grant_password', 'grant_client_credential')
							->first();

		switch ($grant_type) {
			case 'authorization_code':
				if (!$grants->grant_authorization_code) {
					return response()->json(['error' => 'invalid_request', 'error_description' => 'The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the "grant_type" parameter.'], 400); 
				}
				break;
			case 'implicit':
				if (!$grants->grant_implicit) {
					return response()->json(['error' => 'invalid_request', 'error_description' => 'The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the "grant_type" parameter.'], 400); 
				}
				break;
			case 'password':
				if (!$grants->grant_password) {
					return response()->json(['error' => 'invalid_request', 'error_description' => 'The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the "grant_type" parameter.'], 400); 
				}
				break;
			case 'client_credentials':
				if (!$grants->grant_client_credential) {
					return response()->json(['error' => 'invalid_request', 'error_description' => 'The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the "grant_type" parameter.'], 400); 
				}
				break;
			
			default:
				break;
		}

		return response()->json(Authorizer::issueAccessToken());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Route
	 */
	public function getOauth2Authorize() {
		$authParams = Authorizer::getAuthCodeRequestParams();
		$formParams = array_except($authParams,'client');

		$isAuthenticatedBefore = DB::table('oauth_sessions')
									->where('client_id', $authParams['client']->getId())
									->where('owner_id', Auth::id())
									->select('id')
									->count();

		if ($isAuthenticatedBefore > 0) {
			DB::table('oauth_sessions')
							->where('client_id', $authParams['client']->getId())
							->where('owner_id', Auth::id())
							->delete();

			$redirectUri = Authorizer::issueAuthCode('user', Auth::id(), $authParams);

			Audit::CreateEvent('oauth2.reauthorize.success', 'OUATH2 request reauthorized for ' . $authParams['client']->getName());

			return redirect($redirectUri);
		}

		$formParams['client_id'] = $authParams['client']->getId();

		$formParams['scope'] = implode(config('oauth2.scope_delimiter'), array_map(function($scope) {
		   return $scope->getId();
		}, $authParams['scopes']));

		return view('auth.authorization', ['params' => $formParams, 'client' => $authParams['client']]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Route
	 */
	public function doOauth2Authorize(Request $request) {
	    $authParams = Authorizer::getAuthCodeRequestParams();
	    $redirectUri = '/';

	    // If the user has allowed the client to access its data, redirect back to the client with an auth code.
	    if ($request->has('approve')) {
	        $redirectUri = Authorizer::issueAuthCode('user', Auth::id(), $authParams);

	        Audit::CreateEvent('oauth2.authorize.success', 'OUATH2 request approved ' . $authParams['client']->getName());
	    }

	    // If the user has denied the client to access its data, redirect back to the client with an error message.
	    if ($request->has('deny')) {
	        $redirectUri = Authorizer::authCodeRequestDeniedRedirectUri();

	        Audit::CreateEvent('oauth2.authorize.success', 'OUATH2 request denied ' . $authParams['client']->getName());
	    }

	    return redirect($redirectUri);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Route
	 */
	public function getRestUser(Request $request) {
		$id = Authorizer::getResourceOwnerId();

		if (Authorizer::getResourceOwnerType() != "user") {

			return response()->json(['error' => 'access_denied', 'error_description' => 'The resource owner or authorization server denied the request.'], 401); 
		}

		$user = User::find($id);
		$user['isadmin'] = $user->isAdmin();
		$user['issuperuser'] = $user->isSuperUser();
    	return response()->json($user);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Route
	 */
	public function getRestUserProjects(Request $request) {
		$id = Authorizer::getResourceOwnerId();

		if (Authorizer::getResourceOwnerType() != "user") {
			return response()->json(['error' => 'access_denied', 'error_description' => 'The resource owner or authorization server denied the request.'], 401); 
		}

		$user = User::find($id);
		$projects = Project::where('user_id',$user->id)->get();
    	return response()->json($projects);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Route
	 */
	public function getRestUserRelations(Request $request) {
		$id = Authorizer::getResourceOwnerId();

		if (Authorizer::getResourceOwnerType() != "user") {
			return response()->json(['error' => 'access_denied', 'error_description' => 'The resource owner or authorization server denied the request.'], 401); 
		}

		$user = User::find($id);
		$relations = Relation::where('user_id',$user->id)->get();
    	return response()->json($relations);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Route
	 */
	public function getRestAllUsers(Request $request) {
		if (Authorizer::getResourceOwnerType() != "client") {
			return response()->json(['error' => 'access_denied', 'error_description' => 'The resource owner or authorization server denied the request.'], 401); 
		}

    	return response()->json(User::all());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Route
	 */
	public function getRestAllProjects(Request $request) {
		if (Authorizer::getResourceOwnerType() != "client") {
			return response()->json(['error' => 'access_denied', 'error_description' => 'The resource owner or authorization server denied the request.'], 401); 
		}

    	return response()->json(Project::all());
	}
}
