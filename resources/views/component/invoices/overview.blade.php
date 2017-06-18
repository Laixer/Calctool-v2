@section('component_buttons')
<div class="pull-right">

    <a href="/project/{{ $project->id }}-{{ $project->slug() }}/packingslip" target="new" class="btn btn-primary"><i class="fa fa-file-pdf-o">&nbsp;</i>Pakbon maken</a>
    @if ($offer && $offer->invoices()->where('isclose',true)->where('invoice_close',false)->count() && !$project->project_close)
    <a href="/invoice/new?id={{ $project->id }}&csrf={{ csrf_token() }}" class="btn btn-primary btn"><i class="fa fa-pencil-square-o"></i>Termijnfactuur toevoegen</a>
    @endif

</div>
@endsection

<div class="modal fade" id="asyncModal" tabindex="-1" role="dialog" aria-labelledby="asyncModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th class="col-md-2"></th>
            <th class="col-md-3">Bedrag (Excl. BTW) <a data-toggle="tooltip" data-placement="bottom" data-original-title="Geef hier een termijnbedrag of eindbedrag op." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
            <th class="col-md-3">Factuurnummer <a data-toggle="tooltip" data-placement="bottom" data-original-title="Geef hier uw factuurnummer op dat behoort bij uw boekhouding." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
            <th class="col-md-2">Status <a data-toggle="tooltip" data-placement="bottom" data-original-title="Hier staat de status van uw factuur. Hij is open, te factureren of gefactureerd. Tevens is de PDF te raadplegen en te downloaden." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
            <th class="col-md-2"></th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 0; ?>
        @foreach ($offer->invoices()->orderBy('priority')->orderBy('created_at')->get() as $invoice)
        <?php $i++; ?>
        <tr>
            <td class="col-md-2"><a href="/project/{{ $project->id }}-{{ $project->slug() }}/invoices/detail?id={{ $invoice->id }}">{{ $invoice->name($i) }}</a></td>
            <td class="col-md-3">@money($invoice->amount)</td>
            <td class="col-md-3">{{ $invoice->invoice_code }}</td>
            <td class="col-md-2">{{ $invoice->status() }}</td>
            <td class="col-md-2 text-right">
                
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opties <span class="caret"></span></button>
                    <ul class="dropdown-menu">

                        @if ($invoice->invoice_close)

                        @if (!$invoice->payment_date && !$project->project_close)
                        <li><a href="/inline/description?id={{ $invoice->id }}&package=component.modal" data-toggle="modal" data-target="#asyncModal">Versturen</a></li>

                        <li><a href="/invoice/pay?id={{ $invoice->id }}&csrf={{ csrf_token() }}">Betaald</a></li>
                        @endif
                        <li><a href="/res-{{ $invoice->resource_id }}/download">Download PDF</a></li>
                        @if ($invoice->amount > 0)
                        <li><a href="javascript:void(0);" data-invoice="{{ $invoice->id }}" data-project="{{ $project->id }}" class="docredit">Creditfactuur</a></li>
                        @endif

                        @else

                        @if (!$invoice->isclose)
                        <li><a href="/invoice/delete?id={{ $invoice->id }}&csrf={{ csrf_token() }}">Verwijderen</a></li>
                        @endif

                        @endif

                    </ul>
                </div>
                
            </td>
        </tr>
        @endforeach
        @if (!$i)
        <tr>
            <td colspan="5" style="text-align: center;">Er zijn nog geen facturen gemaakt</td>
        </tr>
        @endif
    </tbody>
</table>
