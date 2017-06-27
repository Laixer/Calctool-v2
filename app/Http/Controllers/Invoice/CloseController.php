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

namespace BynqIO\Dynq\Http\Controllers\Invoice;

use Carbon\Carbon;
use BynqIO\Dynq\Models\Project;
use BynqIO\Dynq\Models\Invoice;
use BynqIO\Dynq\Models\Offer;
use BynqIO\Dynq\Models\Relation;
use BynqIO\Dynq\Models\Contact;
use BynqIO\Dynq\Models\Resource;
use BynqIO\Dynq\Calculus\InvoiceTerm;
use BynqIO\Dynq\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Encryptor;
use PDF;

class CloseController extends Controller
{
    protected function invoiceNumber($id, $user)
    {
        return sprintf("%s%05d-%03d-%s", $user->invoicenumber_prefix, $id, $user->invoice_counter, date('y'));
    }

    private function saveReport($user, $invoice, $project)
    {
        $relation = Relation::findOrFail($project->client_id);
        $relation_self = Relation::findOrFail($user->self_id);

        $data = [
            'company'  => $relation_self->name(),
            'address'  => $relation_self->fullAddress(),
            'phone'    => $relation_self->phone_number,
            'email'    => $relation_self->email,
            'pages'    => ['main'],
        ];

        $letter = [
            'document'         => 'Factuur',
            'document_number'  => $invoice->invoice_code,
            'document_date'    => Carbon::now(),
            'project'          => $project,
            'relation'         => $relation,
            'relation_self'    => $relation_self,
            'contact_to'       => Contact::find($invoice->to_contact_id),
            'contact_from'     => Contact::find($invoice->from_contact_id),
            'reference'        => '394969#11',
            'pretext'          => $invoice->description,
            'posttext'         => $invoice->closure,
        ];

        $pdf = PDF::loadView('letter', array_merge($data, $letter));
        $pdf->setOption('footer-font-size', 8);
        $pdf->setOption('footer-left', $relation_self->name());
        $pdf->setOption('footer-right', 'Pagina [page]/[toPage]');
        $pdf->setOption('encoding', 'utf-8');
        $pdf->setOption('lowquality', false);

        $file = Encryptor::putAuto($user->ownCompany->encodedName(), 'pdf', $pdf->output());

        $resource = new Resource;
        $resource->resource_name = 'invoice.pdf';
        $resource->file_location = $file;
        $resource->file_size = mb_strlen($pdf->output());
        $resource->user_id = $user->id;
        $resource->description = 'Factuurversie';

        $resource->save();

        $invoice->resource_id = $resource->id;
    }

    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'id'            => ['required', 'integer'],
            'project'       => ['required', 'integer'],
            'contact_to'    => ['required'],
            'contact_from'  => ['required'],
        ], [
            'contact_to.required'    => 'Geef een contactpersoon op en klik op Bijwerken',
            'contact_from.required'  => 'Geef een afzender op en klik op Bijwerken',
        ]);

        $project = Project::findOrFail($request->get('project'));
        if (!$project->isOwner()) {
            return back()->withInput($request->all());
        }

        $invoice = Invoice::findOrFail($request->get('id'));
        $invoice->to_contact_id = $request->get('contact_to');
        $invoice->from_contact_id = $request->get('contact_from');

        // $invoice_version                 = InvoiceVersion::where('invoice_id', $invoice->id)->orderBy('created_at','desc')->first();
        // $invoice->include_tax            = $invoice_version->include_tax;
        // $invoice->only_totals            = $invoice_version->only_totals;

        // $invoice->description            = $invoice_version->description;
        // $invoice->closure                = $invoice_version->closure;

        if ($request->has('seperate-subcon')) {
            $invoice->seperate_subcon = true;
        } else {
            $invoice->seperate_subcon = false;
        }

        if ($request->has('display-worktotals')) {
            $invoice->display_worktotals = true;
        } else {
            $invoice->display_worktotals = false;
        }

        if ($request->has('display-specification')) {
            $invoice->display_specification = true;
        } else {
            $invoice->display_specification = false;
        }

        if ($request->has('display-description')) {
            $invoice->display_description = true;
        } else {
            $invoice->display_description = false;
        }

        $invoice->invoice_close          = true;
        $invoice->invoice_code           = $this->invoiceNumber($project->id, $request->user());
        $invoice->bill_date              = Carbon::now();
        $invoice->save();

        $this->saveReport($request->user(), $invoice, $project);

        $invoice->save();

        $request->user()->invoice_counter++;
        $request->user()->save();

        return redirect("project/{$project->id}-{$project->slug()}/invoices");
    }

}
