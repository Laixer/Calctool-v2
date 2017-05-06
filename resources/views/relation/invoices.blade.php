@extends('relation.layout', ['page' => 'invoices'])

@section('relation_section_name', 'Facturen')

@section('relation_content')

<?php
use \BynqIO\Dynq\Models\Relation;
use \BynqIO\Dynq\Models\Invoice;
use \BynqIO\Dynq\Models\Offer;
use \BynqIO\Dynq\Models\Project;
$relation = Relation::find(Route::Input('relation_id'));
?>

<div class="white-row">
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="col-md-2">Factuur</th>
                <th class="col-md-2">Project</th>
                <th class="col-md-2">Bedrag</th>
                <th class="col-md-2">Datum</th>
                <th class="col-md-2"></th>
                <th class="col-md-2">Status</th>
            </tr>
        </thead>

        <tbody>
            <?php $i = 0; ?>
            @foreach (Project::where('user_id','=', Auth::id())->where('client_id',$relation->id)->orderBy('created_at','desc')->get() as $project)
            @foreach (Offer::where('project_id','=', $project->id)->orderBy('created_at','desc')->get() as $offer)
            @foreach (Invoice::where('offer_id','=', $offer->id)->whereNotNUll('bill_date')->orderBy('created_at','desc')->get() as $invoice)
            <?php $i++; ?>
            <tr>
                <td class="col-md-2"><a href="/invoice/project-{{ $project->id }}/pdf-invoice-{{ $invoice->id }}">{{ $invoice->invoice_code }}</a></td>
                <td class="col-md-2">{{ $project->project_name }}</td>
                <td class="col-md-2">{!! '&euro;&nbsp;'.number_format($invoice->amount, 2, ",",".") !!}</td>
                <td class="col-md-2">{{ date('d-m-Y', strtotime(DB::table('invoice')->select('created_at')->where('id','=',$invoice->id)->get()[0]->created_at)) }}</td>
                <td class="col-md-2">{{--  --}}</td>
                <td class="col-md-2">{{ $invoice->payment_date ? 'Betaald' : 'Gefactureerd' }}</td>
            </tr>
            @endforeach
            @endforeach
            @endforeach

            @if (!$i)
            <tr>
                <td colspan="6"><center>Geen facturen bij relatie</center></td>
            </td>
            @endif
        </tbody>
    </table>
</div>
@endsection
