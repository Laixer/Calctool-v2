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

class PaymentController extends Controller
{
    const DEFAULT_INCREMENT = 1;
    const DEFAULT_LANGUAGE = 'nl';

    /**
     * The API object.
     *
     * @var MollieObject
     */
    protected $mollie;

    /**
     * Create a new payment controller instance with
     * a payment provider key.
     *
     * @return void
     */
    public function __construct()
    {
        $this->mollie = new \Mollie_API_Client;
        $this->mollie->setApiKey(config('services.mollie.key'));
    }

    private function userSubscription($customerId)
    {
        return $this->mollie->customers_subscriptions->withParentId($customerId);
    }

    private function setupSubscription($order, $customerId)
    {
        $subscription = $this->userSubscription($customerId)->create([
            "amount"		=> $order->amount,
            "interval"		=> "1 month",
            "description"	=> "Maandelijkse incasso CalculatieTool.com",
            "webhookUrl"	=> secure_url('payment/webhook/'),
            "metadata"		=> [
                "token"		=> $order->token,
                "uid"		=> Auth::id(),
                "incr"		=> 1,
            ],
        ]);

        Auth::user()->payment_subscription_id = $subscription->id;
        Auth::user()->save();

        return $subscription;
    }

    private function hasPromoCode()
    {
        if (Redis::exists('promo:' . Auth::user()->username)) {
            $promo = Promotion::find(Redis::get('promo:' . Auth::user()->username));
            if ($promo) {
                Redis::del('promo:' . Auth::user()->username);

                return [
                    'amount' => $promo->amount,
                    'description' => ' Actie:' . $promo->name,
                    'promo' => $promo->id
                ];
            }
        }
    }

    private function newToken()
    {
        return sha1(mt_rand() . time());
    }

    private function registerUserAsCustomer()
    {
        if (!Auth::user()->payment_customer_id) {
            $customer = $this->mollie->customers->create([
                "name"  => Auth::user()->username,
                "email" => Auth::user()->email,
            ]);

            Auth::user()->payment_customer_id = $customer->id;
            Auth::user()->save();
        }
    }

