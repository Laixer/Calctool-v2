<?php

namespace Calctool\Http\Controllers;

use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;

use \Calctool\Events\UserNotification;
use \Calctool\Models\SysMessage;
use \Calctool\Models\Payment;
use \Calctool\Models\User;
use \Calctool\Models\UserType;
use \Calctool\Models\UserTag;
use \Calctool\Models\UserGroup;
use \Calctool\Models\OfferPost;
use \Calctool\Models\Offer;
use \Calctool\Models\Invoice;
use \Calctool\Models\InvoicePost;
use \Calctool\Models\Resource;
use \Calctool\Models\MessageBox;
use \Calctool\Models\Product;
use \Calctool\Models\Audit;
use \Calctool\Models\Wholesale;
use \Calctool\Models\Supplier;
use \Calctool\Models\Project;
use \Calctool\Models\Promotion;
use \Calctool\Models\AdminLog;
use \Calctool\Models\ProductGroup;
use \Calctool\Models\ProductCategory;
use \Calctool\Models\ProductSubCategory;
use \Database\Templates\DemoProjectTemplate;
use \Database\Templates\ValidationProjectTemplate;

use \Storage;
use \Auth;
use \Cache;
use \Hash;
use \Redis;
use \Markdown;
use \Mailgun;
use \Artisan;

