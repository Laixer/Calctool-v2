@extends('layout.master')

@section('title', __('core.dashboard'))

@push('scripts')
<script src="/components/angular/angular.min.js"></script>
@endpush

@section('content')
<div class="modal fade" id="myYouTube" tabindex="-1" role="dialog" aria-labelledby="mYouTubeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <iframe width="1280" height="720" src="https://www.youtube.com/embed/xPmVzvmuFvY" frameborder="0" allowfullscreen></iframe>

        </div>
    </div>
</div>

<div class="modal fade" id="myYouTube2" tabindex="-1" role="dialog" aria-labelledby="mYouTubeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <iframe width="1280" height="720" src="https://www.youtube.com/embed/8YevS5CHoMA" frameborder="0" allowfullscreen></iframe>

        </div>
    </div>
</div>

<div class="modal fade" id="myYouTube3" tabindex="-1" role="dialog" aria-labelledby="mYouTubeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <iframe width="1280" height="720" src="https://www.youtube.com/embed/JGj8iyxZXH0" frameborder="0" allowfullscreen></iframe>

        </div>
    </div>
</div>

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

            @if (Auth::user()->isNewPeriod())
            <div class="pull-right" style="margin: 10px 0 20px 0">
                <a href="/support/gethelp" class="btn btn-default hidden-sm hidden-xs" type="button"><i class="fa fa-support"></i>@lang('core.needhelp')</a>
            </div>
            @endif

            <h2 style="margin: 10px 0 20px 0;"><strong>{{ $welcomeMessage }}</strong> {{ Auth::user()->firstname }}&nbsp;<a class="fa fa-youtube-play yt-vid" href="javascript:void(0);" data-toggle="modal" data-target="#myYouTube"></a></h2>
            
            <div class="row">

                <div class="col-sm-6 col-md-2">
                    <div class="item-box item-box-show fixed-box">
                        <figure>
                            <a class="item-hover" href="/company">
                                <span class="overlay color2"></span>
                                <span class="inner" style="top:40%;">
                                    <span class="block fa fa-home fsize60"></span>
                                </span>
                            </a>
                            <a href="/company" class="btn btn-primary add_to_cart"><strong> @lang('core.companyinfo')</strong></a>

                        </figure>
                    </div>
                </div>

                @if ($projectCount)
                <div class="col-sm-6 col-md-2">
                    <div class="item-box item-box-show fixed-box">
                        <figure>
                            <a class="item-hover" href="/material">
                                <span class="overlay color2"></span>
                                <span class="inner" style="top:40%;">
                                    <span class="block fa fa-wrench fsize60"></span>
                                </span>
                            </a>
                            <a href="/material" class="btn btn-primary add_to_cart"><strong> @lang('core.products')</strong></a>
                        </figure>
                    </div>
                </div>

                <div class="col-sm-6 col-md-2">
                    <div class="item-box item-box-show fixed-box">
                        <figure>
                            <a class="item-hover" href="/timesheet">
                                <span class="overlay color2"></span>
                                <span class="inner" style="top:40%;">
                                    <span class="block fa fa-clock-o fsize60"></span>
                                </span>
                            </a>
                            <a href="/timesheet" class="btn btn-primary add_to_cart"><strong> @lang('core.timesheet')</strong></a>
                        </figure>
                    </div>
                </div>

                <div class="col-sm-6 col-md-2">
                    <div class="item-box item-box-show fixed-box">
                        <figure>
                            <a class="item-hover" href="/purchase">
                                <span class="overlay color2"></span>
                                <span class="inner" style="top:40%;">
                                    <span class="block fa fa-shopping-cart fsize60"></span>
                                </span>
                            </a>
                            <a href="/purchase" class="btn btn-primary add_to_cart"><strong> @lang('core.purchaseinvoice')</strong></a>
                        </figure>
                    </div>
                </div>

                <div class="col-sm-6 col-md-2 hidden-xs">
                    <div class="item-box item-box-show fixed-box">
                        <figure>
                            <a class="item-hover" href="/finance/overview">
                                <span class="overlay color2"></span>
                                <span class="inner" style="top:40%;">
                                    <span class="block fa fa-usd fsize60"></span>
                                </span>
                            </a>
                            <a href="/finance/overview" class="btn btn-primary add_to_cart"><strong> @lang('core.financial')</strong></a>
                        </figure>
                    </div>
                </div>
                @else
                <div class="col-sm-6 col-md-2">
                    <div class="item-box item-box-show fixed-box">
                        <figure>
                            <a class="item-hover" style="cursor: default;" href="javascript:void(0);">
                                <span class="overlay color2" style="background: #9E9E9E !important"></span>
                                <span class="inner" style="top:40%;">
                                    <span class="block fa fa-wrench fsize60"></span>
                                </span>
                            </a>
                            <a href="javascript:void(0);" style="cursor: default;" class="btn btn-primary add_to_cart"><strong> @lang('core.products')</strong></a>
                        </figure>
                    </div>
                </div>

                <div class="col-sm-6 col-md-2">
                    <div class="item-box item-box-show fixed-box">
                        <figure>
                            <a class="item-hover" style="cursor: default;" href="javascript:void(0);">
                                <span class="overlay color2" style="background: #9E9E9E !important"></span>
                                <span class="inner" style="top:40%;">
                                    <span class="block fa fa-clock-o fsize60"></span>
                                </span>
                            </a>
                            <a href="javascript:void(0);" style="cursor: default;" class="btn btn-primary add_to_cart"><strong> @lang('core.timesheet')</strong></a>
                        </figure>
                    </div>
                </div>

                <div class="col-sm-6 col-md-2">
                    <div class="item-box item-box-show fixed-box">
                        <figure>
                            <a class="item-hover" style="cursor: default;" href="javascript:void(0);">
                                <span class="overlay color2" style="background: #9E9E9E !important"></span>
                                <span class="inner" style="top:40%;">
                                    <span class="block fa fa-shopping-cart fsize60"></span>
                                </span>
                            </a>
                            <a href="javascript:void(0);" style="cursor: default;" class="btn btn-primary add_to_cart"><strong> @lang('core.purchaseinvoice')</strong></a>
                        </figure>
                    </div>
                </div>

                <div class="col-sm-6 col-md-2 hidden-xs">
                    <div class="item-box item-box-show fixed-box">
                        <figure>
                            <a class="item-hover" style="cursor: default;" href="javascript:void(0);">
                                <span class="overlay color2" style="background: #9E9E9E !important"></span>
                                <span class="inner" style="top:40%;">
                                    <span class="block fa fa-usd fsize60"></span>
                                </span>
                            </a>
                            <a href="javascript:void(0);" style="cursor: default;" class="btn btn-primary add_to_cart"><strong> @lang('core.financial')</strong></a>
                        </figure>
                    </div>
                </div>
                @endif

                <div class="col-sm-6 col-md-2">
                    <div class="item-box item-box-show fixed-box">
                        <figure>
                            <a class="item-hover" href="/relation">
                                <span class="overlay color2"></span>
                                <span class="inner" style="top:40%;">
                                    <span class="block fa fa-users fsize60"></span>
                                </span>
                            </a>
                            <a href="/relation" class="btn btn-primary add_to_cart"><strong> {{ trans_choice('core.relation', 2) }}</strong></a>
                        </figure>
                    </div>
                </div>
                
            </div>

            <div class="row">

                <div id="wrapper" ng-app="projectApp" class="nopadding-top">

                    <div class="col-md-12">
                        <br>
                        @if ($projectCount)
                        <h2><strong>{{ trans_choice('core.project', 2) }}</strong>&nbsp;&nbsp;<a class="fa fa-youtube-play yt-vid" href="javascript:void(0);" data-toggle="modal" data-target="#myYouTube2"></a></h2>

                        <div class="white-row" ng-controller="projectController">
                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" ng-model="query" placeholder="Zoek in projecten...">
                                        <span class="input-group-btn">
                                            <a href="/project/new" class="btn btn-primary" type="button"><i class="fa fa-file"></i> @lang('core.new') {{ trans_choice('core.project', 1) }}</a>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <table ng-cloak class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="col-md-5" ng-click="orderByField='project_name'; reverseSort = !reverseSort">@lang('core.projectname')</th>
                                        <th class="col-md-3" ng-click="orderByField='relation'; reverseSort = !reverseSort">@lang('core.customer')</th>
                                        <th class="col-md-2 hidden-sm hidden-xs" ng-click="orderByField='type_name'; reverseSort = !reverseSort">@lang('core.type')</th>
                                        <th class="col-md-2 hidden-xs" ng-click="orderByField='address_city'; reverseSort = !reverseSort">@lang('core.city')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <div ng-show="show" class="row text-center">
                                        <img src="/images/loading_icon.gif" height="100" />
                                    </div>
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
                                    <a href="javascript:void(0);" class="btn btn-primary btn-lg" class="fa fa-youtube-play yt-vid" data-toggle="modal" data-target="#myYouTube3">@lang('core.watchwelcvid')</a>
                                        of
                                    <a href="/project/new" class="btn btn-primary btn-lg">@lang('core.crefirstprod') <i class="fa fa-arrow-right"></i></a>
                                </h3>
                            </div>

                            @endif
                        </div>

                    </div>
                </div>
            </div>
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
