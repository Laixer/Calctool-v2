{{--
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
--}}

<?php

use BynqIO\Dynq\Models\Part;
use BynqIO\Dynq\Models\Supplier; //Modal
use BynqIO\Dynq\Models\Wholesale; //Modal
use BynqIO\Dynq\Models\Product; //Modal
use BynqIO\Dynq\Models\ProductSubGroup; //Modal
use BynqIO\Dynq\Models\ProductGroup; //Modal
use BynqIO\Dynq\Models\ProductSubCategory; //Modal
use BynqIO\Dynq\Models\FavoriteActivity; //Modal
use BynqIO\Dynq\Calculus\CalculationRegister;

?>
@inject('tax', 'BynqIO\Dynq\Models\Tax')

@push('style')
<link media="all" type="text/css" rel="stylesheet" href="/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
@endpush

@push('scripts')
<script src="/plugins/jquery.number.min.js"></script>
<script src="/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
@endpush

@push('jsinline')
<script type="text/javascript">
$(document).ready(function() {

    /* Convert string to float */
    function parseNumber(number) {
        return parseFloat($.number(number, 3, '.', ''));//TODO
    }

    /* Convert and format string */
    function convertNumber(number) {
        return $.number({!! \BynqIO\Dynq\Services\FormatService::monetaryJS('number') !!});//TODO
    }

    /* Save toggle state to session */
    $('.toggle-{{ $section }}').click(function(e){
        $id = $(this).attr('id');
        if ($(this).hasClass('active')) {
            if (sessionStorage.toggleOpen{{ $component . $section . Auth::id() }}) {
                $toggleOpen = JSON.parse(sessionStorage.toggleOpen{{ $component . $section . Auth::id() }});
            } else {
                $toggleOpen = [];
            }

            if (!$toggleOpen.length) {
                $toggleOpen.push($id);
            }

            for (var i in $toggleOpen) {
                if ($toggleOpen.indexOf($id) == -1) {
                    $toggleOpen.push($id);
                }
            }

            sessionStorage.toggleOpen{{ $component . $section . Auth::id() }} = JSON.stringify($toggleOpen);
        } else {
            $tmpOpen = [];
            if (sessionStorage.toggleOpen{{ $component . $section . Auth::id() }}){
                $toggleOpen = JSON.parse(sessionStorage.toggleOpen{{ $component . $section . Auth::id() }});
                for (var i in $toggleOpen){
                    if ($toggleOpen[i] != $id) {
                        $tmpOpen.push($toggleOpen[i]);
                    }
                }
            }

            sessionStorage.toggleOpen{{ $component . $section . Auth::id() }} = JSON.stringify($tmpOpen);
        }
    });
    if (sessionStorage.toggleOpen{{ $component . $section . Auth::id() }}){
        $toggleOpen = JSON.parse(sessionStorage.toggleOpen{{ $component . $section . Auth::id() }});
        for (var i in $toggleOpen) {
            $('#' + $toggleOpen[i]).addClass('active').children('.toggle-content').toggle();
        }
    }

    /* Auto format numbers */
    $(".form-control-sm-number").number({!! \BynqIO\Dynq\Services\FormatService::monetaryJS('true') !!});

    $('[name=date]').datepicker({format: '{{ \BynqIO\Dynq\Services\FormatService::dateFormatJS() }}'});

    /* Append new rows */
    $("body").on("blur", ".newrow", function() {
        var i = 1;
        if ($(this).val()) {
            if (!$(this).closest("tr").next().length) {
                var $curTable = $(this).closest("table");
                $curTable.find("tr:eq(1)").clone().removeAttr("data-id").find("input").each(function() {
                    $(this).val("").removeClass("error-input").attr("id", function(_, id) { return id + i });
                }).end().find(".total-row, .total-row-profit").text("").end().find(".form-control-sm-number").each(function() {
                    $(this).number({!! \BynqIO\Dynq\Services\FormatService::monetaryJS('true') !!});
                }).end().find("[name=date]").datepicker({format: '{{ \BynqIO\Dynq\Services\FormatService::dateFormatJS() }}'})
                .end().find("[name=delete]").change(delete_row).end().appendTo($curTable);
                $("button[data-target='#myModal']").on("click", function() {
                    $newinputtr = $(this).closest("tr");
                    $newinputtr2 = $(this).closest("tr");
                });
                i++;
            }
        }
    });

    /* Bind save triggers */
    $("body").on("change", "[name=name]",    save_row);
    $("body").on("change", "[name=unit]",    save_row);
    $("body").on("change", "[name=rate]",    save_row);
    $("body").on("change", "[name=date]",    save_row);
    $("body").on("change", "[name=amount]",  save_row);
    $("body").on("click",  "[name=delete]",  delete_row);
    $("body").on("click",  "[name=reset]",   reset_row);
    $("body").on("change", "[name=tax]",     save_tax);

    function save_tax() {
        $.post('/project/layer/tax', {
            value:     $(this).val(),
            layer:     $(this).attr("data-layer"),
            activity:  $(this).attr("data-id"),
            project:   {{ $project->id }},
        });
    }

    function delete_row() {
        $row         = $(this).closest("tr");
        $uri_delete  = '{{ "/$component/delete" }}';

        $.post($uri_delete, {
            id:        $row.attr("data-id"),
            layer:     $row.closest("table").attr("data-layer"),
            activity:  $row.closest("table").attr("data-id"),
            project:   {{ $project->id }},
        }, function(data) { if (data.success) { $row.remove() } });
    }

    function reset_row() {
        $row         = $(this).closest("tr");
        $uri_reset   = '{{ "/$component/reset" }}';

        $.post($uri_reset, {
            id:        $row.attr("data-id"),
            layer:     $row.closest("table").attr("data-layer"),
            activity:  $row.closest("table").attr("data-id"),
            project:   {{ $project->id }},
        }, function(data) {
            if (data.success) {
                if (data.name) { $row.find("input[name='name']").val(data.name); }
                if (data.unit) { $row.find("input[name='unit']").val(data.unit); }
                if (data.rate) { $row.find("input[name='rate']").val(data.rate); }
                if (data.amount) { $row.find("input[name='amount']").val(data.amount); }
             }
        });
    }

    function save_row() {
        $row         = $(this).closest("tr");
        $uri_new     = '{{ "/$component/new" }}';
        $uri_update  = '{{ "/$component/update" }}';

        if ($row.attr("data-id")) {
            submit_to_backend($row, $uri_update);
        } else {
            var flag = true;
            $row.find("input").each(function() {
                if (!$(this).val()) {
                    flag = false;
                }
            });

            if (flag) {
                submit_to_backend($row, $uri_new);
            }
        }
    }

    function submit_to_backend($tr, $uri) {
        if ($tr.closest("table").attr("data-layer") == undefined) {
            return;
        }

        $.post($uri, {
            id:        $tr.attr("data-id"),
            name:      $tr.find("input[name='name']").val(),
            unit:      $tr.find("input[name='unit']").val(),
            rate:      $tr.find("input[name='rate']").val(),
            date:      $tr.find("input[name='date']").val(),
            amount:    $tr.find("input[name='amount']").val(),
            layer:     $tr.closest("table").attr("data-layer"),
            activity:  $tr.closest("table").attr("data-id"),
            project:   {{ $project->id }},
        }, function(data) { if (data.success) { save_callback($tr, data); } });
    }

    /* Update row and total amounts */
    function save_callback($tr, data) {
        if (data.success) {
            $tr.attr("data-id", data.id);

            if (data.amount) {
                $tr.find(".total-row").html('{{ LOCALE_CURRENCY }} ' + convertNumber(data.amount));
            }

            if (data.amount_incl) {
                $tr.find(".total-row-profit").html('{{ LOCALE_CURRENCY }} ' + convertNumber(data.amount_incl));
            }

            if (data.total) {
                $tr.closest("table").find(".subtotal").html('{{ LOCALE_CURRENCY }} ' + convertNumber(data.total));
            }

            if (data.total) {
                $tr.closest("table").find(".subtotal").html('{{ LOCALE_CURRENCY }} ' + convertNumber(data.total));
            }

            if (data.total_profit) {
                $tr.closest("table").find(".subtotal_profit").html('{{ LOCALE_CURRENCY }} ' + convertNumber(data.total_profit));
            }
        }
    }

    /* Remove contents from modal on close */
    $(document).on('hidden.bs.modal', function (e) {
        $(e.target).removeData('bs.modal');
    });

});
</script>
@endpush

