<?php

/**
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the Dynq project.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 *
 * @package  Dynq
 * @author   Yorick de Wid <y.dewid@calculatietool.com>
 */

namespace BynqIO\Dynq\Http\Controllers\Auth;

use BynqIO\Dynq\Models\User;
use BynqIO\Dynq\Models\Audit;
use BynqIO\Dynq\Models\Relation;
use BynqIO\Dynq\Models\RelationKind;
use BynqIO\Dynq\Models\RelationType;
use BynqIO\Dynq\Events\UserSignup;
use BynqIO\Dynq\Models\UserType;
use BynqIO\Dynq\Models\Contact;
use BynqIO\Dynq\Models\ContactFunction;
use BynqIO\Dynq\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Hash;

class SignupController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Signup Controller
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * Instantiate a new signup controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('utm');

        //
    }

    /**
     * Retrieve signup page.
     *
     * @return Route
     */
    public function index(Request $request)
    {
        if ($request->has('client_referer')) {
            return view('auth.signup', ['client_referer' => $request->get('client_referer')]);
        }

        return view('auth.signup');
    }

    /**
     * Create new account.
     *
     * @return Route
     */
    public function signup(Request $request)
    {
        $request->merge(array('username' => trim(strtolower($request->input('username')))));
        $request->merge(array('email' => trim(strtolower($request->input('email')))));
        
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
        $user->username        = $request->get('username');
        $user->secret          = Hash::make($request->get('secret'));
        $user->firstname       = $user->username;
        $user->reset_token     = sha1(mt_rand());
        $user->referral_key    = md5(mt_rand());
        $user->ip              = $request->ip();
        $user->email           = $request->get('email');
        $user->expiration_date = $expiration_date;
        $user->user_type       = UserType::where('user_type', 'user')->firstOrFail()->id;
        $user->user_group      = 100;
        $user->firstname       = $request->get('contact_firstname');
        $user->lastname        = $request->get('contact_name');

        if ($request->session()->has('referrer')) {
            $user->referral_url = substr($request->session()->pull('referrer'), 0, 180);
        }

        $user->save();

        /* General relation */
        $relation = new Relation;
        $relation->user_id     = $user->id;
        $relation->debtor_code = mt_rand(1000000, 9999999);

        /* Company info */
        $relation->kind_id      = RelationKind::where('kind_name', 'zakelijk')->firstOrFail()->id;
        $relation->company_name = $request->input('company_name');
        $relation->type_id      = RelationType::where('type_name', 'aannemer')->firstOrFail()->id;
        $relation->email        = $user->email;
        $relation->save();

        $user->self_id = $relation->id;
        $user->save();

        /* Contact */
        $contact = new Contact;
        $contact->firstname   = $request->input('contact_firstname');
        $contact->lastname    = $request->input('contact_name');
        $contact->email       = $user->email;
        $contact->relation_id = $relation->id;
        $contact->function_id = ContactFunction::where('function_name','eigenaar')->firstOrFail()->id;
        $contact->save();

        if ($referral_user) {
            $referral_user->expiration_date = date('Y-m-d', strtotime("+3 month", strtotime($referral_user->expiration_date)));

            $referral_user->save();

            Audit::CreateEvent('account.referralkey.used.success', 'Referral key used', $referral_user->id);
        }

        event(new UserSignup($user, $relation, $contact));

        Audit::CreateEvent('account.new.success', 'Created new account from template', $user->id);

        return redirect()->route('signup')->with('success', __('core.accoutncreated'));
    }

}
