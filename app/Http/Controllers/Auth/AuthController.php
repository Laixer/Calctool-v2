<?php

/**
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the BynqIO\CalculatieTool.com.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 *
 * @package  CalculatieTool
 * @author   Yorick de Wid <y.dewid@calculatietool.com>
 */

namespace BynqIO\CalculatieTool\Http\Controllers\Auth;

use BynqIO\CalculatieTool\Events\UserSignup;
use BynqIO\CalculatieTool\Models\User;
use BynqIO\CalculatieTool\Models\UserGroup;
use BynqIO\CalculatieTool\Models\Project;
use BynqIO\CalculatieTool\Models\UserType;
use BynqIO\CalculatieTool\Models\Audit;
use BynqIO\CalculatieTool\Models\MessageBox;
use BynqIO\CalculatieTool\Models\Relation;
use BynqIO\CalculatieTool\Models\RelationType;
use BynqIO\CalculatieTool\Models\RelationKind;
use BynqIO\CalculatieTool\Models\Contact;
use BynqIO\CalculatieTool\Models\ContactFunction;
use BynqIO\CalculatieTool\Models\Chapter;
use BynqIO\CalculatieTool\Models\Activity;
use BynqIO\CalculatieTool\Models\Offer;
use BynqIO\CalculatieTool\Models\Invoice;
use BynqIO\CalculatieTool\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use Auth;
use Cache;
use Hash;
use Mail;
use Authorizer;
use Validator;
use DB;
use Newsletter;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Auth Controller
    |--------------------------------------------------------------------------
    |
    |
    */

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

        if (!$grants) {
            return response()->json(['error' => 'invalid_request', 'error_description' => 'The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed. Check the "grant_type" parameter.'], 400);
        }

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
    public function getRestVerify(Request $request) {
        if (Authorizer::getResourceOwnerType() != "client") {
            return response()->json(['error' => 'access_denied', 'error_description' => 'The resource owner or authorization server denied the request.'], 401); 
        }

        return response()->json(['success' => 1]);
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

        $users = User::select(
            'id', 'username', 'gender', 'firstname', 'lastname', 'active',
            'banned', 'confirmed_mail', 'registration_date', 'expiration_date',
            'website', 'mobile', 'phone', 'email', 'administration_cost',
            'referral_url', 'self_id',
            'created_at', 'updated_at'
            )->get();

        return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Route
     */
    public function getRestAllRelations(Request $request) {
        if (Authorizer::getResourceOwnerType() != "client") {
            return response()->json(['error' => 'access_denied', 'error_description' => 'The resource owner or authorization server denied the request.'], 401); 
        }

        $relations = Relation::select(
            'relation.id', 'company_name', 'address_street', 'address_number', 'address_postal', 'address_city',
            'phone', 'email', 'website', 'active', 'user_id',
            'province.province_name', 'country.country_name', 'type_name', 'kind_name',
            'created_at', 'updated_at'
            )
            ->join('province', 'relation.province_id', '=', 'province.id')
            ->join('country', 'relation.country_id', '=', 'country.id')
            ->join('relation_type', 'relation.type_id', '=', 'relation_type.id')
            ->join('relation_kind', 'relation.kind_id', '=', 'relation_kind.id')
            // ->join('project_type', 'project.type_id', '=', 'project_type.id')
            ->get();

        return response()->json($relations);
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

        $projects = Project::select(
            'project.id', 'project_name', 'address_street', 'address_number', 'address_postal', 'address_city',
            'tax_reverse', 'use_estimate', 'use_more', 'use_less', 'hour_rate', 'hour_rate_more',
            'profit_calc_contr_mat', 'profit_calc_contr_equip', 'profit_calc_subcontr_mat', 'profit_calc_subcontr_equip',
            'profit_more_contr_mat', 'profit_more_contr_equip', 'profit_more_subcontr_mat', 'profit_more_subcontr_equip',
            'work_execution', 'work_completion', 'start_more', 'update_more', 'start_less', 'update_less',
            'start_estimate', 'update_estimate', 'project_close', 'user_id',
            'province.province_name', 'country.country_name', 'type_name',
            'created_at', 'updated_at'
            )
            ->join('province', 'project.province_id', '=', 'province.id')
            ->join('country', 'project.country_id', '=', 'country.id')
            ->join('project_type', 'project.type_id', '=', 'project_type.id')
            ->get();

        return response()->json($projects);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Route
     */
    public function getRestAllChapters(Request $request) {
        if (Authorizer::getResourceOwnerType() != "client") {
            return response()->json(['error' => 'access_denied', 'error_description' => 'The resource owner or authorization server denied the request.'], 401); 
        }

        $chapters = Chapter::select(
            'id', 'chapter_name', 'more', 'project_id',
            'created_at', 'updated_at'
            )->get();

        return response()->json($chapters);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Route
     */
    public function getRestAllActivities(Request $request) {
        if (Authorizer::getResourceOwnerType() != "client") {
            return response()->json(['error' => 'access_denied', 'error_description' => 'The resource owner or authorization server denied the request.'], 401); 
        }

        $activities = Activity::select(
            'activity.id', 'activity_name', 'chapter_id', 'use_timesheet',
            'tax_labor.tax_rate as tax_labor', 'tax_material.tax_rate as tax_material', 'tax_equipment.tax_rate as tax_equipment',
            'part_name', 'type_name', 'detail_name',
            'created_at', 'updated_at'
            )
            ->join('tax as tax_labor', 'activity.tax_labor_id', '=', 'tax_labor.id') 
            ->join('tax as tax_material', 'activity.tax_material_id', '=', 'tax_material.id') 
            ->join('tax as tax_equipment', 'activity.tax_equipment_id', '=', 'tax_equipment.id') 
            ->join('part', 'activity.part_id', '=', 'part.id') 
            ->join('part_type', 'activity.part_type_id', '=', 'part_type.id') 
            ->join('detail', 'activity.detail_id', '=', 'detail.id') 
            ->get();

        return response()->json($activities);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Route
     */
    public function getRestAllOffers(Request $request) {
        if (Authorizer::getResourceOwnerType() != "client") {
            return response()->json(['error' => 'access_denied', 'error_description' => 'The resource owner or authorization server denied the request.'], 401); 
        }

        $offers = Offer::select(
            'offer.id', 'downpayment', 'downpayment_amount', 'offer_total', 'offer_finish', 'offer_make',
            'offer_code', 'delivertime_name', 'valid_name', 'project_id',
            'created_at', 'updated_at'
            )
            ->join('deliver_time', 'offer.deliver_id', '=', 'deliver_time.id') 
            ->join('valid', 'offer.valid_id', '=', 'valid.id') 
            ->get();

        return response()->json($offers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Route
     */
    public function getRestAllInvoices(Request $request) {
        if (Authorizer::getResourceOwnerType() != "client") {
            return response()->json(['error' => 'access_denied', 'error_description' => 'The resource owner or authorization server denied the request.'], 401); 
        }

        $invoices = Invoice::select(
            'invoice.id', 'invoice_close', 'isclose', 'invoice_code', 'amount', 'payment_condition',
            'bill_date', 'payment_date', 'invoice_make', 'offer_id',
            'created_at', 'updated_at'
            )
            ->get();

        return response()->json($invoices);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Route
     */
    public function doRestUsernameCheck(Request $request) {
        if (Authorizer::getResourceOwnerType() != "client") {
            return response()->json(['error' => 'access_denied', 'error_description' => 'The resource owner or authorization server denied the request.'], 401); 
        }

        $counter = User::where('username',strtolower(trim($request->get('name'))))->count();
        return response()->json(['success' => 1, 'exist' => $counter ? true : false]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Route
     */
    public function doRestNewUser(Request $request) {
        if (Authorizer::getResourceOwnerType() != "client") {
            return response()->json(['error' => 'access_denied', 'error_description' => 'The resource owner or authorization server denied the request.'], 401); 
        }

        $api_client = DB::table('oauth_clients')->where('id', Authorizer::getClientId())->select('name')->first();

        $request->merge(array('username' => strtolower(trim($request->input('account')))));
        $request->merge(array('email' => strtolower(trim($request->input('email')))));
        
        if ($request->has('phone')) {
            $request->merge(array('phone' => substr($request->input('phone'), 0, 12)));
        }
        
        $validator = Validator::make($request->all(), [
            'username' => array('required','max:30','unique:user_account'),
            'email' => array('required','max:80','email','unique:user_account'),
            'password' => array('required','min:5'),
            'last_name' => array('required','max:50'),
            'first_name' => array('max:30'),
            'company' => array('required','max:50'),
            'remote_addr' => array('required'),
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => 0, 'errors' => $validator->errors()->all()], 401);
        }

        $group_id = 100;
        if ($request->has('tags')) {
            foreach ($request->get('tags') as $tag) {
                $group = UserGroup::where('name', $tag)->first();
                if ($group) {
                    $group_id = $group->id;
                    break;
                }
            }
        }

        $user = new User;
        $user->username = $request->get('username');
        $user->secret = Hash::make($request->get('password'));
        $user->firstname = $user->username;
        $user->reset_token = sha1(mt_rand());
        $user->referral_key = md5(mt_rand());
        $user->ip = $request->get('remote_addr');
        $user->email = $request->get('email');
        $user->expiration_date = date('Y-m-d', strtotime("+1 month", time()));
        $user->user_type = UserType::where('user_type','=','user')->first()->id;
        $user->user_group = $group_id;
        $user->firstname = $request->get('first_name');
        $user->lastname = $request->get('last_name');

        if ($request->has('http_referer')) {
            $user->referral_url = substr($request->get('http_referer'), 0, 180);
        }

        $user->note  = "<p><ul><li>User created via application " . $api_client->name . "<br></li>";
        if ($request->has('tags')) {
            $user->note .= "<li>Client tags: " . implode(", ", $request->get('tags')) . "</li>";
        }
        $user->note .= "</ul></p>";

        $user->save();

        /* General relation */
        $relation = new Relation;
        $relation->user_id = $user->id;
        $relation->debtor_code = mt_rand(1000000, 9999999);

        /* My company */
        $relation->kind_id = RelationKind::where('kind_name','zakelijk')->first()->id;
        $relation->company_name = $request->get('company');
        $relation->type_id = RelationType::where('type_name', 'aannemer')->first()->id;
        $relation->email = $user->email;

        if ($request->has('phone')) {
            $relation->phone = $request->get('phone');
        }

        $relation->save();

        $user->self_id = $relation->id;
        $user->save();

        /* Contact */
        $contact = new Contact;
        $contact->firstname = $request->input('first_name');
        $contact->lastname = $request->input('last_name');
        $contact->email = $user->email;
        $contact->relation_id = $relation->id;
        $contact->function_id = ContactFunction::where('function_name','eigenaar')->first()->id;

        if ($request->has('phone')) {
            $contact->phone = $request->get('phone');
        }

        $contact->save();

        $data = array(
            'email' => $user->email,
            'token' => $user->reset_token,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname
        );
        Mail::send('mail.confirm', $data, function($message) use ($data) {
            $message->to($data['email'], ucfirst($data['firstname']) . ' ' . ucfirst($data['lastname']));
            $message->subject('BynqIO\CalculatieTool.com - Account activatie');
            $message->from('info@calculatietool.com', 'BynqIO\CalculatieTool.com');
            $message->replyTo('support@calculatietool.com', 'BynqIO\CalculatieTool.com');
        });

        $user->save();

        Audit::CreateEvent('api.account.new.success', 'Created new account from template using application ' . $api_client->name, $user->id);

        if (!config('app.debug')) {
            $data = array(
                'email' => $user->email,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'company' => $relation->company_name,
                'contact_first' => $contact->firstname,
                'contact_last'=> $contact->lastname
            );
            Mail::send('mail.inform_new_user', $data, function($message) use ($data) {
                $message->to('administratie@calculatietool.com', 'BynqIO\CalculatieTool.com');
                $message->subject('BynqIO\CalculatieTool.com - Account activatie');
                $message->from('info@calculatietool.com', 'BynqIO\CalculatieTool.com');
                $message->replyTo('administratie@calculatietool.com', 'BynqIO\CalculatieTool.com');
            });
        }

        return response()->json(['success' => 1]);
    }

}
