@inject('agent', 'Jenssegers\Agent\Agent')

@extends('layout.master')

@section('title', __('core.dashboard'))

@push('scripts')
<script src="/components/angular/angular.min.js"></script>
@endpush

@section('content')
<div id="wrapper">

    <div id="shop">
        <section class="container">

            @if ($systemMessage && $systemMessage->level == 1)
            <div class="alert alert-warning">
                <i class="fa fa-fa fa-info-circle"></i>
                {{ $systemMessage->content }}
            </div>
            @elseif ($systemMessage && $systemMessage->level > 1)
            <div class="alert alert-danger">
                <i class="fa fa-warning"></i>
                <strong>{{ $systemMessage->content }}</strong>
            </div>
            @endif

            @include('layout.message')

            @if ($agent->isMobile())
            <div class="alert alert-warning">
                <i class="fa fa-warning"></i>
                <strong>@lang('core.mobilewarning')</strong>
            </div>
            @endif

            @if (Auth::user()->isNewPeriod())
            <div class="pull-right" style="margin: 10px 0 20px 0">
                <a href="/support/gethelp" class="btn btn-default hidden-sm hidden-xs" type="button"><i class="fa fa-support"></i>@lang('core.needhelp')</a>
            </div>
            @endif

            <h2 style="margin: 10px 0 20px 0;"><strong>{{ $welcomeMessage }}</strong> {{ Auth::user()->firstname }}</h2>
            
            <div class="row">
                @include('dashboard.widgets')
            </div>

            <div class="row">

                <div id="wrapper" ng-app="projectApp" class="nopadding-top">

                    <div class="col-md-12">
                        <br>
                        @if ($projectCount)
                        <h2><strong>{{ trans_choice('core.project', 2) }}</strong></h2>

                        <div class="white-row" ng-controller="projectController">

                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <div class="input-group">
                                    <input type="text" class="form-control" ng-model="query" placeholder="Zoek in projecten...">
                                    <div class="input-group-btn">
                                        <a href="/project/new" class="btn btn-primary" type="button"><i class="fa fa-file"></i> @lang('core.new') {{ trans_choice('core.project', 1) }}</a>
                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li><a href="/project/all">Alle Projecten</a></li>
                                        </ul> </div>
                                    </div>
                                </div>
                            </div>

                            <table class="ng-cloak table table-striped">
                                <thead>
                                    <tr>
                                        <th class="col-md-5" ng-click="orderByField='project_name'; reverseSort = !reverseSort">@lang('core.projectname')</th>
                                        <th class="col-md-3" ng-click="orderByField='relation'; reverseSort = !reverseSort">@lang('core.customer')</th>
                                        <th class="col-md-2 hidden-sm hidden-xs" ng-click="orderByField='type_name'; reverseSort = !reverseSort">@lang('core.type')</th>
                                        <th class="col-md-2 hidden-xs" ng-click="orderByField='address_city'; reverseSort = !reverseSort">@lang('core.city')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="project in projects | filter: query | orderBy: orderByField:reverseSort as results">
                                        <td class="col-md-5"><a href="/project/@{{ project.id }}-@{{ project.project_name | strReplace:' ':'-' }}/details">@{{ project.project_name }}</a></td>
                                        <td class="col-md-3">@{{ project.relation }}</td>
                                        <td class="col-md-2 hidden-sm hidden-xs">@{{ project.type.type_name | capitalize }}</td>
                                        <td class="col-md-2 hidden-xs">@{{ project.address_city }}</td>
                                    </tr>
                                    <tr ng-show="results == 0">
                                        <td colspan="6" style="text-align: center;">@lang('core.noprodavail')</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="btn-group item-full">
                                        <button class="btn btn-primary" name="toggle-close"><i class="fa fa-close"></i> @lang('core.closed') {{ trans_choice('core.project', 2) }}</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            @else
                            <h2><strong>@lang('core.firststep')</strong></h2>
                            <div class="bs-callout text-center whiteBg" style="margin:0">
                                <h3>			
                                    <a href="/" class="btn btn-primary btn-lg" class="fa fa-youtube-play yt-vid">@lang('core.watchwelcvid')</a>
                                        of
                                    <a href="/project/new" class="btn btn-primary btn-lg">@lang('core.crefirstprod') <i class="fa fa-arrow-right"></i></a>
                                </h3>
                            </div>

                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div> 

</div>
<script type="text/javascript">
    $(document).ready(function() {
        angular.module('projectApp', []).controller('projectController', function($scope, $http) {
            $http.get('/api/v1/projects').then(function(response){
                $scope._projects = response.data;
                $scope.filter_close = false;
                $scope.projects = [];
                angular.forEach($scope._projects, function(value, key) {
                    if (value.project_close == null) {
                        $scope.projects.push(value);
                    }
                });
            });
            $("[name='toggle-close']").click(function() {
                if ($scope.filter_close) {
                    $scope.projects = [];
                    angular.forEach($scope._projects, function(value, key) {
                        if (value.project_close == null) {
                            $scope.projects.push(value);
                        }
                    });
                    $scope.$apply();
                    $scope.filter_close = false;
                    $("[name='toggle-close']").html('<i class="fa fa-close"></i> @lang('core.closed') {{ trans_choice('core.project', 2) }}');
                } else {
                    $scope.projects = [];
                    angular.forEach($scope._projects, function(value, key) {
                        if (value.project_close != null) {
                            $scope.projects.push(value);
                        }
                    });
                    $scope.$apply();
                    $scope.filter_close = true;
                    $("[name='toggle-close']").html('<i class="fa fa-folder-open" aria-hidden="true"></i> @lang('core.open') {{ trans_choice('core.project', 2) }}');
                }
            });

        }).filter('capitalize', function() {
            return function(input) {
                return (!!input) ? input.charAt(0).toUpperCase() + input.substr(1).toLowerCase() : '';
            }
        }).filter('strReplace', function () {
            return function (input, from, to) {
                input = input || '';
                from = from || '';
                to = to || '';
                return input.replace(new RegExp(from, 'g'), to);
            };
        });
    });
</script>
@stop