ini_set('max_execution_time', 0);
ini_set('max_input_time', -1);

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

	public function getDashboard(Request $request)
	{
		if ($request->get('actas') == 'system') {
			Auth::user()->user_type = UserType::where('user_type','system')->first()->id;
			Auth::user()->save();
			return redirect('/admin');
		}

		if ($request->get('actas') == 'admin') {
			Auth::user()->user_type = UserType::where('user_type','admin')->first()->id;
			Auth::user()->save();
			return redirect('/admin');
		}

		return view('admin.dashboard');
	}

	public function getDocumentation(Request $request, $directory = null, $page = null)
	{
		$docdir = "../docs/";

		if (!$directory)
			$directory = "";

		if (!$page)
			$page = "index";

		$dirpage = "/" . $page . ".md";

		if (!file_exists(config('filesystems.docs') . "/" . $directory . $dirpage))
			return redirect('/admin/documentation')->withErrors('Deze pagina bestaat (nog) niet');

		$mdpage = file_get_contents(config('filesystems.docs') . "/" . $directory . $dirpage);
		if (!$mdpage)
			return redirect('/admin/documentation');

		$content = Markdown::convertToHtml($mdpage);

		return view('admin.documentation',['content' => $content, 'dir' => $directory, 'page' => $page]);

	}

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

		return response()->json(['success' => 1]);

	}

	public function doRefund(Request $request, $transcode)
	{
		$this->validate($request, [
			'amount' => array('required'),
		]);

		$subtract = $request->input('amount');

		$mollie = new \Mollie_API_Client;
		$mollie->setApiKey(config('services.mollie.key'));

		$payment = $mollie->payments->get($transcode);

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

			$data = array('email' => $user->email, 'amount' => number_format($order->amount, 2,",","."), 'firstname' => $user->firstname, 'lastname' => $user->lastname);
			Mailgun::send('mail.refund', $data, function($message) use ($data) {
				$message->to($data['email'], ucfirst($data['firstname']) . ' ' . ucfirst($data['lastname']));
				$message->subject('CalculatieTool.com - Terugstorting');
				$message->from('info@calculatietool.com', 'CalculatieTool.com');
				$message->replyTo('administratie@calculatietool.com', 'CalculatieTool.com');
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
			'group' => array('required'),

			/* Contact */
			'lastname' => array('max:50'),
			'firstname' => array('max:30'),
			'mobile' => array('numeric'),
			'telephone' => array('numeric'),
			'email' => array('required','email','max:80','unique:user_account'),
			'website' => array('url','max:180'),

			/* Adress */
			'address_street' => array('alpha_num','max:60'),
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
		$user->user_group = $request->input('group');		

		/* Server */
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

		Audit::CreateEvent('admin.user.new.succces', 'Created user: ' . $user->username);
		Audit::CreateEvent('admin.user.new.succces', 'Created user: ' . $user->username, $user->id);

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
		if (!$user->isAdmin()) {
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
		}
		if (!$user->isAdmin()) {
			if ($request->input('secret'))
				$user->secret = Hash::make($request->input('secret'));
		}
		if ($request->input('type'))
			$user->user_type = $request->input('type');
		if ($request->input('group'))
			$user->user_group = $request->input('group');
		if ($request->input('tag')) {
			if ( $request->input('tag') == '-1' )
				$user->user_tag_id = NULL;
			else
				$user->user_tag_id = $request->input('tag');
		}

		/* Contact */
		if ($request->input('firstname'))
			$user->firstname = $request->input('firstname');
		else
			$user->firstname = $user->username;
		if ($request->input('lastname'))
			$user->lastname = $request->input('lastname');
		if (!$user->isAdmin()) {
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
			$user->gender = null;
		else
			$user->gender = $request->input('gender');

		$user->save();

		Audit::CreateEvent('admin.user.update.succces', 'Updated user: ' . $user->username);
		Audit::CreateEvent('admin.user.update.succces', 'Updated user: ' . $user->username, $user->id);

		return back()->with('success', 'Gegevens gebruiker aangepast');
	}

	public function doNewAdminLog(Request $request, $user_id)
	{
		$this->validate($request, [
			'date' => array('required'),
			'note' => array('required'),
			'label' => array('required', 'max:100'),
		]);

		$log = new AdminLog;
		$log->note = $request->get('note');
		$log->created_at = date('Y-m-d', strtotime($request->get('date')));
		$log->label_id = $request->get('label');
		$log->user_id = $user_id;
		
		$log->save();

		return back()->with('success', 'Item toegevoegd');

	}

	public function getSwitchSession(Request $request, $user_id)
	{
		if (!Auth::user()->isAdmin())
			return back();

		if (session()->has('swap_session'))
			return back();

		if (Cache::has('keepsesionstate'))
			return back();

		$swapinfo = [
			'user_id' => $user_id,
			'admin_id' => Auth::id(),
		];

		Audit::CreateEvent('auth.swap.session.succces', 'Session takeover by: ' . auth::user()->username, $user_id);

		Cache::put('keepsesionstate', $user_id, 1);

		Auth::loginUsingId($user_id);

		session()->put('swap_session', $swapinfo);

		return redirect('/');
	}

	public function getLoginAsUser(Request $request, $user_id)
	{
		if (!Auth::user()->isSystem())
			return back();

		Auth::loginUsingId($user_id);

		return redirect('/');
	}

	public function getSwitchSessionBack(Request $request)
	{
        if (!session()->has('swap_session'))
			return back();

		$swapinfo = session()->get('swap_session');

		$user = User::find($swapinfo['admin_id']);
		if (!$user->isAdmin())
			return back();

		Auth::loginUsingId($user->id);

		session()->forget('swap_session');

		return redirect('/admin/user');
	}

	public function doNewGroup(Request $request)
	{
		$request->merge(array('name' => strtolower(trim($request->input('name')))));

		$this->validate($request, [
			'name' => array('required','unique:user_group'),
			'subscription_amount' => array('required','min:1'),
		]);

		/* General */
		$group = new UserGroup;
		$group->name = strtolower($request->input('name'));
		$group->subscription_amount = floatval($request->input('subscription_amount'));

		if ($request->input('note'))
			$group->note = $request->input('note');	
		if ($request->input('toggle-active'))
			$group->active = true;
		else
			$group->active = false;
		if ($request->input('toggle-beta'))
			$group->experimental = true;
		else
			$group->experimental = false;

		$group->token = md5(mt_rand());
		$group->save();

		return back()->with('success', 'Groep aangemaakt');
	}

	public function doNewTag(Request $request)
	{
		$this->validate($request, [
			'name' => array('required'),
		]);

		/* General */
		$tag = new UserTag;
		$tag->name = $request->input('name');

		$tag->save();

		return back()->with('success', 'Tag aangemaakt');
	}

	public function doUpdateGroup(Request $request, $group_id)
	{
		$this->validate($request, [
			'name' => array('required'),
		]);

		/* General */
		$group = UserGroup::find($group_id);
		if ($request->input('name')) {
			if ($group->name != $request->get('name')) {
				$name = strtolower(trim($request->input('name')));

				if (UserGroup::where('name',$name)->where('id','!=',$group->id)->count()>0) {
					$errors = new MessageBag(['status' => ['Groepnaam wordt al gebruikt']]);
					return back()->withErrors($errors);
				}

				$group->name = $name;
			}
		}

		$group->subscription_amount = floatval($request->input('subscription_amount'));
		if ($request->input('note'))
			$group->note = $request->input('note');	
		if ($request->input('toggle-active'))
			$group->active = true;
		else
			$group->active = false;
		if ($request->input('toggle-beta'))
			$group->experimental = true;
		else
			$group->experimental = false;

		$group->save();

		return back()->with('success', 'Groep opgeslagen');
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

		return response()->json(['success' => 1]);
	}

	public function doOfferPostDone(Request $request)
	{
		$this->validate($request, [
			'id' => array('required'),
		]);

		$post = OfferPost::find($request->input('id'));
		$post->sent_date = date('Y-m-d H:i:s');

		$post->save();

		$offer = Offer::find($post->offer_id); 
		$project = Project::find($offer->project_id); 

		$message = new MessageBox;
		$message->subject = 'Offerte ' . $project->project_name;
		$message->message = 'De offerte voor ' . $project->project_name . ' is met de post verstuurd door de CalculatieTool.com.';
		$message->from_user = User::where('username', 'admin')->first()['id'];
		$message->user_id =	$project->user_id;

		$message->save();

		event(new UserNotification(User::find($project->user_id), $message->subject, $message->message));

		return response()->json(['success' => 1]);
	}

	public function doInvoicePostDone(Request $request)
	{
		$this->validate($request, [
			'id' => array('required'),
		]);

		$post = InvoicePost::find($request->input('id'));
		$post->sent_date = date('Y-m-d H:i:s');

		$post->save();

		$invoice = Invoice::find($post->invoice_id); 
		$offer = Offer::find($invoice->offer_id); 
		$project = Project::find($offer->project_id); 

		$message = new MessageBox;
		$message->subject = 'Factuur ' . $project->project_name;
		$message->message = 'De factuur voor ' . $project->project_name . ' is met de post verstuurd door de CalculatieTool.com.';
		$message->from_user = User::where('username', 'admin')->first()['id'];
		$message->user_id =	$project->user_id;

		$message->save();

		event(new UserNotification(User::find($project->user_id), $message->subject, $message->message));

		return response()->json(['success' => 1]);
	}

	public function doOfferPostDelete(Request $request)
	{
		$this->validate($request, [
			'id' => array('required'),
		]);

		$post = OfferPost::find($request->input('id'));
		$post->delete();

		return response()->json(['success' => 1]);
	}

	public function doInvoicePostDelete(Request $request)
	{
		$this->validate($request, [
			'id' => array('required'),
		]);

		$post = InvoicePost::find($request->input('id'));
		$post->delete();

		return response()->json(['success' => 1]);
	}

	public function doSendNotification(Request $request)
	{
		$this->validate($request, [
			'subject' => array('required'),
			'message' => array('required'),
		]);

		if ($request->input('user') == -1 && $request->input('group') == -1)
			return back();

		$users = [];
		if ($request->input('user') != -1) {
			array_push($users, $request->input('user'));
		} else {
			foreach(User::where('user_group', $request->input('group'))->get() as $user) {
				array_push($users, $user->id);
			}
		}

		foreach ($users as $user) {
			$message = new MessageBox;
			$message->subject = $request->input('subject');
			$message->message = nl2br($request->input('message'));
			$message->from_user = Auth::id();
			$message->user_id =	$user;

			$message->save();

			event(new UserNotification(User::find($user), $message->subject, $message->message));
		}

		return back()->with('success', 'Bericht verstuurd');
	}

	public function doUploadCSV(Request $request)
	{
		$this->validate($request, [
			'csvfile' => array('required'),
		]);

		if ($request->hasFile('csvfile')) {
			$file = $request->file('csvfile');

			if ($file->getMimeType() != 'text/csv' && $file->getMimeType() != 'text/plain')
				return back()->withErrors('Geen CSV bestand');

			$row = 0; $i = 0; $j = 0; $skip = 0; $new_group = 0; $new_category = 0; $new_subcategory = 0;
			if (($handle = fopen($file->getRealPath(), "r")) !== FALSE) {
				while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
					if ($row++ == 0)
						continue;
					
					if (count($data)<9) {
						$skip++;
						continue;
					}
					
					$description = strtolower(preg_replace('/[[:^print:]]/', '', $data[0]));
					$unit = $data[1];
					$article_code = $data[2];

					$price = str_replace(',', '.', str_replace('.', '' , trim($data[3])));
					if (!$price)
						$price = 0;
					if (!is_numeric($price))
						$price = 0;

					$total_price = str_replace(',', '.', str_replace('.', '' , trim($data[4])));
					if (!$total_price)
						$total_price = 0;
					if (!is_numeric($total_price))
						$total_price = 0;

					$supplier = $data[5];

					$wholesale = Wholesale::where('company_name', $supplier)->first();
					if (!$wholesale) {
						$skip++;
						continue;
					}

					$mysupplier = Supplier::where('wholesale_id', $wholesale->id)->first();
					if (!$mysupplier) {
						$skip++;
						continue;
					}

					$group = $data[8];

					$mygroup = ProductGroup::where('group_name', $group)->first();
					if (!$mygroup){
						$mygroup = ProductGroup::create(array(
							'group_name' => $group
						));
						$new_group++;
					}

					$cat = $data[7];

					$mycat = ProductCategory::where('category_name', $cat)->first();
					if (!$mycat){
						$mycat = ProductCategory::create(array(
							'category_name' => $cat,
							'group_id' => $mygroup->id
						));
						$new_category++;
					}

					$sub_cat = $data[6];

					$mysubcat = ProductSubCategory::where('sub_category_name', $sub_cat)->first();
					if (!$mysubcat){
						$mysubcat = ProductSubCategory::create(array(
							'sub_category_name' => $sub_cat,
							'category_id' => $mycat->id
						));
						$new_subcategory++;
					}

					$product = Product::where('article_code', $article_code)->limit(1)->first();
					if ($product) {
						$product->description = $description;
						$product->unit = $unit;
						$product->price = $price;
						$product->total_price = $total_price;
						$product->group_id = $mysubcat->id;
						$product->supplier_id = $mysupplier->id;
						$product->save();
						$j++;
					} else {
						Product::create(array(
							'description' => $description,
							'unit' => $unit,
							'article_code' => $article_code,
							'price' => $price,
							'total_price' => $total_price,
							'group_id' => $mysubcat->id,
							'supplier_id' => $mysupplier->id
						));
					}

					$i++;
				}
				fclose($handle);
			}

			return back()->with('success', $i . ' materialen geimporteerd<ul><li>Updates: ' . $j . '</li><li>Overgeslagen: ' . $skip . '</li><li>Nieuwe groepen: ' . $new_group++ . '</li><li>Nieuwe categorien: ' . $new_category . '</li><li>Nieuwe subcategorien: ' . $new_subcategory . '</li></ul>');
		} else {
			// redirect our user back to the form with the errors from the validator
			return back()->withErrors('Geen CSV geupload');
		}
	}

	public function getEmptyList(Request $request)
	{
		$this->validate($request, [
			'supplier' => array('required'),
		]);

		$supplier = Supplier::where('wholesale_id', $request->get('supplier'))->first();
		Product::where('supplier_id', $supplier->id)->delete();

		return back()->with('success', 'Lijst verwijderd');
	}

	public function doNewPromotion(Request $request)
	{
		$this->validate($request, [
			'name' => array('required'),
			'code' => array('required'),
			'amount' => array('required'),
			'valid' => array('required'),
		]);

		$promo = new Promotion;
		$promo->name = $request->input('name');
		$promo->code = strtoupper($request->input('code'));
		$promo->amount = $request->input('amount');
		$promo->valid = date('Y-m-d H:i:s', strtotime($request->input('valid')));

		$promo->save();

		return back()->with('success', 'Actiecode aangemaakt');
	}

	public function doNewApplication(Request $request)
	{
		$this->validate($request, [
			'appid' => array('required','size:40'),
			'secret' => array('required','size:40'),
			'name' => array('required'),
			'endpoint' => array('required','url'),
		]);

		\DB::table('oauth_clients')->insert([
			'id' => $request->input('appid'),
			'secret' => $request->input('secret'),
			'name' => $request->input('name'),
			'active' => $request->input('toggle-active') ? true : false,
            'grant_authorization_code' => $request->input('toggle-grant_authorization_code') ? true : false,
            'grant_implicit' => $request->input('toggle-grant_implicit') ? true : false,
            'grant_password' => $request->input('toggle-grant_password') ? true : false,
            'grant_client_credential' => $request->input('toggle-grant_client_credential') ? true : false,
			'note' => $request->input('note'),
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		]);

		\DB::table('oauth_client_endpoints')->insert([
			'client_id' => $request->input('appid'),
			'redirect_uri' => $request->input('endpoint'),
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		]);

		return back()->with('success', 'Applicatie aangemaakt');
	}

	public function doUpdateApplication(Request $request, $client_id)
	{
		$this->validate($request, [
			'name' => array('required'),
			'endpoint' => array('required','url'),
		]);

		\DB::table('oauth_clients')->where('id', $client_id)->update([
			'name' => $request->input('name'),
			'active' => $request->input('toggle-active') ? true : false,
            'grant_authorization_code' => $request->input('toggle-grant_authorization_code') ? true : false,
            'grant_implicit' => $request->input('toggle-grant_implicit') ? true : false,
            'grant_password' => $request->input('toggle-grant_password') ? true : false,
            'grant_client_credential' => $request->input('toggle-grant_client_credential') ? true : false,
			'note' => $request->input('note'),
			'updated_at' => date('Y-m-d H:i:s'),
		]);

		\DB::table('oauth_client_endpoints')->where('client_id', $client_id)->update([
			'redirect_uri' => $request->input('endpoint'),
			'updated_at' => date('Y-m-d H:i:s'),
		]);

		return back()->with('success', 'Applicatie opgeslagen');
	}

	public function getPasswordResetUser(Request $request, $user_id)
	{
		if (!Auth::user()->isAdmin()) {
			return back();
		}

		if (Auth::id() == $user_id) {
			return back();
		}

		$user = User::find($user_id);
		$user->reset_token = sha1(mt_rand());

		$data = array(
			'email' => $user->email,
			'token' => $user->reset_token,
			'firstname' => $user->firstname,
			'lastname' => $user->lastname
		);
		Mailgun::send('mail.password', $data, function($message) use ($data) {
			$message->to($data['email'], ucfirst($data['firstname']) . ' ' . ucfirst($data['lastname']));
			$message->subject('CalculatieTool.com - Wachtwoord herstellen');
			$message->from('info@calculatietool.com', 'CalculatieTool.com');
			$message->replyTo('support@calculatietool.com', 'CalculatieTool.com');
		});

		$user->save();

		Audit::CreateEvent('admin.auth.reset.password.succces', 'Admin send password reset email', $user->id);

		return back()->with('success', 'Reset email verstuurd');
	}

	public function getPasswordDefault(Request $request, $user_id)
	{
		if (!Auth::user()->isAdmin()) {
			return back();
		}

		if (Auth::id() == $user_id) {
			return back();
		}

		$user = User::find($user_id);
		$user->reset_token = sha1(mt_rand());
		$user->secret = Hash::make('ABC@123');
		$user->save();

		Audit::CreateEvent('admin.auth.reset.password.succces', 'Admin set default password', $user->id);

		return back()->with('success', 'Reset email verstuurd');
	}

	public function getPurgeUser(Request $request, $user_id)
	{
		if (!Auth::user()->isSystem()) {
			return back();
		}

		if (Auth::id() == $user_id) {
			return back();
		}

		$user = User::find($user_id);
		$user->delete();

		return redirect('/admin/user')->with('success', 'Gebruiker uit de database verwijderd');
	}

	public function doDeleteApplication(Request $request, $client_id)
	{
		\DB::table('oauth_clients')->where('id', $client_id)->delete();

		return redirect('/admin/application/')->with('success', 'Applicatie verwijderd');
	}

	public function doDeletePromotion(Request $request, $id)
	{
		$promo = Promotion::find($id);
		$promo->active = false;

		$promo->save();

		return back()->with('success', 'Actiecode verwijderd');
	}

	public function doTruncateLog()
	{
		if (!Auth::user()->isAdmin())
			return back();

		file_put_contents("../storage/logs/laravel.log", "");

		return back()->with('success', 'Getruncate');
	}

	public function getValidationProject(Request $request, $user_id)
	{
		\ValidationProjectTemplate::setup($user_id);

		return back()->with('success', 'Validatie-project ingevoegd');
	}

	public function getStabuProject(Request $request, $user_id)
	{
		\StabuProjectTemplate::setup($user_id);

		return back()->with('success', 'STABU-project ingevoegd');
	}

	public function getSubscriptionCancel(Request $request, $user_id)
	{
		$mollie = new \Mollie_API_Client;
		$mollie->setApiKey(config('services.mollie.key'));

		$user = User::find($user_id);
		if (!$user)
			return back();

		$subscription_id = $user->payment_subscription_id;
		$subscription = $mollie->customers_subscriptions->withParentId($user->payment_customer_id)->cancel($user->payment_subscription_id);
		$user->payment_subscription_id = NULL;
		$user->save();

		return back()->with('success', 'Automatische incasso gestopt');
	}

	public function doApplicationClearCache(Request $request)
	{
		Artisan::call('clear-compiled');
		Artisan::call('cache:clear');
		Artisan::call('config:clear');
		Artisan::call('oauth:clear');
		Artisan::call('view:clear');
		Artisan::call('route:clear');
		Artisan::call('session:clear');

		return back()->with('success', 'Applicatie caches verwijderd');
	}	
}
