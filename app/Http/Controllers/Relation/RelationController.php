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

namespace BynqIO\CalculatieTool\Http\Controllers\Relation;

use Illuminate\Http\Request;
use JeroenDesloovere\VCard\VCard;

use BynqIO\CalculatieTool\Models\Relation;
use BynqIO\CalculatieTool\Models\RelationKind;
use BynqIO\CalculatieTool\Models\Contact;
use BynqIO\CalculatieTool\Models\Audit;
use BynqIO\CalculatieTool\Models\ContactFunction;
use BynqIO\CalculatieTool\Models\Resource;
use BynqIO\CalculatieTool\Http\Controllers\Controller;

use Image;
use Storage;

class RelationController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /relation
     *
     * @return Response
     */

    public function getAll()
    {
        return view('relation.all');
    }

    public function getNew()
    {
        return view('relation.new_relation', ['debtor_code' => mt_rand(1000000, 9999999)]);
    }

    public function details()
    {
        return view('relation.details');
    }

    public function contacts()
    {
        return view('relation.contacts');
    }

    public function financial()
    {
        return view('relation.financial');
    }

    public function invoices()
    {
        return view('relation.invoices');
    }

    public function preferences()
    {
        return view('relation.preferences');
    }

    public function getNewContact()
    {
        return view('relation.new_contact');
    }

    public function getEditContact()
    {
        return view('relation.edit_contact');
    }

    public function getImport()
    {
        return view('relation.import');
    }

    public function doUpdate(Request $request)
    {
        $this->validate($request, [
            'id' => array('required','integer'),
            'debtor' => array('required','alpha_num','max:10'),
            'company_type' => array('required_if:relationkind,zakelijk','numeric'),
            'company_name' => array('required_if:relationkind,zakelijk','max:50'),
            'email_comp' => array('required_if:relationkind,zakelijk','email','max:80'),
            'street' => array('required','max:60'),
            'address_number' => array('required','alpha_num','max:5'),
            'zipcode' => array('required','size:6'),
            'city' => array('required','max:35'),
            'province' => array('required','numeric'),
            'country' => array('required','numeric')
        ]);

        /* General */
        $relation = \BynqIO\CalculatieTool\Models\Relation::findOrFail($request->input('id'));
        if (!$relation->isOwner()) {
            return back()->withInput($request->all());
        }
        $relation->note = $request->input('note');
        $relation->debtor_code = $request->input('debtor');

        /* Company */
        $relation_kind = \BynqIO\CalculatieTool\Models\RelationKind::findOrFail($relation->kind_id);
        if ($relation_kind->kind_name == "zakelijk") {
            $relation->company_name = $request->input('company_name');
            $relation->type_id = $request->input('company_type');
            $relation->kvk = $request->input('kvk');
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

        return back()->with('success', 'Relatie is aangepast');
    }

    public function getDelete(Request $request, $relation_id)
    {
        $relation = \BynqIO\CalculatieTool\Models\Relation::find($relation_id);
        if (!$relation || !$relation->isOwner()) {
            return back()->withInput($request->all());
        }

        $relation->active = false;

        $relation->save();

        return redirect('/relation');
    }

    public function getConvert(Request $request, $relation_id)
    {
        $relation = \BynqIO\CalculatieTool\Models\Relation::findOrFail($relation_id);
        if (!$relation->isOwner()) {
            return back()->withInput($request->all());
        }

        if (\BynqIO\CalculatieTool\Models\RelationKind::findOrFail($relation->kind_id)->kind_name == 'zakelijk') {
            $relation->kind_id = \BynqIO\CalculatieTool\Models\RelationKind::where('kind_name','particulier')->first()->id;
        } else {
            $relation->kind_id = \BynqIO\CalculatieTool\Models\RelationKind::where('kind_name','zakelijk')->first()->id;
            if (!$relation->company_name)
                $relation->company_name = 'onbekend';
            if (!$relation->email)
                $relation->email = 'onbekend@calculatietool.com';
        }

        // $relation->active = false;

        $relation->save();

        return back()->with('success', 'Relatie is omgezet');
    }

    public function doUpdateContact(Request $request)
    {
        $this->validate($request, [
            'id' => array('required','integer'),
            'contact_salutation' => array('max:16'),
            'contact_name' => array('required','max:50'),
            'contact_firstname' => array('max:30'),
            'email' => array('required','email','max:80'),
        ]);

        $contact = \BynqIO\CalculatieTool\Models\Contact::find($request->input('id'));
        if (!$contact) {
            return back()->withInput($request->all());
        }
        $relation = \BynqIO\CalculatieTool\Models\Relation::find($contact->relation_id);
        if (!$relation || !$relation->isOwner()) {
            return back()->withInput($request->all());
        }

        if ($request->input('contact_salutation')) {
            $contact->salutation = $request->input('contact_salutation');
        }
        if ($request->input('contact_firstname')) {
            $contact->firstname = $request->input('contact_firstname');
        }
        $contact->lastname = $request->input('contact_name');
        $contact->mobile = $request->input('mobile');
        $contact->phone = $request->input('telephone');
        $contact->email = $request->input('email');
        $contact->note = $request->input('note');
        if ($request->input('contactfunction')) {
            $contact->function_id = $request->input('contactfunction');
        }
        if ($request->input('gender') == '-1') {
            $contact->gender = NULL;
        } else {
            $contact->gender = $request->input('gender');
        }

        $contact->save();

        return back()->with('success', 'Contactgegevens zijn aangepast');
    }

    public function doUpdateIban(Request $request)
    {
        $this->validate($request, [
            'iban' => array('alpha_num','max:25'),
            'iban_name' => array('max:50'),
        ]);

        $relation = \BynqIO\CalculatieTool\Models\Relation::find($request->input('id'));
        if (!$relation || !$relation->isOwner()) {
            return back()->withInput($request->all());
        }

        $relation->iban = $request->input('iban');
        $relation->iban_name = $request->input('iban_name');

        $relation->save();

        return back()->with('success', 'Betalingsgegevens zijn aangepast');
    }

    public function doNew(Request $request)
    {
        $rules = array(
            'relationkind' => array('required','numeric'),
            'debtor' => array('required','alpha_num','max:10'),
            'company_type' => array('required_if:relationkind,1','numeric'),
            'company_name' => array('required_if:relationkind,1','max:50'),
            'email_comp' => array('required_if:relationkind,1','email','max:80'),
            'contact_salutation' => array('max:16'),
            'contact_name' => array('required','max:50'),
            'contact_firstname' => array('max:30'),
            'email' => array('required','email','max:80'),
            'contactfunction' => array('required','numeric'),
            'street' => array('required','max:60'),
            'address_number' => array('required','alpha_num','max:5'),
            'zipcode' => array('required','size:6'),
            'city' => array('required','max:35'),
            'province' => array('required','numeric'),
            'country' => array('required','numeric'),
            'telephone' => array('max:12'),
            'mobile' => array('max:12'),
            'website' => array('max:180'),
            'iban' => array('alpha_num','max:25'),
            'iban_name' => array('max:50'),
        );

        $this->validate($request, $rules);

        /* General */
        $relation = new \BynqIO\CalculatieTool\Models\Relation;
        $relation->user_id = $request->user()->id;
        $relation->note = $request->input('note');
        $relation->kind_id = $request->input('relationkind');
        $relation->debtor_code = $request->input('debtor');

        /* Company */
        $relation_kind = \BynqIO\CalculatieTool\Models\RelationKind::where('id','=',$relation->kind_id)->firstOrFail();
        if ($relation_kind->kind_name == "zakelijk") {
            $relation->company_name = $request->input('company_name');
            $relation->type_id = $request->input('company_type');
            $relation->kvk = $request->input('kvk');
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

        if ($request->input('iban'))
            $relation->iban = $request->input('iban');
        if ($request->input('iban_name'))
            $relation->iban_name = $request->input('iban_name');

        $relation->save();

        /* Contact */
        $contact = new \BynqIO\CalculatieTool\Models\Contact;
        $contact->salutation = $request->input('contact_salutation');
        $contact->firstname = $request->input('contact_firstname');
        $contact->lastname = $request->input('contact_name');
        $contact->mobile = $request->input('mobile');
        $contact->phone = $request->input('telephone');
        $contact->email = $request->input('email');
        $contact->note = $request->input('note');
        $contact->relation_id = $relation->id;
        if ($relation_kind->kind_name == "zakelijk") {
            $contact->function_id = $request->input('contactfunction');
        } else {
            $contact->function_id = ContactFunction::where('function_name','=','opdrachtgever')->first()->id;
        }
        if ($request->input('gender') == '-1') {
            $contact->gender = NULL;
        } else {
            $contact->gender = $request->input('gender');
        }

        $contact->save();

        if ($request->get('redirect'))
            return redirect('/'.$request->get('redirect'));

        if ($request->ajax()) {
            if ($relation_kind->kind_name == "zakelijk")
                return response()->json(['success' => true, 'id' => $relation->id, 'name' => $relation->company_name]);
            else
                return response()->json(['success' => true, 'id' => $relation->id, 'name' => $contact->firstname . ' ' . $contact->lastname]);
        }

        return redirect('/relation-'.$relation->id.'/edit')->with('success', 'Relatie opgeslagen');
    }

    public function doNewContact(Request $request)
    {
        $this->validate($request, [
            'id' => array('required','integer'),
            'contact_salutation' => array('max:16'),
            'contact_firstname' => array('required','max:50'),
            'contact_name' => array('required','max:50'),
            'email' => array('required','email','max:80'),
        ]);

        $relation = \BynqIO\CalculatieTool\Models\Relation::find($request->input('id'));
        if (!$relation || !$relation->isOwner()) {
            return back()->withInput($request->all());
        }

        $contact = new \BynqIO\CalculatieTool\Models\Contact;
        $contact->salutation = $request->input('contact_salutation');
        $contact->firstname = $request->input('contact_firstname');
        $contact->lastname = $request->input('contact_name');
        $contact->mobile = $request->input('mobile');
        $contact->phone = $request->input('telephone');
        $contact->email = $request->input('email');
        $contact->note = $request->input('note');
        $contact->relation_id = $relation->id;
        if (\BynqIO\CalculatieTool\Models\RelationKind::find($relation->kind_id)->kind_name=='zakelijk') {
            $contact->function_id = $request->input('contactfunction');
        } else {
            $contact->function_id = ContactFunction::where('function_name','=','opdrachtgever')->first()->id;
        }
        if ($request->input('gender') == '-1') {
            $contact->gender = NULL;
        } else {
            $contact->gender = $request->input('gender');
        }

        $contact->save();

        return redirect('/relation-'.$request->input('id').'/edit')->with('success','Contact opgeslagen');
    }

    public function doDeleteContact()
    {
        $rules = array(
            'id' => array('required','integer'),
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();

            // redirect our user back to the form with the errors from the validator
            return Redirect::back()->withErrors($validator)->withInput($request->all());
        } else {

            $rec = Contact::find($request->input('id'));
            if (!$rec)
                return Redirect::back()->withInput($request->all());
            $relation = Relation::find($rec->relation_id);
            if (!$relation || !$relation->isOwner()) {
                return Redirect::back()->withInput($request->all());
            }

            $rec->delete();

            return Redirect::back()->with('success', 'Contact verwijderd');
        }
    }

    public function doUpdateProfit(Request $request)
    {
        $this->validate($request, [
            'id' => array('required','integer'),
            'hour_rate' => array('regex:/^\$?([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(.[0-9][0-9]?)?$/'),
            'more_hour_rate' => array('required','regex:/^\$?([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(.[0-9][0-9]?)?$/'),
            'profit_material_1' => array('numeric','between:0,200'),
            'profit_equipment_1' => array('numeric','between:0,200'),
            'profit_material_2' => array('numeric','between:0,200'),
            'profit_equipment_2' => array('numeric','between:0,200'),
            'more_profit_material_1' => array('required','numeric','between:0,200'),
            'more_profit_equipment_1' => array('required','numeric','between:0,200'),
            'more_profit_material_2' => array('required','numeric','between:0,200'),
            'more_profit_equipment_2' => array('required','numeric','between:0,200')
        ]);

        $relation = Relation::find($request->input('id'));
        if (!$relation || !$relation->isOwner()) {
            return back()->withInput($request->all());
        }

        $hour_rate = floatval(str_replace(',', '.', str_replace('.', '', $request->input('hour_rate'))));
        if ($hour_rate<0 || $hour_rate>999) {
            return back()->withInput($request->all())->withErrors(['error' => "Ongeldige invoer, vervang punten door comma's"]);
        }

        $hour_rate_more = floatval(str_replace(',', '.', str_replace('.', '', $request->input('more_hour_rate'))));
        if ($hour_rate_more<0 || $hour_rate_more>999) {
            return back()->withInput($request->all())->withErrors(['error' => "Ongeldige invoer, vervang punten door comma's"]);
        }

        if ($hour_rate)
            $relation->hour_rate = $hour_rate;
            $relation->hour_rate_more = $hour_rate_more;
        if ($request->input('profit_material_1') != "")
            $relation->profit_calc_contr_mat = round($request->input('profit_material_1'));
        if ($request->input('profit_equipment_1') != "")
            $relation->profit_calc_contr_equip = round($request->input('profit_equipment_1'));
        if ($request->input('profit_material_2') != "")
            $relation->profit_calc_subcontr_mat = round($request->input('profit_material_2'));
        if ($request->input('profit_equipment_2') != "")
            $relation->profit_calc_subcontr_equip = round($request->input('profit_equipment_2'));
        $relation->profit_more_contr_mat = round($request->input('more_profit_material_1'));
        $relation->profit_more_contr_equip = round($request->input('more_profit_equipment_1'));
        $relation->profit_more_subcontr_mat = round($request->input('more_profit_material_2'));
        $relation->profit_more_subcontr_equip = round($request->input('more_profit_equipment_2'));

        $relation->save();

        Audit::CreateEvent('relation.update.profit.success', 'Profits by relation ' . $relation->id . ' updated');

        return back()->with('success', 'Uurtarief & winstpercentages aangepast');
    }

    public function downloadVCard(Request $request, $relation_id, $contact_id)
    {
        $contact = \BynqIO\CalculatieTool\Models\Contact::find($contact_id);
        if (!$contact) {
            return;
        } else {
            $relation = \BynqIO\CalculatieTool\Models\Relation::find($contact->relation_id);
            if (!$relation || !$relation->isOwner()) {
                return;
            }
        }

        // define vcard
        $vcard = new VCard();

        // define variables
        $additional = '';
        $prefix = '';
        $suffix = '';

        // add personal data
        $vcard->addName($contact->lastname, $contact->firstname, $additional, $prefix, $suffix);

        // add work data
        $vcard->addCompany($relation->company_name);
        $vcard->addJobtitle(ucwords(\BynqIO\CalculatieTool\Models\ContactFunction::find($contact->function_id)->function_name));
        $vcard->addEmail($relation->email);
        if ($relation->phone)
            $vcard->addPhoneNumber($relation->phone, 'WORK');
        if ($relation->mobile)
            $vcard->addPhoneNumber($relation->mobile, 'WORK');

        return $vcard->download();
    }
}