{{-- TODO: move into module --}}
<?php /*
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Producten</h4>
            </div>

            <div class="modal-body">

                <div class="form-group input-group-lg">

                    <div class="row">
                        <div class="col-md-4">
                            <select id="wholesale" class="getsub form-control" style="background-color: #E5E7E9; color:#000;">
                                <?php
                                $mysupplier = Supplier::where('user_id', Auth::id())->first();
                                if ($mysupplier) {
                                ?>
                                <option value="{{ $mysupplier->id }}">Mijn Materiaal</option>
                                <?php } ?>

                                @foreach (Wholesale::all() as $wholesale)
                                <?php
                                $supplier = Supplier::where('wholesale_id', $wholesale->id)->first();
                                if (!$supplier)
                                    continue;
                                $cnt = Product::where('supplier_id', $supplier->id)->limit(1)->count();
                                if (!$cnt)
                                    continue;
                                ?>
                                <option {{ $wholesale->company_name=='Bouwmaat NL' ? 'selected' : '' }} value="{{ $supplier->id }}">{{ $wholesale->company_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <select id="group2" class="getsub form-control" style="background-color: #E5E7E9; color:#000;">
                                <option value="0" selected>Selecteer Categorie</option>
                                @foreach (ProductGroup::all() as $group)
                                <option data-name="group" value="{{ $group->id }}">{{ $group->group_name }}</option>
                                @foreach (ProductCategory::where('group_id', $group->id)->get() as $cat)
                                <option data-name="cat" value="{{ $cat->id }}"> - {{ $cat->category_name }}</option>
                                @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select id="group" class="form-control" style="background-color: #E5E7E9; color:#000">
                                <option value="0" selected>Selecteer Subcategorie</option>
                                @foreach (ProductSubCategory::all() as $subcat)
                                <option value="{{ $subcat->id }}">{{ $subcat->sub_category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>

                <div class="form-group">
                      <input type="text" maxlength="100" id="search" value="" class="form-control" placeholder="Zoek in alle producten">
                </div>

                <div class="table-responsive">
                    <table id="tbl-material" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Omschrijving</th>
                                <th>Eenheid</th>
                                <th>Prijs per eenheid</th>
                                <th>Totaalprijs</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times">&nbsp;</i>Sluiten</button>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Favorieten producten</h4>
            </div>

            <div class="modal-body">

                <div class="table-responsive">
                    <table id="tbl-material2" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Omschrijving</th>
                                <th>Eenheid</th>
                                <th>Prijs per eenheid</th>
                                <th>Totaalprijs</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (Auth::user()->productFavorite()->get() as $product)
                            <tr>
                                <td><a data-name="{{ $product->description }}" data-unit="{{ $product->unit }}" data-price="{{ number_format($product->price, 2,",",".") }}" href="javascript:void(0);">{{ $product->description }}</a></td>
                                <td>{{ $product->unit }}</td>
                                <td>{{ number_format($product->price, 2,",",".") }}</td>
                                <td>&euro;&nbsp;{{ number_format($product->total_price, 2,",",".") }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times">&nbsp;</i>Sluiten</button>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="myFavAct" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Favoriete werkzaamheden</h4>
            </div>

            <div class="modal-body">

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Omschrijving</th>
                                <th class="text-right">Aangemaakt</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (FavoriteActivity::where('user_id', Auth::id())->orderBy('created_at')->get() as $favact)
                            <tr>
                                <td><a class="favlink" href="#" data-id="{{ $favact->id }}">{{ $favact->activity_name }}</a></td>
                                <td class="text-right">{{ $favact->created_at->toDateString() }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times">&nbsp;</i>Sluiten</button>
            </div>

        </div>
    </div>
</div> */ ?>
{{-- /TODO: move into module --}}

