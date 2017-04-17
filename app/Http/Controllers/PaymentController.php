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

use BynqIO\CalculatieTool\Models\Payment;
use BynqIO\CalculatieTool\Models\User;
use BynqIO\CalculatieTool\Models\Project;
use BynqIO\CalculatieTool\Models\Audit;
use BynqIO\CalculatieTool\Models\Promotion;
use BynqIO\CalculatieTool\Models\UserGroup;
use BynqIO\CalculatieTool\Models\BankAccount;
use BynqIO\CalculatieTool\Models\Resource;
use BynqIO\CalculatieTool\Models\CTInvoice;
use BynqIO\CalculatieTool\Models\Contact;
use BynqIO\CalculatieTool\Models\Relation;
use BynqIO\CalculatieTool\Events\UserPaymentSuccess;
use BynqIO\CalculatieTool\Events\UserSubscriptionCanceled;

use Auth;
use Redis;
use Hash;
use Mail;
use DB;
use PDF;

class PaymentController extends Controller
{
    /**
     * Constant defaults.
     */
    const DEFAULT_INCREMENT = 1;
    const DEFAULT_LANGUAGE  = 'nl';

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

        $this->middleware('auth')->except('doPaymentUpdate');
        $this->middleware('guest')->only('doPaymentUpdate');
    }

    protected function userSubscription($customerId)
    {
        return $this->mollie->customers_subscriptions->withParentId($customerId);
    }

    protected function setupSubscription($order, $customerId)
    {
        $subscription = $this->userSubscription($customerId)->create([
            "amount"		=> $order->amount,
            "interval"		=> "1 month",
            "description"	=> "Maandelijkse incasso BynqIO\CalculatieTool.com",
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

    protected function hasPromoCode()
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

    protected function newToken()
    {
        return sha1(mt_rand() . time());
    }

    protected function registerUserAsCustomer()
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

    protected function newPayment($transaction, $description, $amount = 0, $status = 'paid', $inc = self::DEFAULT_INCREMENT)
    {
        $order = new Payment;

        $order->transaction = $transaction;
        $order->token       = $this->newToken();
        $order->amount      = $amount;
        $order->status      = $status;
        $order->increment   = $inc;
        $order->description = $description;
        $order->method      = '';
        
        return $order;
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

            event(new UserPaymentSuccess($user, $order));

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
        if (app()->environment('local')) {
            return redirect('account')->withErrors(['status' => ['Callback niet mogelijk op local dev']]);
        }
        
        $relation_self = Relation::find(Auth::user()->self_id);
        if (!$relation_self) {
            return redirect('account')->withErrors(['status' => ['Account vereist bedrijfsgegevens']]);
        }
        
        $token = $this->newToken();
        $increment = self::DEFAULT_INCREMENT;
        if ($request->has('incr'))
            $increment = $request->get('incr');

        $amount = $increment * UserGroup::find(Auth::user()->user_group)->subscription_amount;
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
                    "incr"		=> $increment,
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

            return redirect('account')->withErrors(['status' => ['Aanmaken van een betaling is mislukt']]);
        }

        $order = $this->newPayment($payment->id, $description, $amount, $payment->status, $increment);
        $order->token = $token;
        
        if (isset($payment_object['recurringType']))
            $order->recurring_type = $payment_object['recurringType'];

        $order->user_id = Auth::id();
        $order->save();

        Audit::CreateEvent('account.payment.initiated.success', 'Create payment ' . $payment->id . ' for ' . $amount);

        return redirect($payment->links->paymentUrl);
    }

    /**
     * Extend user acount without payment.
     * GET payment/increasefree
     *
     * @return Response
     */
    public function getPaymentFree(Request $request)
    {
        //TODO: move into user
        if (UserGroup::find(Auth::user()->user_group)->subscription_amount > 0) {
            return redirect('account')->withErrors(['status' => ['Account vereist betaling']]);
        }

        $order = $this->newPayment('CTFREE', 'Verleng gratis met een maand');
        $order->user_id = Auth::id();
        $order->save();

        /* Increase account subscription */
        $user = Auth::user();
        $user->expiration_date = date('Y-m-d', strtotime("+1 month", strtotime($user->expiration_date)));
        $user->save();

        Audit::CreateEvent('account.payment.free.success', 'Payment free succeeded');

        event(new UserPaymentSuccess($user, $order));

        return redirect('account')->with('success','Bedankt voor uw betaling');
    }

    public function getPaymentFinish(Request $request, $token)
    {
        $order = Payment::where('token', $token)->first();
        if (!$order) {
            return redirect('account')->withErrors(['status' => ['Transactie niet geldig']]);
        }

        $payment = $this->mollie->payments->get($order->transaction);
        if ($payment->isPaid()) {

            /*
             * If both mandate and customer ID are present,
             * setup a subscription.
             */
            if ($payment->mandateId && $payment->customerId) {
                $this->setupSubscription($order, $payment->customerId);
                return redirect('account')->with('success','Bedankt voor uw betaling, automatische incasso is ingesteld');
            }

            return redirect('account')->with('success','Bedankt voor uw betaling');
        } else if ($payment->isOpen() || $payment->isPending()) {
            return redirect('account')->with('success','Betaling is nog niet bevestigd, dit kan enkele dagen duren. Uw heeft in deze periode toegang tot uw account');
        } else if ($payment->isCancelled()) {
            $order->status = $payment->status;
            $order->save();

            return redirect('account')->withErrors(['status' => ['Betaling is afgebroken']]);
        } else if ($payment->isExpired()) {
            $order->status = $payment->status;
            $order->save();

            return redirect('account')->withErrors(['status' => ['Betaling is verlopen']]);
        }

        return redirect('account')->withErrors(['status' => ['Transactie niet afgerond ('.$payment->status.')']]);
    }

    /**
     * Cancel active subscription if any.
     * GET payment/subscription/cancel
     *
     * @return Response
     */
    public function getSubscriptionCancel()
    {
        $user = Auth::user();
        if (!$user->payment_subscription_id)
            return back();

        $subscription_id = $user->payment_subscription_id;
        $customerId      = $user->payment_customer_id;

        $subscription = $this->userSubscription($customerId)->cancel($subscription_id);

        $user->payment_subscription_id = NULL;
        $user->save();

        event(new UserSubscriptionCanceled($user, $subscription_id));

        return back()->with('success', 'Automatische incasso gestopt');
    }    
}