    /**
     * Callback from payment provider.
     * POST /payment/webhook
     *
     * @return Response
     */
    public function doPaymentUpdate(Request $request)
    {
        $user = null;
        $increase = 0;

        $payment = $this->mollie->payments->get($request->get('id'));
        if ($payment->recurringType) {
            switch ($payment->recurringType) {
                case 'first':
                    $order = Payment::where('transaction', $payment->id)->first();
                    if (!$order) {
                        return;
                    }
                    $order->status = $payment->status;
                    if ($payment->method)
                        $order->method = $payment->method;
                    else
                        $order->method = '';
                    $order->save();
                    $user = User::find($order->user_id);
                    $increase = $order->increment;
                    break;
                case 'recurring':
                    $user = User::where('payment_subscription_id', $payment->subscriptionId)->first();
                    $order = new Payment;
                    $order->transaction = $payment->id;
                    $order->token = sha1(mt_rand().time());
                    $order->amount = $payment->amount;
                    $order->status = $payment->status;
                    $order->increment = 1;
                    $order->description = $payment->description;
                    if ($payment->method)
                        $order->method = $payment->method;
                    else
                        $order->method = '';
                    $order->recurring_type = $payment->recurringType;
                    $order->user_id = $user->id;
                    $order->save();
                    $increase = $order->increment;
                    break;
            }
        } else {
            $order = Payment::where('transaction', $payment->id)->where('status', 'open')->whereNull('recurring_type')->first();
            if (!$order) {
                return;
            }

            if ($payment->metadata->token != $order->token)
                return;

            if ($payment->metadata->uid != $order->user_id)
                return;

            $order->status = $payment->status;
            if ($payment->method)
                $order->method = $payment->method;
            else
                $order->method = '';
            $order->save();
            $user = User::find($payment->metadata->uid);
            $increase = $order->increment;
        }

        if ($payment->isPaid()) {
            $expdate = $user->expiration_date;
            $user->expiration_date = date('Y-m-d', strtotime("+" . $increase . " month", strtotime($expdate)));
            $user->save();

            $ctinvoice = CTInvoice::orderBy('invoice_count','desc')->first();
            if (!$ctinvoice) {
                $ctinvoice = new CTInvoice;
                $ctinvoice->invoice_count = 0;
                $ctinvoice->payment_id = $order->id;
                $ctinvoice->save();
            } else {
                $nctinvoice = new CTInvoice;
                $nctinvoice->invoice_count = $ctinvoice->invoice_count + 1;
                $nctinvoice->payment_id = $order->id;
                $nctinvoice->save();
                $ctinvoice = $nctinvoice;
            }

            $relation_self = Relation::find($user->self_id);
            $contact_user = Contact::where('relation_id', $user->self_id)->first();
            $newname = $user->id . '-'.substr(md5(uniqid()), 0, 5).'-ct_invoice.pdf';
            $pdf = PDF::loadView('base.ct_invoice_pdf', [
                'name' => $contact_user->getFormalName(),
                'date' => $user->dueDateHuman(),
                'amount' => $order->amount,
                'user_id' => $user->id,
                'relation_self' => $relation_self,
                'reference' => $order->transaction,
                'payment_id' => mt_rand(100,999) . '-' . $order->id,
                'invoice_id' => 'FACTUUR-' . $ctinvoice->invoice_count,
            ]);

            $footer_text = 'CalculatieTool.com';
            $footer_text .= ' | IBAN: NL29INGB0006863509';
            $footer_text .= ' | KVK: 54565243';
            $footer_text .= ' | BTW: 851353423B01';

            $pdf->setOption('zoom', 1.1);
            $pdf->setOption('footer-font-size', 8);
            $pdf->setOption('footer-left', $footer_text);
            $pdf->setOption('footer-right', 'Pagina [page]/[toPage]');
            $pdf->setOption('lowquality', false);
            $pdf->save('user-content/' . $newname);

            $resource = new Resource;
            $resource->resource_name = $newname;
            $resource->file_location = 'user-content/' . $newname;
            $resource->file_size = filesize('user-content/' . $newname);
            $resource->user_id = $user->id;
            $resource->description = 'CTFactuur';
            $resource->save();

            $order->resource_id = $resource->id;
            $order->save();

            $data = array(
                'email' => $user->email,
                'amount' => number_format($payment->amount, 2,",","."),
                'expdate' => date('j F Y', strtotime($user->expiration_date)),
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'pdf' => $resource->file_location,
            );
            Mail::send('mail.paid', $data, function($message) use ($data) {
                $message->to($data['email'], ucfirst($data['firstname']) . ' ' . ucfirst($data['lastname']));
                $message->bcc('administratie@calculatietool.com', 'Gebruiker account verlengd');
                $message->attach($data['pdf']);
                $message->subject('CalculatieTool.com - Account verlengd');
                $message->from('info@calculatietool.com', 'CalculatieTool.com');
                $message->replyTo('administratie@calculatietool.com', 'CalculatieTool.com');
            });

            Audit::CreateEvent('account.payment.callback.success', 'Payment ' . $payment->id . ' succeeded', $user->id);
        }

        return response()->json(['success' => 1]);
    }

