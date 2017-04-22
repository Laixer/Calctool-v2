<?php

/**
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the BynqIO\CalculatieTool.com.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 */

namespace BynqIO\CalculatieTool\Http\Controllers\Company;

use BynqIO\CalculatieTool\Models\Audit;
use BynqIO\CalculatieTool\Models\Relation;
use BynqIO\CalculatieTool\Models\RelationKind;
use BynqIO\CalculatieTool\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __construct()
    {
        $this->middleware('reqcompany');

        //
    }

    public function updateDetails(Request $request)
    {
        $this->validate($request, [
            /* General */
            'id' => ['required','integer'],
            'company_type' => ['required_if:relationkind,zakelijk','numeric'],
            'company_name' => ['required_if:relationkind,zakelijk','max:50'],
            'kvk' => ['nullable','numeric','min:8'],
            'btw' => ['nullable','alpha_num','min:14'],
            'telephone_comp' => ['nullable','alpha_num','max:12'],
            'email_comp' => ['required_if:relationkind,zakelijk','email','max:80'],
            'street' => ['required','max:60'],
            'address_number' => ['required','alpha_num','max:5'],
            'zipcode' => ['required','size:6'],
            'city' => ['required','max:35'],
            'province' => ['required','numeric'],
            'country' => ['required','numeric']
        ]);

        /* General */
        $relation = Relation::findOrFail($request->input('id'));
        if (!$relation || !$relation->isOwner()) {
            return Redirect::back()->withInput($request->all());
        }
        $relation->note = $request->input('note');

        /* Company */
        $relation_kind = RelationKind::where('id',$relation->kind_id)->firstOrFail();
        if ($relation_kind->kind_name == "zakelijk") {
            $relation->company_name = $request->input('company_name');
            $relation->type_id = $request->input('company_type');
            if (!$request->has('kvk'))
                $relation->kvk = NULL;
            else
                $relation->kvk = $request->input('kvk');
            if (!$request->input('btw'))
                $relation->btw = NULL;
            else
                $relation->btw = $request->input('btw');
            $relation->phone = $request->input('telephone_comp');
            $relation->email = $request->input('email_comp');
            $relation->website = $request->input('website');
        }

        /* Adress */
        $relation->address_street = $request->input('street');
        $relation->address_number = $request->input('address_number');
        $relation->address_postal = $request->input('zipcode');
        $relation->address_city = $request->input('city');
        $relation->province_id = $request->input('province');
        $relation->country_id = $request->input('country');

        $relation->save();

        Audit::CreateEvent('mycompany.update.success', 'Settings for my corporation updated');
        
        return back()->with('success', 'Uw bedrijfsgegevens zijn aangepast');
    }

    public function updateIban(Request $request)
    {
        $this->validate($request, [
            'id' => array('required','integer'),
            'iban' => array('alpha_num','regex:/[a-zA-Z]{2}[0-9]{2}[a-zA-Z0-9]{4}[0-9]{7}([a-zA-Z0-9]?){0,16}/'),
            'iban_name' => array('required','max:50')
        ]);

        $relation = Relation::findOrFail($request->input('id'));
        if (!$relation || !$relation->isOwner()) {
            return back()->withInput($request->all());
        }

        // if (!$relation->iban && !$relation->iban_name) {
        //     $account = new BankAccount;
        //     $account->user_id = Auth::id();
        //     $account->account = $request->input('iban');
        //     $account->account_name = $request->input('iban_name');

        //     $account->save();
        // }

        $relation->iban = $request->get('iban');
        $relation->iban_name = $request->get('iban_name');

        $relation->save();

        // $user = Auth::user();

        //TODO
        // $data = array('email' => Auth::user()->email, 'firstname' => $user->firstname, 'lastname' => $user->lastname);
        // Mail::send('mail.iban_update', $data, function($message) use ($data) {
        //     $message->to($data['email'], ucfirst($data['firstname']) . ' ' . ucfirst($data['lastname']));
        //     $message->subject('BynqIO\CalculatieTool.com - Betaalgegevens aangepast');
        //     $message->from('info@calculatietool.com', 'BynqIO\CalculatieTool.com');
        //     $message->replyTo('support@calculatietool.com', 'BynqIO\CalculatieTool.com');
        // });

        Audit::CreateEvent('account.iban.update.success', 'IBAN and/or account name updated');

        return back()->with('success', 'Betalingsgegevens zijn aangepast');
    }

}
