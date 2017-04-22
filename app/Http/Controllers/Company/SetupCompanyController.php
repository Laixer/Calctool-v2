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
use BynqIO\CalculatieTool\Models\Contact;
use BynqIO\CalculatieTool\Models\ContactFunction;
use BynqIO\CalculatieTool\Models\Relation;
use BynqIO\CalculatieTool\Models\RelationKind;
use BynqIO\CalculatieTool\Http\Middleware\RequireNoCompany;
use BynqIO\CalculatieTool\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SetupCompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware(RequireNoCompany::class);
    }

    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'company_type' => ['required','numeric'],
            'company_name' => ['required','max:50'],
            'kvk' => ['nullable', 'numeric','min:8'],
            'btw' => ['nullable', 'alpha_num','min:14'],
            'telephone_comp' => ['nullable', 'alpha_num','max:12'],
            'email_comp' => ['nullable','email','max:80'],
            'street' => ['required','max:60'],
            'address_number' => ['required','alpha_num','max:5'],
            'zipcode' => ['required'],
            'city' => ['required','max:35'],
            'province' => ['required','numeric'],
            'country' => ['required','numeric'],
            'website' => ['max:180'],
        ]);

        /* General */
        $relation = new Relation;
        $relation->user_id = $request->user()->id;
        $relation->note = $request->input('note');
        $relation->debtor_code = mt_rand(1000000, 9999999);

        /* Company */
        $relation->kind_id = RelationKind::where('kind_name','zakelijk')->firstOrFail()->id;
        $relation->company_name = $request->input('company_name');
        $relation->type_id = $request->input('company_type');
        $relation->kvk = $request->input('kvk');
        $relation->btw = $request->input('btw');
        $relation->phone = $request->input('telephone_comp');
        $relation->email = $request->input('email_comp');
        $relation->website = $request->input('website');

        /* Adress */
        $relation->address_street = $request->input('street');
        $relation->address_number = $request->input('address_number');
        $relation->address_postal = $request->input('zipcode');
        $relation->address_city = $request->input('city');
        $relation->province_id = $request->input('province');
        $relation->country_id = $request->input('country');
        $relation->save();

        $contact = new Contact;
        $contact->firstname = $request->user()->firstname;
        $contact->lastname = $request->user()->lastname;
        $contact->email = $request->user()->email;
        $contact->relation_id = $relation->id;
        $contact->function_id = ContactFunction::where('function_name','directeur')->firstOrFail()->id;
        $contact->save();

        $request->user()->self_id = $relation->id;
        $request->user()->save();

        Audit::CreateEvent('company.new.success', 'New company setup');

        return back()->with('success', 'Uw bedrijfsgegevens zijn opgeslagen');
    }
}
