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

use BynqIO\Dynq\Models\Project;
use BynqIO\Dynq\Models\Invoice;
use BynqIO\Dynq\Models\Offer;
use BynqIO\Dynq\Calculus\InvoiceTerm;
use BynqIO\Dynq\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Storage;
use PDF;

class CloseController extends Controller
{
    protected function invoiceNumber($id, $user)
    {
        return sprintf("%s%05d-%03d-%s", $user->invoicenumber_prefix, $id, $user->invoice_counter, date('y'));
    }

    private function saveReport($user, $invoice, $project)
    {
        // $newname = $user->id . '-' . substr(md5(uniqid()), 0, 5) . '-' . $invoice->invoice_code . '-invoice.pdf';

        $relation = \BynqIO\Dynq\Models\Relation::findOrFail($project->client_id);
        $relation_self = \BynqIO\Dynq\Models\Relation::findOrFail($user->self_id);

        $data = [
            'company' => $relation_self->name(),
            'address' => $relation_self->fullAddress(),
            'phone' => $relation_self->phone_number,
            'email' => $relation_self->email,
            'overlay' => 'concept',
            'pages' => ['main'],
        ];

        $letter = [
            'document' => 'Factuur',
            'document_number' => $invoice->invoice_code,
            'project' => $project,
            'relation' => $relation,
            'relation_self' => $relation_self,
            'contact_to' => \BynqIO\Dynq\Models\Contact::find($invoice->to_contact_id),
            'contact_from' => \BynqIO\Dynq\Models\Contact::find($invoice->from_contact_id),
            'reference' => '394969#11',
            'pretext' => 'Bij deze doe ik u toekomen mijn prijsopgaaf betreffende het uit te voeren werk. Onderstaand zal ik het werk en de uit te voeren werkzaamheden specificeren zoals afgesproken.',
            'posttext' => 'Hopende u hiermee een passende aanbieding gedaan te hebben, zie ik uw reactie met genoegen tegemoet.',
        ];

        $pdf = PDF::loadView('letter', array_merge($data, $letter));
        $pdf->setOption('footer-font-size', 8);
        $pdf->setOption('footer-left', $relation_self->name());
        $pdf->setOption('footer-right', 'Pagina [page]/[toPage]');
        $pdf->setOption('lowquality', false);
        // $pdf->save("user-content/$newname");

        $file = $user->encodedName() . "/troll.pdf";
        $path = Storage::put($file, $pdf->output());

        // $resource = new Resource;
        // $resource->resource_name = $newname;
        // $resource->file_location = 'user-content/' . $newname;
        // $resource->file_size = filesize('user-content/' . $newname);
        // $resource->user_id = Auth::id();
        // $resource->description = 'Factuurversie';

        // $resource->save();

        // $invoice->resource_id = $resource->id;
    }

    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'id'        => ['required', 'integer'],
            'project'   => ['required', 'integer'],
            'condition' => ['integer'],
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

        // $invoice->invoice_close          = true;
        // $invoice->invoice_code           = $this->invoiceNumber($project->id, $request->user());
        // $invoice->bill_date              = date('Y-m-d H:i:s');
        // $invoice->save();

        $this->saveReport($request->user(), $invoice, $project);

        // $invoice->save();

        // $request->user()->invoice_counter++;
        // $request->user()->save();

        return redirect("project/{$project->id}-{$project->slug()}/invoices");
    }

}