<div class="modal fade" id="asyncModal" tabindex="-1" role="dialog" aria-labelledby="asyncModal" aria-hidden="true">
    <div class="modal-dialog {{-- modal-lg --}} {{-- modal-sm --}}">
        <div class="modal-content"></div>
    </div>
</div>

<div class="toogle">
    @foreach ($project->chapters()->orderBy('priority')->get() as $chapter)
    <div id="toggle-chapter-{{ $section }}-{{ $chapter->id }}" class="toggle toggle-{{ $section }} toggle-chapter">
        <label>{{ $chapter->chapter_name }}</label>
        <div class="toggle-content" style="padding: 5px 10px;">

            {{-- Activity body --}}
            <div class="toogle">
                @foreach ($filter($section, $chapter->activities())->get() as $activity)
                <div id="toggle-activity-{{ $section }}-{{ $activity->id }}" class="toggle toggle-{{ $section }} toggle-activity">
                    <label>
                        <span>{{ $activity->activity_name }}</span>
                        <span class="label-align-right">
                            @if ($activity->isSubcontracting())
                            <div class="label-custom"><i class="fa fa-user">&nbsp;&nbsp;</i>Onderaanneming</div>
                            @endif
                            @if ($activity->isEstimate())
                            <div class="label-custom"><i class="fa fa-wrench">&nbsp;&nbsp;</i>Stelpost</div>
                            @endif
                        </span>
                        <span style="float:right;margin-right:30px;">@money($calculus_overview::activityTotalProfit($project->hour_rate, $activity, $profit('material', $activity), $profit('other', $activity)))</span>
                    </label>
                    <div class="toggle-content" style="padding:10px 0px">

                        {{-- Activity options --}}
                        @ifallowed ($features['activity.options'])
                        <div class="row" style="margin-bottom:15px">
                            <div class="col-md-12 text-right">

                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-pencil">&nbsp;&nbsp;</i>Werkzaamheid&nbsp;&nbsp;<span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        @ifallowed ($features['activity.changename'])
                                        <li><a href="/inline/changename?id={{ $activity->id }}&level=2&name={{ urlencode($activity->activity_name) }}&package=component.modal" data-toggle="modal" data-target="#asyncModal"><i class="fa fa-pencil-square-o">&nbsp;</i>Naam wijzigen</a></a></li>
                                        @endifallowed

                                        <li><a href="/inline/description?id={{ $activity->id }}&package=component.modal" data-toggle="modal" data-target="#asyncModal"><i class="fa fa-file-text-o" style="padding-right:5px">&nbsp;</i>Omschrijving</a></li>
                                        <li><a href="/project/level/favorite?activity={{ $activity->id }}&level=2&csrf={{ csrf_token() }}" onclick="return confirm('Niveau opslaan als favoriet?')"><i class="fa fa-star-o" style="padding-right:5px">&nbsp;</i>Opslaan als Favoriet</a></li>

                                        @if ((isset($features['activity.timesheet']) && $features['activity.timesheet'] === true)
                                            || (isset($features['activity.convertsubcon']) && $features['activity.convertsubcon'] === true)
                                            || (isset($features['activity.converestimate']) && $features['activity.converestimate'] === true))
                                        <li class="divider" style="margin:5px 0;"></li>
                                        @endif

                                        @ifallowed ($features['activity.timesheet'])
                                        @if ($activity->use_timesheet)
                                        <li><a href="/project/level/option?activity={{ $activity->id }}&action=disable_timesheet&csrf={{ csrf_token() }}"><i class="fa fa-hourglass-end">&nbsp;&nbsp;</i>Gebruik Arbeid</a></li>
                                        @else
                                        <li><a href="/project/level/option?activity={{ $activity->id }}&action=enable_timesheet&csrf={{ csrf_token() }}"><i class="fa fa-clock-o">&nbsp;&nbsp;</i>Gebruik Urenregistratie</a></li>
                                        @endif
                                        @endifallowed

                                        @ifallowed ($features['activity.convertsubcon'])
                                        @if ($activity->isSubcontracting())
                                        <li><a href="/project/level/option?activity={{ $activity->id }}&action=convert_contracting&csrf={{ csrf_token() }}"><i class="fa fa-outdent">&nbsp;&nbsp;</i>Maak Aanneming</a></li>
                                        @else
                                        <li><a href="/project/level/option?activity={{ $activity->id }}&action=convert_subcontracting&csrf={{ csrf_token() }}"><i class="fa fa-indent">&nbsp;&nbsp;</i>Maak Onderaanneming</a></li>
                                        @endif
                                        @endifallowed

                                        @ifallowed ($features['activity.converestimate'])
                                        @if ($activity->isEstimate())
                                        <li><a href="/project/level/option?activity={{ $activity->id }}&action=convert_calculation&csrf={{ csrf_token() }}"><i class="fa fa-retweet">&nbsp;&nbsp;</i>Maak Calculatie</a></li>
                                        @else
                                        <li><a href="/project/level/option?activity={{ $activity->id }}&action=convert_estimate&csrf={{ csrf_token() }}"><i class="fa fa-retweet">&nbsp;&nbsp;</i>Maak Stelpost</a></li>
                                        @endif
                                        @endifallowed

                                        @if ((isset($features['activity.move']) && $features['activity.move'] === true)
                                            || (isset($features['activity.remove']) && $features['activity.remove'] === true))
                                        <li class="divider" style="margin:5px 0;"></li>
                                        @endifallowed

                                        @ifallowed ($features['activity.move'])
                                        <li><a href="/project/level/move?id={{ $activity->id }}&level=2&direction=up&csrf={{ csrf_token() }}"><i class="fa fa-arrow-up" style="padding-right:5px">&nbsp;</i>Verplaats omhoog</a></li>
                                        <li><a href="/project/level/move?id={{ $activity->id }}&level=2&direction=down&csrf={{ csrf_token() }}"><i class="fa fa-arrow-down" style="padding-right:5px">&nbsp;</i>Verplaats omlaag</a></li>
                                        @endifallowed

                                        @ifallowed ($features['activity.remove'])
                                        <li><a href="/project/level/delete?activity={{ $activity->id }}&level=2&csrf={{ csrf_token() }}" onclick="return confirm('Niveau verwijderen?')"><i class="fa fa-times" style="padding-right:5px">&nbsp;</i>Verwijderen</a></li>
                                        @endifallowed
                                    </ul>
                                </div>

                            </div>
                        </div>
                        @endifallowed
                        {{-- /Activity options --}}

                        {{-- Labor --}}
                        @ifallowed ($features['rows.labor'])
                        @if (!$activity->use_timesheet)
                        <div class="row">
                            <div class="col-md-2"><h4>Arbeid</h4></div>
                            <div class="col-md-6"></div>
                            @if ($project->tax_reverse)
                            <div class="col-md-2 text-right label label-info"><strong>BTW 0%</strong></div>
                            <div class="col-md-2"></div>
                            @else
                            <div class="col-md-2 text-right"></div>
                            <div class="col-md-2">
                                @ifallowed ($features['tax.update'])
                                <select name="tax" data-id="{{ $activity->id }}" data-layer="labor" class="form-control-sm-text pointer">
                                    @foreach ($tax::all() as $tax)
                                    @php
                                    if ($tax->id == 1) continue;
                                    @endphp
                                    <option value="{{ $tax->id }}" {{ ($activity->tax_labor_id==$tax->id ? 'selected="selected"' : '') }}>BTW {{ $tax->tax_rate }}%</option>
                                    @endforeach
                                </select>
                                @else
                                <select name="tax" disabled class="form-control-sm-text pointer" style="opacity: .65;cursor: not-allowed;">
                                    @foreach ($tax::all() as $tax)
                                    <option {{ ($activity->tax_labor_id==$tax->id ? 'selected="selected"' : '') }}>BTW {{ $tax->tax_rate }}%</option>
                                    @endforeach
                                </select>
                                @endifallowed
                            </div>
                            @endif
                        </div>
                        <table class="table table-striped" data-id="{{ $activity->id }}" data-layer="labor">
                            <thead>
                                <tr>
                                    <th class="col-md-5">Omschrijving</th>
                                    <th class="col-md-1">Eenheid</th>
                                    <th class="col-md-1">Uurtarief</th>
                                    <th class="col-md-1">Uren</th>
                                    <th class="col-md-1">Prijs</th>
                                    <th class="col-md-1">+ Winst %</th>
                                    <th class="col-md-1">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="height:33px" data-id="{{ $layer('labor', $activity)::where('activity_id', $activity->id)->first() ? $layer('labor', $activity)::where('activity_id', $activity->id)->first()->id : '' }}">
                                    <td class="col-md-5">Arbeidsuren</td>
                                    <td class="col-md-1">Uur</td>
                                    <td class="col-md-1">
                                        @if ($activity->isSubcontracting())
                                        @ifallowed ($features['rows.labor.edit.rate'])
                                        <span class="rate"><input name="rate" type="text" value="@money($layer('labor', $activity)::where('activity_id', $activity->id)->first() ? $layer('labor', $activity)::where('activity_id', $activity->id)->first()->rate : 0, false)" class="form-control-sm-number labor-amount lsave"></span>
                                        @else
                                        @money($project->hour_rate, false)
                                        @endifallowed
                                        @else
                                        @money($project->hour_rate, false)
                                        @endif
                                    </td>
                                    <td class="col-md-1">
                                        @ifallowed ($features['rows.labor.edit.amount'])
                                        <input data-id="{{ $activity->id }}" name="amount" type="text" value="@money($layer('labor', $activity)::where('activity_id', $activity->id)->first() ? $layer('labor', $activity)::where('activity_id', $activity->id)->first()->getAmount() : 0, false)" class="form-control-sm-number labor-amount lsave" />
                                        @else
                                        @money($layer('labor', $activity)::where('activity_id', $activity->id)->first() ? $layer('labor', $activity)::where('activity_id', $activity->id)->first()->getAmount() : 0, false)
                                        @endif
                                    </td>
                                    <td class="col-md-1"><span class="total-row">@money($layer_total($activity)::laborTotal(Part::find($activity->part_id)->part_name=='subcontracting' ? $layer('labor', $activity)::where('activity_id', $activity->id)->first()['rate'] : $project->hour_rate, $layer('labor', $activity)::where('activity_id', $activity->id)->first()['amount']))</span></td>
                                    <td class="col-md-1"><span class="total-row-profit"></span>
                                    <td class="col-md-1 text-right">
                                        @ifallowed ($features['rows.labor.reset'])
                                        @if ($layer('labor', $activity)::where('activity_id', $activity->id)->first() && $layer('labor', $activity)::where('activity_id', $activity->id)->first()->isOriginal())
                                        <button name="reset" class="btn btn-xs btn-warning fa fa-undo"></button>
                                        @endif
                                        @endifallowed
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        @endif
                        @endifallowed
                        {{-- /Labor --}}

                        {{-- Timesheet --}}
                        @ifallowed ($features['rows.timesheet'])
                        @if ($activity->use_timesheet)
                        <div class="row">
                            <div class="col-md-2"><h4>Urenregistratie</h4></div>
                            <div class="col-md-6"></div>
                            @if ($project->tax_reverse)
                            <div class="col-md-2 text-right label label-info"><strong>BTW 0%</strong></div>
                            <div class="col-md-2"></div>
                            @else
                            <div class="col-md-2 text-right"></div>
                            <div class="col-md-2">
                                @ifallowed ($features['tax.update'])
                                <select name="tax" data-id="{{ $activity->id }}" data-layer="labor" class="form-control-sm-text pointer">
                                    @foreach ($tax::all() as $tax)
                                    @php
                                    if ($tax->id == 1) continue;
                                    @endphp
                                    <option value="{{ $tax->id }}" {{ ($activity->tax_labor_id==$tax->id ? 'selected="selected"' : '') }}>BTW {{ $tax->tax_rate }}%</option>
                                    @endforeach
                                </select>
                                @else
                                <select name="tax" disabled class="form-control-sm-text pointer" style="opacity: .65;cursor: not-allowed;">
                                    @foreach ($tax::all() as $tax)
                                    <option {{ ($activity->tax_labor_id==$tax->id ? 'selected="selected"' : '') }}>BTW {{ $tax->tax_rate }}%</option>
                                    @endforeach
                                </select>
                                @endifallowed
                            </div>
                            @endif
                        </div>
                        <table class="table table-striped" data-id="{{ $activity->id }}" data-layer="timesheet">
                            <thead>
                                <tr>
                                    <th class="col-md-5">
                                        <span class="col-md-8 nopadding">Omschrijving</span>
                                        <span class="col-md-4 nopadding">Datum</span>
                                    </th>
                                    <th class="col-md-1">Eenheid</th>
                                    <th class="col-md-1">Uurtarief</th>
                                    <th class="col-md-1">Uren</th>
                                    <th class="col-md-1">Prijs</th>
                                    <th class="col-md-1">+ Winst %</th>
                                    <th class="col-md-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($layer('labor', $activity)::where('activity_id', $activity->id)->whereNotNull('hour_id')->orderBy('id')->get() as $labor)
                                <tr style="height:33px" data-id="{{ $labor->id }}">
                                    <td class="col-md-5">
                                        <div class="col-md-8 nopadding">
                                            <input name="name" maxlength="100" type="text" class="form-control-sm-text newrow" value="{{ $labor->timesheet->getName($original) }}" />
                                        </div>
                                        <div class="col-md-4 nopadding">
                                            <input name="date" maxlength="100" type="text" class="form-control-sm-text newrow" value="{{ $labor->timesheet->register_date }}" />
                                        </div>
                                    </td>
                                    <td class="col-md-1">Uur</td>
                                    <td class="col-md-1">
                                        <span class="rate">
                                            @if ($activity->isSubcontracting())
                                            <input name="rate" type="text" value="@money($labor->getRate($original), false)" class="form-control-sm-number">
                                            @else
                                            @money($project->hour_rate, false)
                                            @endif
                                        </span>
                                    </td>
                                    <td class="col-md-1"><input name="amount" id="name" type="text" class="form-control-sm-number" value="@money($labor->getAmount($original), false)" /></td>
                                    <td class="col-md-1"><span class="total-row">@money($calculate_row($labor))</span></td>
                                    <td class="col-md-1"><span class="total-row-profit"></span></td>
                                    <td class="col-md-1 text-right">
                                        <button class="btn btn-xs btn-primary fa fa-book" data-toggle="modal" data-target="#myModal"></button>
                                        <button name="delete" class="btn btn-danger btn-xs fa fa-times"></button>
                                    </td>
                                </tr>
                                @endforeach
                                @ifallowed ($features['rows.timesheet.add'])
                                <tr style="height:33px">
                                    <td class="col-md-5">
                                        <div class="col-md-8 nopadding">
                                            <input name="name" maxlength="100" type="text" class="form-control-sm-text newrow" />
                                        </div>
                                        <div class="col-md-4 nopadding">
                                            <input name="date" maxlength="100" type="text" class="form-control-sm-text newrow" />
                                        </div>
                                    </td>
                                    <td class="col-md-1">Uur</td>
                                    <td class="col-md-1">
                                        <span class="rate">
                                            @if ($activity->isSubcontracting())
                                            <input name="rate" type="text" class="form-control-sm-number">
                                            @else
                                            @money($project->hour_rate, false)
                                            @endif
                                        </span>
                                    </td>
                                    <td class="col-md-1"><input name="amount" id="name" type="text" class="form-control-sm-number" /></td>
                                    <td class="col-md-1"><span class="total-row"></span></td>
                                    <td class="col-md-1"><span class="total-row-profit"></span></td>
                                    <td class="col-md-1 text-right">
                                        <button class="btn btn-xs btn-primary fa fa-book" data-toggle="modal" data-target="#myModal"></button>
                                        @ifallowed ($features['rows.timesheet.remove'])
                                        <button name="delete" class="btn btn-danger btn-xs fa fa-times"></button>
                                        @endifallowed
                                    </td>
                                </tr>
                                @endifallowed
                            </tbody>
                            <tbody>
                                <tr>
                                    <td class="col-md-5"><strong>Totaal</strong></td>
                                    <td class="col-md-1"></td>
                                    <td class="col-md-1"></td>
                                    <td class="col-md-1"></td>
                                    <td class="col-md-1"><strong class="subtotal">@money($calculus_register::timesheetTotal($activity->id))</span></td>
                                    <td class="col-md-1"><strong class="subtotal_profit"></span></td>
                                    <td class="col-md-1"></td>
                                </tr>
                            </tbody>
                        </table>
                        @endif
                        @endifallowed
                        {{-- /Timesheet --}}

                        {{-- Material --}}
                        @ifallowed ($features['rows.material'])
                        <div class="row">
                            <div class="col-md-2"><h4>Materiaal</h4></div>
                            <div class="col-md-6"></div>
                            @if ($project->tax_reverse)
                            <div class="col-md-2 text-right label label-info"></div>
                            <div class="col-md-2"></div>
                            @else
                            <div class="col-md-2 text-right"></div>
                            <div class="col-md-2">
                                @ifallowed ($features['tax.update'])
                                <select name="tax" data-id="{{ $activity->id }}" data-layer="material" class="form-control-sm-text pointer">
                                    @foreach ($tax::all() as $tax)
                                    @php
                                    if ($tax->id == 1) continue;
                                    @endphp
                                    <option value="{{ $tax->id }}" {{ ($activity->tax_material_id==$tax->id ? 'selected="selected"' : '') }}>BTW {{ $tax->tax_rate }}%</option>
                                    @endforeach
                                </select>
                                @else
                                <select name="tax" disabled class="form-control-sm-text pointer" style="opacity: .65;cursor: not-allowed;">
                                    @foreach ($tax::all() as $tax)
                                    <option {{ ($activity->tax_material_id==$tax->id ? 'selected="selected"' : '') }}>BTW {{ $tax->tax_rate }}%</option>
                                    @endforeach
                                </select>
                                @endifallowed
                            </div>
                            @endif
                        </div>
                        <table class="table table-striped" data-id="{{ $activity->id }}" data-layer="material">
                            <thead>
                                <tr>
                                    <th class="col-md-5">Omschrijving</th>
                                    <th class="col-md-1">Eenheid</th>
                                    <th class="col-md-1">&euro; / Eenh.</th>
                                    <th class="col-md-1">Aantal</th>
                                    <th class="col-md-1">Prijs</th>
                                    <th class="col-md-1">+ Winst %</th>
                                    <th class="col-md-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($layer('material', $activity)::where('activity_id', $activity->id)->orderBy('id')->get() as $material)
                                <tr style="height:33px" data-id="{{ $material->id }}">
                                    <td class="col-md-5">@ifallowed ($features['rows.material.edit.name'])<input name="name" maxlength="100" type="text" value="{{ $material->getName($original) }}" class="form-control-sm-text newrow" />@else {{ $material->getName($original) }} @endifallowed</td>
                                    <td class="col-md-1">@ifallowed ($features['rows.material.edit.unit'])<input name="unit" maxlength="10"  type="text" value="{{ $material->getUnit($original) }}" class="form-control-sm-text" />@else {{ $material->getUnit($original) }} @endifallowed</td>
                                    <td class="col-md-1">@ifallowed ($features['rows.material.edit.rate'])<input name="rate" type="text" value="@money($material->getRate($original), false)" class="form-control-sm-number" />@else @money($material->getRate($original), false) @endifallowed</td>
                                    <td class="col-md-1">@ifallowed ($features['rows.material.edit.amount'])<input name="amount" type="text" value="@money($material->getAmount($original), false)" class="form-control-sm-number" />@else @money($material->getAmount($original), false) @endifallowed</td>
                                    <td class="col-md-1"><span class="total-row">@money($calculate_row($material))</span></td>
                                    <td class="col-md-1"><span class="total-row-profit">@money($calculate_row($material, $profit('material', $activity)))</span></td>
                                    <td class="col-md-1 text-right">
                                        @ifallowed ($features['rows.material.edit'])
                                        <button class="btn btn-xs btn-primary fa fa-book" data-toggle="modal" data-target="#myModal"></button>

                                        @ifallowed ($features['rows.material.remove'])
                                        <button name="delete" class="btn btn-danger btn-xs fa fa-times"></button>
                                        @endifallowed

                                        @ifallowed ($features['rows.material.reset'])
                                        @if ($material->isOriginal())
                                        <button name="reset" class="btn btn-xs btn-warning fa fa-undo btn-x"></button>
                                        @else
                                        <button name="delete" class="btn btn-danger btn-xs fa fa-times"></button>
                                        @endifallowed
                                        @endif

                                        @endifallowed
                                    </td>
                                </tr>
                                @endforeach
                                @ifallowed ($features['rows.material.add'])
                                <tr style="height:33px">
                                    <td class="col-md-5"><input name="name" maxlength="100" id="name" type="text" class="form-control-sm-text newrow" /></td>
                                    <td class="col-md-1"><input name="unit" maxlength="10" id="name" type="text" class="form-control-sm-text" /></td>
                                    <td class="col-md-1"><input name="rate" id="name" type="text" class="form-control-sm-number" /></td>
                                    <td class="col-md-1"><input name="amount" id="name" type="text" class="form-control-sm-number" /></td>
                                    <td class="col-md-1"><span class="total-row"></span></td>
                                    <td class="col-md-1"><span class="total-row-profit"></span></td>
                                    <td class="col-md-1 text-right">
                                        <button class="btn btn-xs btn-primary fa fa-book" data-toggle="modal" data-target="#myModal"></button>
                                        @ifallowed ($features['rows.material.remove'])
                                        <button name="delete" class="btn btn-danger btn-xs fa fa-times"></button>
                                        @endifallowed
                                    </td>
                                </tr>
                                @endifallowed
                            </tbody>
                            <tbody>
                                <tr>
                                    <td class="col-md-5"><strong>Totaal</strong></td>
                                    <td class="col-md-1"></td>
                                    <td class="col-md-1"></td>
                                    <td class="col-md-1"></td>
                                    <td class="col-md-1"><strong class="subtotal">@money($layer_total($activity)::materialTotal($activity->id))</span></td>
                                    <td class="col-md-1"><strong class="subtotal_profit">@money($layer_total($activity)::materialTotalProfit($activity->id, $profit('material', $activity)))</span></td>
                                    <td class="col-md-1"></td>
                                </tr>
                            </tbody>
                        </table>
                        @endifallowed
                        {{-- /Material --}}

                        {{-- Equipment --}}
                        @ifallowed ($features['rows.other'])
                        <div class="row">
                            <div class="col-md-2"><h4>Overig</h4></div>
                            <div class="col-md-6"></div>
                            @if ($project->tax_reverse)
                            <div class="col-md-2 text-right label label-info"><strong>BTW 0%</strong></div>
                            <div class="col-md-2"></div>
                            @else
                            <div class="col-md-2 text-right"></div>
                            <div class="col-md-2">
                                @ifallowed ($features['tax.update'])
                                <select name="tax" data-id="{{ $activity->id }}" data-layer="other" class="form-control-sm-text pointer">
                                    @foreach ($tax::all() as $tax)
                                    @php
                                    if ($tax->id == 1) continue;
                                    @endphp
                                    <option value="{{ $tax->id }}" {{ ($activity->tax_equipment_id==$tax->id ? 'selected="selected"' : '') }}>BTW {{ $tax->tax_rate }}%</option>
                                    @endforeach
                                </select>
                                @else
                                <select name="tax" disabled class="form-control-sm-text pointer" style="opacity: .65;cursor: not-allowed;">
                                    @foreach ($tax::all() as $tax)
                                    <option {{ ($activity->tax_equipment_id==$tax->id ? 'selected="selected"' : '') }}>BTW {{ $tax->tax_rate }}%</option>
                                    @endforeach
                                </select>
                                @endifallowed
                            </div>
                            @endif
                        </div>
                        <table class="table table-striped" data-id="{{ $activity->id }}" data-layer="other">
                            <thead>
                                <tr>
                                    <th class="col-md-5">Omschrijving</th>
                                    <th class="col-md-1">Eenheid</th>
                                    <th class="col-md-1">&euro; / Eenh.</th>
                                    <th class="col-md-1">Aantal</th>
                                    <th class="col-md-1">Prijs</th>
                                    <th class="col-md-1">+ Winst %</th>
                                    <th class="col-md-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($layer('other', $activity)::where('activity_id', $activity->id)->orderBy('id')->get() as $equipment)
                                <tr style="height:33px" data-id="{{ $equipment->id }}">
                                    <td class="col-md-5">@ifallowed ($features['rows.other.edit.name'])<input name="name" maxlength="100" id="name" type="text" value="{{ $equipment->getName($original) }}" class="form-control-sm-text esave newrow" />@else {{ $equipment->getName($original) }} @endifallowed</td>
                                    <td class="col-md-1">@ifallowed ($features['rows.other.edit.unit'])<input name="unit" maxlength="10" id="name" type="text" value="{{ $equipment->getUnit($original) }}" class="form-control-sm-text esave" />@else {{ $equipment->getUnit($original) }} @endifallowed</td>
                                    <td class="col-md-1">@ifallowed ($features['rows.other.edit.rate'])<input name="rate" id="name" type="text" value="@money($equipment->getRate($original), false)" class="form-control-sm-number esave" />@else @money($equipment->getRate($original), false) @endifallowed</td>
                                    <td class="col-md-1">@ifallowed ($features['rows.other.edit.amount'])<input name="amount" id="name" type="text" value="@money($equipment->getAmount($original), false)" class="form-control-sm-number esave" />@else @money($equipment->getAmount($original), false) @endifallowed</td>
                                    <td class="col-md-1"><span class="total-row">@money($calculate_row($equipment))</span></td>
                                    <td class="col-md-1"><span class="total-row-profit">@money($calculate_row($equipment, $profit('other', $activity)))</span></td>
                                    <td class="col-md-1 text-right">
                                        @ifallowed ($features['rows.other.edit'])
                                        <button class="btn btn-xs btn-primary fa fa-book" data-toggle="modal" data-target="#myModal"></button>

                                        @ifallowed ($features['rows.other.remove'])
                                        <button name="delete" class="btn btn-danger btn-xs fa fa-times"></button>
                                        @endifallowed

                                        @ifallowed ($features['rows.other.reset'])
                                        @if ($equipment->isOriginal())
                                        <button name="reset" class="btn btn-xs btn-warning fa fa-undo"></button>
                                        @else
                                        <button name="delete" class="btn btn-danger btn-xs fa fa-times"></button>
                                        @endif
                                        @endifallowed

                                        @endifallowed
                                    </td>
                                </tr>
                                @endforeach
                                @ifallowed ($features['rows.other.add'])
                                <tr style="height:33px">
                                    <td class="col-md-5"><input name="name" maxlength="100" id="name" type="text" class="form-control-sm-text esave newrow" /></td>
                                    <td class="col-md-1"><input name="unit" maxlength="10" id="name" type="text" class="form-control-sm-text esave" /></td>
                                    <td class="col-md-1"><input name="rate" id="name" type="text" class="form-control-sm-number esave" /></td>
                                    <td class="col-md-1"><input name="amount" id="name" type="text" class="form-control-sm-number esave" /></td>
                                    <td class="col-md-1"><span class="total-row"></span></td>
                                    <td class="col-md-1"><span class="total-row-profit"></span></td>
                                    <td class="col-md-1 text-right">
                                        <button class="btn btn-xs btn-primary fa fa-book" data-toggle="modal" data-target="#myModal"></button>
                                        @ifallowed ($features['rows.other.remove'])
                                        <button name="delete" class="btn btn-danger btn-xs fa fa-times"></button>
                                        @endifallowed
                                    </td>
                                </tr>
                                @endifallowed
                            </tbody>
                            <tbody>
                                <tr>
                                    <td class="col-md-5"><strong>Totaal</strong></td>
                                    <td class="col-md-1"></td>
                                    <td class="col-md-1"></td>
                                    <td class="col-md-1"></td>
                                    <td class="col-md-1"><strong class="subtotal">@money($layer_total($activity)::equipmentTotal($activity->id))</span></td>
                                    <td class="col-md-1"><strong class="subtotal_profit">@money($layer_total($activity)::equipmentTotalProfit($activity->id, $profit('other', $activity)))</span></td>
                                    <td class="col-md-1"></td>
                                </tr>
                            </tbody>
                        </table>
                        @endifallowed
                        {{-- /Equipment --}}

                        {{-- Additional layers can be placed here, eventually this is pulled from module catalog --}}

                    </div>
                </div>
                @endforeach
            </div>
            {{-- /Activity body --}}

            {{-- Level:chapter options --}}
            <form method="POST" action="/project/level/new" accept-charset="UTF-8">
                {!! csrf_field() !!}

                <div class="row">
                    <div class="col-md-6">
                        @ifallowed ($features['level.new'])
                        <div class="input-group">
                            <input type="hidden" name="project" value="{{ $project->id }}">
                            <input type="hidden" name="chapter" value="{{ $chapter->id }}">
                            <input type="hidden" name="level" value="2">
                            <input type="hidden" name="type" value="{{ $section == 'estimate' ? 'estimate' : 'calculation' }}">
                            <input type="hidden" name="detail" value="{{ $component == 'more' ?: '' }}">
                            <input type="text" maxlength="50" class="form-control" name="name" id="name" value="" placeholder="Nieuwe Werkzaamheid">
                            <div class="input-group-btn">
                                <button class="btn btn-primary btn-primary-activity"><i class="fa fa-plus">&nbsp;&nbsp;</i> Voeg toe</button>
                                <button type="button" class="btn btn-primary dropdown-toggle" style="padding-right: 8px;padding-left: 8px;" data-toggle="dropdown">
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#" class="lfavselect" data-id="{{ $chapter->id }}" data-toggle="modal" data-target="#myFavAct"><i class="fa fa-star-o">&nbsp;</i>Favoriet selecteren</a></li>
                                </ul>
                            </div>
                        </div>
                        @endifallowed
                    </div>

                    @ifallowed ($features['chapter.options'])
                    <div class="col-md-6 text-right">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Onderdeel&nbsp;&nbsp;<span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li><a href="/inline/changename?id={{ $chapter->id }}&level=1&name={{ urlencode($chapter->chapter_name) }}&package=component.modal" data-toggle="modal" data-target="#asyncModal"><i class="fa fa-pencil-square-o">&nbsp;</i>Naam wijzigen</a></a></li>
                                <li><a href="/project/level/move?id={{ $chapter->id }}&level=1&direction=up&csrf={{ csrf_token() }}"><i class="fa fa-arrow-up">&nbsp;</i>Verplaats omhoog</a></li>
                                <li><a href="/project/level/move?id={{ $chapter->id }}&level=1&direction=down&csrf={{ csrf_token() }}"><i class="fa fa-arrow-down">&nbsp;</i>Verplaats omlaag</a></li>
                                <li><a href="/project/level/delete?chapter={{ $chapter->id }}&level=1&csrf={{ csrf_token() }}" onclick="return confirm('Niveau verwijderen?')"><i class="fa fa-times">&nbsp;</i>Verwijderen</a></li>
                            </ul>
                        </div>
                    </div>
                    @endifallowed
                </div>

            </form>
            {{-- /Chapter options --}}

        </div>
    </div>
    @endforeach
