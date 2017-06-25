@inject('carbon', 'Carbon\Carbon')

@extends('layout.master')

@section('content')
<div id="wrapper" ng-app="projectApp">

    <section class="container">

        <div class="col-md-12">

            <ol class="breadcrumb">
                <li><a href="/">Dashboard</a></li>
                <li class="active">Projecten</li>
            </ol>

            <div>

                <div class="pull-right">
                    <!--<button type="button" class="btn btn-primary">Geavanceerde Filters</button>-->
                    <div class="btn-group">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Snelle Filters
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url()->current() }}">Alle Projecten</a></li>
                            <li><a href="?status=open">Open Projecten</a></li>
                            <li><a href="?status=closed">Gesloten Projecten</a></li>
                            <li><a href="?updated=after:{{ $carbon::now()->subDays(2)->toDateString() }}">Recente Bewerkt</a></li>
                            <li class="divider" style="margin:5px 0;"></li>
                            <li><a href="?type=calculatie">Projecttype Calculatie</a></li>
                            <li><a href="?type=regie">Projecttype Regiewerk</a></li>
                            <li><a href="?type=snelle offerte en factuur">Projecttype Snelle offerte</a></li>
                            <li class="divider" style="margin:5px 0;"></li>
                            <li><a href="?sort=name:asc">Sorteer op Projectnaam</a></li>
                            <li><a href="?sort=client:asc">Sorteer op Opdrachtgever</a></li>
                        </ul>
                    </div>
                </div>

                <h2><strong>Projecten</strong></h2>
                <div class="white-row">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="col-md-3">Projectnaam</th>
                                <th class="col-md-3">Opdrachtgever</th>
                                <th class="col-md-2">Type</th>
                                <th class="col-md-2">Plaats</th>
                                <th class="col-md-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
                            <tr>
                                <td class="col-md-3"><a href="/project/{{ $project->id }}-{{ $project->slug() }}/details">{{ $project->project_name }}</a></td>
                                <td class="col-md-3">{{ $project->client->name() }}</td>
                                <td class="col-md-2">{{ ucfirst($project->type->type_name) }}</td>
                                <td class="col-md-2">{{ $project->address_city }}</td>
                                <td class="col-md-2">{{ ucfirst($project->status()) }}</td>
                            </tr>
                            @endforeach
                            @empty($projects)
                            <tr>
                                <td colspan="6" style="text-align: center;">Geen projecten beschikbaar</td>
                            </tr>
                            @endempty
                        </tbody>
                    </table>
                </div>

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

            </div>
        </div>

    </section>

</div>
@stop