    /**
     * Start new payment.
     * GET /payment
     *
     * @return Response
     */
    public function getPayment(Request $request)
    {
        if (\App::environment('local')) {
            $errors = new MessageBag(['status' => ['Callback niet mogelijk op local dev']]);
            return redirect('myaccount')->withErrors($errors);
        }
        
        $relation_self = Relation::find(Auth::user()->self_id);
        if (!$relation_self) {
            $errors = new MessageBag(['status' => ['Account vereist bedrijfsgegevens']]);
            return redirect('myaccount')->withErrors($errors);
        }
        
        $token = $this->newToken();
        $increment_months = self::DEFAULT_INCREMENT;
        if ($request->has('incr'))
            $increment_months = $request->get('incr');

        $amount = $increment_months * UserGroup::find(Auth::user()->user_group)->subscription_amount;
        $description = 'Verleng met ' . $increment_months . ' maand(en)';
        $promo_id = -1;

        /*
         * Check if the user entered a promo code.
         */
        $arr = $this->hasPromoCode();
        if ($arr) {
            $amount = $arr->amount;
            $description = $arr->description;
            $promo_id = $arr->promo;
        }

        try {
            $payment_object = [
                'amount'        => $amount,
                'description'   => $description,
                "locale"		=> self::DEFAULT_LANGUAGE,
                "webhookUrl"	=> secure_url('payment/webhook/'),
                'redirectUrl'   => secure_url('payment/order/' . $token),
                "metadata"		=> [
                    "token"		=> $token,
                    "uid"		=> Auth::id(),
                    "incr"		=> $increment_months,
                ],
            ];

            /*
             * First transaction of subscription.
             */
            if ($request->has('auto') && !Auth::user()->payment_subscription_id) {
                $this->registerUserAsCustomer();
                
                $payment_object['description'] = 'Automatische incasso';
                $payment_object['customerId'] = Auth::user()->payment_customer_id;
                $payment_object['recurringType'] = 'first';
            }

            $payment = $this->mollie->payments->create($payment_object);
        } catch (\Mollie_API_Exception $e) {
            Audit::CreateEvent('account.payment.initiated.failed', 'Create payment failed with ' . $e->getMessage());

            $errors = new MessageBag(['status' => ['Aanmaken van een betaling is mislukt']]);
            return redirect('myaccount')->withErrors($errors);
        }

        $order = new Payment;
        $order->transaction = $payment->id;
        $order->token = $token;
        $order->amount = $amount;
        $order->status = $payment->status;
        $order->increment = $increment_months;
        $order->description = $description;
        $order->method = '';
        if (isset($payment_object['recurringType']))
            $order->recurring_type = $payment_object['recurringType'];
        $order->user_id = Auth::id();
        $order->save();

        Audit::CreateEvent('account.payment.initiated.success', 'Create payment ' . $payment->id . ' for ' . $amount);

        return redirect($payment->links->paymentUrl)->withCookie(cookie()->forget('_dccod'.Auth::id()));
    }

    /**
     * Extend user acount without payment.
     * GET payment/increasefree
     *
     * @return Response
     */
    public function getPaymentFree(Request $request)
    {
        if (UserGroup::find(Auth::user()->user_group)->subscription_amount > 0) {
            $errors = new MessageBag(['status' => ['Account vereist betaling']]);
            return redirect('myaccount')->withErrors($errors);
        }

        $user = Auth::user();
        $expdate = $user->expiration_date;
        $user->expiration_date = date('Y-m-d', strtotime("+1 month", strtotime($expdate)));

        $user->save();

        $order = new Payment;
        $order->transaction = 'CT_FREE';
        $order->token = sha1(mt_rand().time());
        $order->amount = 0;
        $order->status = 'paid';
        $order->increment = 1;
        $order->description = 'Verleng gratis met een maand';
        $order->method = '';
        $order->user_id = $user->id;
        $order->save();
        
        $relation_self = Relation::find($user->self_id);
        if (!$relation_self) {
            $errors = new MessageBag(['status' => ['Account vereist bedrijfsgegevens']]);
            return redirect('myaccount')->withErrors($errors);
        }

        $contact_user = Contact::where('relation_id', $user->self_id)->first();
        $ctinvoice = CTInvoice::orderBy('invoice_count','desc')->first();
        if (!$ctinvoice) {
            $ctinvoice = new CTInvoice;
            $ctinvoice->invoice_count = 0;
            $ctinvoice->payment_id = $order->id;
            $ctinvoice->save();
        } else {
            $nctinvoice = new CTInvoice;
            $nctinvoice->invoice_count = $ctinvoice->invoice_count + 1;
            $nctinvoice->payment_id = $order->id;
            $nctinvoice->save();
            $ctinvoice = $nctinvoice;
        }

        $newname = $user->id . '-'.substr(md5(uniqid()), 0, 5).'-ct_invoice.pdf';
        $pdf = PDF::loadView('base.ct_invoice_pdf', [
            'name' => $contact_user->getFormalName(),
            'date' => $user->dueDateHuman(),
            'amount' => $order->amount,
            'user_id' => $user->id,
            'relation_self' => $relation_self,
            'reference' => $order->transaction,
            'payment_id' => mt_rand(100,999) . '-' . $order->id,
            'invoice_id' => 'FACTUUR-' . $ctinvoice->invoice_count,
        ]);

        $footer_text = 'CalculatieTool.com';
        $footer_text .= ' | IBAN: NL29INGB0006863509';
        $footer_text .= ' | KVK: 54565243';
        $footer_text .= ' | BTW: 851353423B01';

        $pdf->setOption('zoom', 1.1);
        $pdf->setOption('footer-font-size', 8);
        $pdf->setOption('footer-left', $footer_text);
        $pdf->setOption('footer-right', 'Pagina [page]/[toPage]');
        $pdf->setOption('lowquality', false);
        $pdf->save('user-content/' . $newname);

        $resource = new Resource;
        $resource->resource_name = $newname;
        $resource->file_location = 'user-content/' . $newname;
        $resource->file_size = filesize('user-content/' . $newname);
        $resource->user_id = Auth::id();
        $resource->description = 'CTFactuur';
        $resource->save();

        $order->resource_id = $resource->id;
        $order->save();

        Audit::CreateEvent('account.payment.free.success', 'Payment free succeeded');

        return redirect('myaccount')->with('success','Bedankt voor uw betaling');
    }

