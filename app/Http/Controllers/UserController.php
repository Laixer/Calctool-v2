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

namespace BynqIO\CalculatieTool\Http\Controllers;

use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;

use \BynqIO\CalculatieTool\Models\Payment;
use \BynqIO\CalculatieTool\Models\User;
use \BynqIO\CalculatieTool\Models\Project;
use \BynqIO\CalculatieTool\Models\Audit;
use \BynqIO\CalculatieTool\Models\Promotion;
use \BynqIO\CalculatieTool\Models\UserGroup;
use \BynqIO\CalculatieTool\Models\BankAccount;
use \BynqIO\CalculatieTool\Models\Resource;
use \BynqIO\CalculatieTool\Models\CTInvoice;
use \BynqIO\CalculatieTool\Models\Contact;
use \BynqIO\CalculatieTool\Models\Relation;

use Auth;
use Redis;
use Hash;
use Mail;
use DB;
use PDF;

class UserController extends Controller
{
    public function doUpdateIban(Request $request)
    {
        $this->validate($request, [
            'id' => array('required','integer'),
            'iban' => array('alpha_num','max:25'),
            'iban_name' => array('required','max:50')
        ]);

        $relation = \BynqIO\CalculatieTool\Models\Relation::find($request->input('id'));
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
            $message->subject('BynqIO\CalculatieTool.com - Betaalgegevens aangepast');
            $message->from('info@calculatietool.com', 'BynqIO\CalculatieTool.com');
            $message->replyTo('support@calculatietool.com', 'BynqIO\CalculatieTool.com');
        });

        Audit::CreateEvent('account.iban.update.success', 'IBAN and/or account name updated');

        return back()->with('success', 'Betalingsgegevens zijn aangepast');
    }
}
