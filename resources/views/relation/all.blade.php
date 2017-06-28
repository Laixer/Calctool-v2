@inject('carbon', 'Carbon\Carbon')

@extends('layout.master')

@section('content')
<div id="wrapper" ng-app="projectApp">

    <section class="container">

        <div class="col-md-12">

            <ol class="breadcrumb">
                <li><a href="/">Dashboard</a></li>
                <li class="active">Relaties</li>
            </ol>

            <div>

                <div class="pull-right">
                    <div class="btn-group">
                        <a href="/relation/new" class="btn btn-primary"><i class="fa fa-file" aria-hidden="true"></i>Nieuwe relatie</a>
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="/relation/import" type="button"><i class="fa fa-upload"></i> Importeer</a></li>
                            <li><a href="/relation/export" type="button"><i class="fa fa-download"></i> Exporteer</a></li>
                        </ul>
                    </div>
                </div>

                <h2><strong>Relaties</strong></h2>
                <div class="white-row">

                    <div class="row">
                        <div class="form-group col-lg-12">
                            <div class="input-group">
                                <input type="text" class="form-control" ng-model="query" placeholder="Zoek in relaties op naam, nummer, adres of type">

                                <div class="input-group-btn">
                                    <button class="btn btn-primary btn-primary-activity"><i class="fa fa-search"></i> Zoeken</button>
                                    <button type="button" class="btn btn-primary dropdown-toggle" style="padding-right: 8px;padding-left: 8px;" data-toggle="dropdown" aria-expanded="false">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="{{ url()->current() }}">Alle Relaties</a></li>
                                        <li><a href="?status=open">Veel Gebruikt</a></li>
                                        <li><a href="?updated=after:{{ $carbon::now()->subDays(2)->toDateString() }}">Recente Bewerkt</a></li>
                                        <li class="divider" style="margin:5px 0;"></li>
                                        <li><a href="?type=calculatie">Relatietype Zakelijk</a></li>
                                        <li><a href="?type=regie">Relatietype Particulier</a></li>
                                        <li class="divider" style="margin:5px 0;"></li>
                                        <li><a href="?sort=name:asc">Sorteer op Relatienaam</a></li>
                                        <li><a href="?sort=client:asc">Sorteer op Plaats</a></li>
                                    </ul>
                                </div>


                            </div>
                        </div>
                    </div>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="col-md-2">Debiteur</th>
                                <th class="col-md-4">Relatienaam</th>
                                <th class="col-md-2">Type</th>
                                <th class="col-md-2">Telefoon</th>
                                <th class="col-md-2">Plaats</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($relations as $relation)
                            <tr>
                                <td class="col-md-2"><a href="/relation/{{ $relation->id }}-{{ $relation->slug() }}/details">{{ $relation->debtor_code }}</a></td>
                                <td class="col-md-4"><a href="/relation/{{ $relation->id }}-{{ $relation->slug() }}/details">{{ $relation->name() }}</a></td>
                                <td class="col-md-2">{{ ucfirst($relation->isBusiness() ? 'Zakelijk' : 'Particulier') }}</td>
                                <td class="col-md-2">{{ $relation->phone }}</td>
                                <td class="col-md-2">{{ $relation->address_city }}</td>
                            </tr>
                            @endforeach
                            @empty($relations)
                            <tr>
                                <td colspan="6" style="text-align: center;">Geen relaties beschikbaar</td>
                            </tr>
                            @endempty
                        </tbody>
                    </table>
                </div>

                {{-- TODO --}}
                @if (Cookie::has('beta'))
                <div class="text-center">
                    <ul class="pagination">
                        <li><a href="#">&laquo;</a></li>
                        <li><a href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">5</a></li>
                        <li><a href="#">&raquo;</a></li>
                    </ul>
                </div>
                @endif
                {{-- /TODO --}}

            </div>
        </div>

    </section>

</div>
@stop