</div>

{{-- Project options --}}
<form method="POST" action="/project/level/new" accept-charset="UTF-8">
    {!! csrf_field() !!}

    @ifallowed ($features['level.new'])
    <div class="row">
        <div class="col-md-6">
            <div class="input-group">
                <input type="hidden" name="project" value="{{ $project->id }}">
                <input type="hidden" name="level" value="1">
                <input type="text" maxlength="50" class="form-control" name="name" id="name" value="" placeholder="Nieuw onderdeel">
                <span class="input-group-btn">
                    <button class="btn btn-primary btn-primary-chapter"><i class="fa fa-plus">&nbsp;&nbsp;</i> Voeg toe</button>
                </span>
            </div>
        </div>
    </div>
    @endifallowed

    @if (Auth::user()->isNewPeriod())
        @if (!$project->chapters()->count())
        <div class="row">
            <div class="col-md-12">
                <hr>
                <h4>Een kleine uitleg voordat je begint met calculeren</h4>
                <ul>
                    <li>Stap 1: Voeg nieuw <i>Onderdeel</i> toe</li>
                    <li>Stap 2: Klik op het toegevoegde <i>Onderdeel</i></li>
                    <li>Stap 3: Voeg <i>Werkzaamheid</i> toe</li>
                    <li>Stap 4: Klik op de toegevoegde <i>Werkzaamheid</i></li>
                    <li>Stap 5: Nu kunt u de <i>Werkzaamheid</i> gaan calculeren</li>
                </ul>
                <img src="/images/exp_calc.jpg" />
            </div>
        </div>
        @endif
    @endif

</form>
{{-- /Project options --}}
