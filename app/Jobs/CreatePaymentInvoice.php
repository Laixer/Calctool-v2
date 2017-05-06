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

namespace BynqIO\Dynq\Jobs;

use BynqIO\Dynq\Jobs\Job;
use BynqIO\Dynq\Models\CTInvoice;
use BynqIO\Dynq\Models\Relation;
use BynqIO\Dynq\Models\Contact;
use BynqIO\Dynq\Models\Resource;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Mail;
use PDF;
use Storage;

class CreatePaymentInvoice extends Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The data object containing the mail info.
     *
     * @var array
     */
    protected $user;
    protected $order;
    protected $amount;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 60;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $order)
    {
        $this->user = $user;
        $this->order = $order;
    }

    /**
     * Create PDF.
     *
     * @return void
     */
    protected function CTInvoiceNumber()
    {
        $last_invoice = CTInvoice::orderBy('invoice_count','desc')->first();
        if (!$last_invoice) {
            $ctinvoice = new CTInvoice;
            $ctinvoice->invoice_count = 0;
            $ctinvoice->payment_id = $this->order->id;
            $ctinvoice->save();
            return $ctinvoice->invoice_count;
        }

        $ctinvoice = new CTInvoice;
        $ctinvoice->invoice_count = $last_invoice->invoice_count + 1;
        $ctinvoice->payment_id = $this->order->id;
        $ctinvoice->save();
        return $ctinvoice->invoice_count;
    }

    /**
     * Create PDF.
     *
     * @return void
     */
    protected function createPDF($invoice_counter)
    {
        $relation_self = Relation::find($this->user->self_id);
        $contact_user = Contact::where('relation_id', $this->user->self_id)->first();

        $pdf = PDF::loadView('base.ct_invoice_pdf', [
            'name'          => $contact_user->getFormalName(),
            'date'          => $this->user->dueDateHuman(),
            'amount'        => $this->order->amount,
            'user_id'       => $this->user->id,
            'relation_self' => $relation_self,
            'reference'     => $this->order->transaction,
            'payment_id'    => mt_rand(100,999) . '-' . $this->order->id,
            'invoice_id'    => 'FACTUUR-' . $invoice_counter,
        ]);

        $footer_text = config('app.name');
        $footer_text .= ' | IBAN: NL29INGB0006863509';
        $footer_text .= ' | KVK: 54565243';
        $footer_text .= ' | BTW: 851353423B01';

        $pdf->setOption('zoom', 1.1);
        $pdf->setOption('footer-font-size', 8);
        $pdf->setOption('footer-left', $footer_text);
        $pdf->setOption('footer-right', 'Pagina [page]/[toPage]');
        $pdf->setOption('lowquality', false);
        return $pdf;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file = $this->createPDF($this->CTInvoiceNumber());

        /* Resource name and location */
        $name = substr(md5(uniqid()), 0, 32) . '.pdf';
        $path = $this->user->encodedName() . '/' . $name;

        Storage::put($path, $file->output());

        $resource = new Resource;
        $resource->resource_name = $name;
        $resource->file_location = $path;
        $resource->file_size = Storage::size($path);
        $resource->user_id = $this->user->id;
        $resource->description = 'CT Invoice';
        $resource->save();

        /* Attach resource to order */
        $this->order->resource_id = $resource->id;
        $this->order->save();

        /* Send invoice to user */
        $data = array(
            'email'     => $this->user->email,
            'amount'    => number_format($this->order->amount, 2,",","."),
            'expdate'   => date('j F Y', strtotime($this->user->expiration_date)),
            'firstname' => $this->user->firstname,
            'lastname'  => $this->user->lastname,
            'pdf'       => $resource->file_location,
        );
        Mail::send('mail.paid', $data, function($message) use ($data) {
            $message->to($data['email'], ucfirst($data['firstname']) . ' ' . ucfirst($data['lastname']));
            $message->bcc('administratie@calculatietool.com', 'Gebruiker account verlengd');
            $message->attachData(Storage::get($data['pdf']), 'invoice.pdf', [
                'mime' => 'application/pdf',
            ]);
            $message->subject(config('app.name') . ' - Account verlengd');
            $message->from(APP_EMAIL);
        });
    }
}
