@inject('calculus', 'BynqIO\Dynq\Calculus\CalculationEndresult')
@inject('carbon', 'Carbon\Carbon')

@push('style')
<link media="all" type="text/css" rel="stylesheet" href="/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
@endpush

<div class="modal fade" id="asyncModal" tabindex="-1" role="dialog" aria-labelledby="asyncModal" aria-hidden="true">
    <div class="modal-dialog {{-- modal-lg --}} {{-- modal-sm --}}">
        <div class="modal-content"></div>
    </div>
</div>

@if ($offer_last)
@if (number_format($calculus::totalProject($project), 3, ",",".") != number_format($offer_last->offer_total, 3, ",","."))
<div class="alert alert-warning">
    <i class="fa fa-fa fa-info-circle"></i> Gegevens zijn gewijzigd ten op zichte van de laaste offerte
</div>
@endif
@endif

@if ($offer_last && !$offer_last->offer_finish)
<div class="alert alert-info">
    <i class="fa fa-fa fa-info-circle"></i> Zend na aanpassing van de calculatie een nieuwe offerte naar uw opdrachtgever.
</div>
@endif

@section('component_buttons')
<div class="pull-right">

    @if ($offer_last && !$offer_last->offer_finish && !$project->project_close)
    <div class="btn-group">
        <a href="/inline/offer_send?project_name={{ $project->project_name }}&user={{ urlencode($offer_last->fromContact->getFormalName()) }}&package=mail" data-toggle="modal" data-target="#asyncModal" class="btn btn-primary"><i class="fa fa-paper-plane-o" style="padding-right:5px">&nbsp;</i>Versturen</a>
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="caret"></span>
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="/inline/confirm?id={{ $offer_last->id }}&project={{ $project->id }}&package=component.modal" data-toggle="modal" data-target="#asyncModal" data-toggle="modal"><i class="fa fa-check-square-o">&nbsp;</i>Opdracht Bevestigen</a></li>
        </ul>
    </div>
    @endif

    @if (!($offer_last && $offer_last->offer_finish) && !$project->project_close)
    <a href="/project/{{ $project->id }}-{{ $project->slug() }}/quotations/new?ts={{ time() }}&pretext=Bij+deze+doe+ik+u+toekomen+mijn+prijsopgaaf+betreffende+het+uit+te+voeren+werk.+Onderstaand+zal+ik+het+werk+en+de+uit+te+voeren+werkzaamheden+specificeren+zoals+afgesproken.&posttext=Hopende+u+hiermee+een+passende+aanbieding+gedaan+te+hebben%2C+zie+ik+uw+reactie+met+genoegen+tegemoet.&terms=1&contact_to={{ $project->client->contacts->first()->id }}&contact_from={{ Auth::user()->ownCompany->contacts->first()->id }}" class="btn btn-primary btn"><i class="fa fa-pencil-square-o"></i>Nieuwe Offerte</a>
    @endif

</div>
@endsection

<table class="table table-striped">
    <thead>
        <tr>
            <th class="col-md-4">Offertenummer</th>
            <th class="col-md-3">Datum</th>
            <th class="col-md-3">Offertebedrag (excl. BTW)</th>
            <th class="col-md-3"></th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 0; ?>
        @foreach($project->quotations()->orderBy('created_at')->get() as $offer)
        <?php $i++; ?>
        <tr>
            <td class="col-md-4"><a href="/project/{{ $project->id }}-{{ $project->slug() }}/quotations/detail?id={{ $offer->id }}">{{ $offer->offer_code }}</a> @if ($offer->offer_finish)<span class="label label-default">Definitief</span>@endif</td>
            <td class="col-md-3">{{ $carbon::parse($offer->offer_make)->toDateString() }}</td>
            <td class="col-md-3">@money($offer->offer_total)</td>
            <td class="col-md-3 text-right"><a href="/resource/{{ $offer->resource_id }}/download/quotation.pdf" class="btn btn-primary btn-xs"><i class="fa fa-download fa-fw"></i> Downloaden</a></td>
        </tr>
        @endforeach
        @if (!$i)
        <tr>
            <td colspan="4" style="text-align: center;">Er zijn nog geen offertes gemaakt</td>
        </tr>
        @endif
    </tbody>
</table>
