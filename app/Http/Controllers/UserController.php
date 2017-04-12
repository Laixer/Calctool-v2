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

class UserController extends Controller
{
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
}