    public function getPaymentFinish(Request $request, $token)
    {
        $order = Payment::where('token', $token)->first();
        if (!$order) {
            $errors = new MessageBag(['status' => ['Transactie niet geldig']]);
            return redirect('myaccount')->withErrors($errors);
        }

        $payment = $this->mollie->payments->get($order->transaction);
        if ($payment->isPaid()) {

            /*
             * If both mandate and customer ID are present,
             * setup a subscription.
             */
            if ($payment->mandateId && $payment->customerId) {
                $this->setupSubscription($order, $payment->customerId);
                return redirect('myaccount')->with('success','Bedankt voor uw betaling, automatische incasso is ingesteld');
            }

            return redirect('myaccount')->with('success','Bedankt voor uw betaling');
        } else if ($payment->isOpen() || $payment->isPending()) {
            return redirect('myaccount')->with('success','Betaling is nog niet bevestigd, dit kan enkele dagen duren. Uw heeft in deze periode toegang tot uw account');
        } else if ($payment->isCancelled()) {
            $order->status = $payment->status;
            $order->save();
            $errors = new MessageBag(['status' => ['Betaling is afgebroken']]);
            return redirect('myaccount')->withErrors($errors);
        } else if ($payment->isExpired()) {
            $order->status = $payment->status;
            $order->save();
            $errors = new MessageBag(['status' => ['Betaling is verlopen']]);
            return redirect('myaccount')->withErrors($errors);
        }

        $errors = new MessageBag(['status' => ['Transactie niet afgerond ('.$payment->status.')']]);
        return redirect('myaccount')->withErrors($errors);
    }

    /**
     * Cancel active subscription if any.
     * GET payment/subscription/cancel
     *
     * @return Response
     */
    public function getSubscriptionCancel()
    {
        if (!Auth::user()->payment_subscription_id)
            return back();

        $subscription_id = Auth::user()->payment_subscription_id;
        $customerId      = Auth::user()->payment_customer_id;

        $subscription = $this->userSubscription($customerId)->cancel(subscription_id);

        Auth::user()->payment_subscription_id = NULL;
        Auth::user()->save();

        if (!config('app.debug')) {
            $data = array(
                'user' => Auth::user()->username,
                'subscription' => $subscription_id,
            );

            Mail::send('mail.payment_stopped', $data, function($message) use ($data) {
                $message->to('administratie@calculatietool.com', 'CalculatieTool.com');
                $message->subject('CalculatieTool.com - Automatische incasso gestopt');
                $message->from('info@calculatietool.com', 'CalculatieTool.com');
                $message->replyTo('administratie@calculatietool.com', 'CalculatieTool.com');
            });
        }

        return back()->with('success', 'Automatische incasso gestopt');
    }    
}
