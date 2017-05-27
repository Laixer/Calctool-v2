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

@push('scripts')
<script src="/plugins/jquery.number.min.js"></script>
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

    /* Append new rows */
    $("body").on("blur", ".newrow", function() {
        var i = 1;
        if ($(this).val()) {
            if (!$(this).closest("tr").next().length) {
                var $curTable = $(this).closest("table");
                $curTable.find("tr:eq(1)").clone().removeAttr("data-id").find("input").each(function() {
                    $(this).val("").removeClass("error-input").attr("id", function(_, id) { return id + i });
                }).end().find(".total-ex-tax, .total-incl-tax").text("").end().find(".form-control-sm-number").each(function() {
                    $(this).number({!! \BynqIO\Dynq\Services\FormatService::monetaryJS('true') !!});
                }).end().find("[name=delete]").change(delete_row).end().appendTo($curTable);
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
    $("body").on("change", "[name=amount]",  save_row);
    $("body").on("click",  "[name=delete]",  delete_row);
    $("body").on("change", "[name=tax]",     save_tax);

    function save_tax() {
        $.post('/project/layer/tax', {
            value:     $(this).val(),
            layer:     $(this).attr("data-layer"),
            activity:  $(this).attr("data-id"),
            project:   {{ $project->id }},
        }, function(data) { if (data.success) { save_callback($tr); } });
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
        $.post($uri, {
            id:        $tr.attr("data-id"),
            name:      $tr.find("input[name='name']").val(),
            unit:      $tr.find("input[name='unit']").val(),
            rate:      $tr.find("input[name='rate']").val(),
            amount:    $tr.find("input[name='amount']").val(),
            layer:     $tr.closest("table").attr("data-layer"),
            activity:  $tr.closest("table").attr("data-id"),
            project:   {{ $project->id }},
        }, function(data) { if (data.success) { save_callback($tr, data); } });
    }

    //TODO
    function save_callback($tr, data) {
        if (data.success) {
            $tr.attr("data-id", data.id);
        }

        var rate   = parseNumber($tr.find("input[name='rate']").val());
        var amount = parseNumber($tr.find("input[name='amount']").val());

        // var profit = 2;//= $tr.closest("tr").find('td[data-profit]').data('profit');
        $tr.find(".total-ex-tax").text('€ ' + convertNumber(rate * amount) );
        // $tr.find(".total-incl-tax").text('€ ' + convertNumber(rate * amount * ((100 + profit) / 100)));
        // var sub_total = 0;
        // $curThis.closest("tbody").find(".total-ex-tax").each(function(index){
        //     var _cal = parseFloat($(this).text().substring(2).split('.').join('').replace(',', '.'));
        //     if (_cal)
        //         sub_total += _cal;
        // });
        // $curThis.closest("table").find('.mat_subtotal').text('€ '+$.number(sub_total,2,',','.'));
        // var sub_total_profit = 0;
        // $curThis.closest("tbody").find(".total-incl-tax").each(function(index){
        //     var _cal = parseFloat($(this).text().substring(2).split('.').join('').replace(',', '.'));
        //     if (_cal)
        //         sub_total_profit += _cal;
        // });
        // $curThis.closest("table").find('.mat_subtotal_profit').text('€ '+$.number(sub_total_profit,2,',','.'));
    }

    /* Remove contents from modal on close */
    $(document).on('hidden.bs.modal', function (e) {
        $(e.target).removeData('bs.modal');
    });

});
</script>
@endpush

{{-- TODO: move into module --}}
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
</div>
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
                        <span style="float:right;margin-right:30px;">
                            @if ($activity->isSubcontracting())
                            {{ '&euro; ' . \BynqIO\Dynq\Services\FormatService::monetary($calculus::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip)) }}
                            @else
                            {{ '&euro; ' . \BynqIO\Dynq\Services\FormatService::monetary($calculus::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip)) }}
                            @endif
                        </span>
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
                                        <li><a href="/inline/changename?id={{ $activity->id }}&level=2&name={{ urlencode($activity->activity_name) }}" data-toggle="modal" data-target="#asyncModal"><i class="fa fa-pencil-square-o">&nbsp;</i>Naam wijzigen</a></a></li>
                                        @endifallowed

                                        <li><a href="/inline/description?id={{ $activity->id }}" data-toggle="modal" data-target="#asyncModal"><i class="fa fa-file-text-o" style="padding-right:5px">&nbsp;</i>Omschrijving</a></li>
                                        <li><a href="/project/level/favorite?activity={{ $activity->id }}&level=2&csrf={{ csrf_token() }}" onclick="return confirm('Niveau opslaan als favoriet?')"><i class="fa fa-star-o" style="padding-right:5px">&nbsp;</i>Opslaan als Favoriet</a></li>

                                        @ifallowed ($features['activity.timesheet'])
                                        <li class="divider" style="margin:5px 0;"></li>
                                        @endifallowed

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

                                        @ifallowed ($features['activity.move'])
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
                                        <span class="rate"><input name="rate" type="text" value="{{ \BynqIO\Dynq\Services\FormatService::monetary($layer('labor', $activity)::where('activity_id', $activity->id)->first()['rate']) }}" class="form-control-sm-number labor-amount lsave"></span>
                                        @else
                                        {{ \BynqIO\Dynq\Services\FormatService::monetary($project->hour_rate) }}
                                        @endif
                                    </td>
                                    <td class="col-md-1">
                                        @ifallowed ($features['rows.labor.edit'])
                                        <input data-id="{{ $activity->id }}" name="amount" type="text" value="{{ number_format($layer('labor', $activity)::where('activity_id', $activity->id)->first()['amount'], 2, ",",".") }}" class="form-control-sm-number labor-amount lsave" />
                                        @else
                                        {{ \BynqIO\Dynq\Services\FormatService::monetary($layer('labor', $activity)::where('activity_id', $activity->id)->first()['amount']) }}
                                        @endif
                                    </td>
                                    <td class="col-md-1"><span class="total-ex-tax">{{ '&euro; ' . \BynqIO\Dynq\Services\FormatService::monetary(CalculationRegister::calcLaborTotal(Part::find($activity->part_id)->part_name=='subcontracting' ? $layer('labor', $activity)::where('activity_id', $activity->id)->first()['rate'] : $project->hour_rate, $layer('labor', $activity)::where('activity_id', $activity->id)->first()['amount'])) }}</span></td>
                                    <td class="col-md-1">&nbsp;</td>
                                    <td class="col-md-1 text-right">
                                        @ifallowed ($features['rows.labor.reset'])
                                        <button name="reset" class="btn btn-xs btn-warning fa fa-undo btn-x"></button>
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
                                    <th class="col-md-1">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="height:33px">
                                    <td class="col-md-5">
                                        <div class="col-md-8 nopadding">
                                            <input name="name" maxlength="100" id="name" type="text" class="form-control-sm-text dsave newrow" />
                                        </div>
                                        <div class="col-md-4 nopadding">
                                            <input name="name" maxlength="100" id="name" type="text" class="form-control-sm-text dsave newrow" />
                                        </div>
                                    </td>
                                    <td class="col-md-1">Uur</td>
                                    <td class="col-md-1"><span class="rate">{!! Part::find($activity->part_id)->part_name=='subcontracting' ? '<input name="rate" type="text" value="'.number_format(CalculationLabor::where('activity_id','=', $activity->id)->first()['rate'], 2,",",".").'" class="form-control-sm-number labor-amount lsave">' : number_format($project->hour_rate, 2,",",".") !!}</span></td>
                                    <td class="col-md-1"><input name="amount" id="name" type="text" class="form-control-sm-number dsave" /></td>
                                    <td class="col-md-1"><span class="total-ex-tax"></span></td>
                                    <td class="col-md-1"><span class="total-incl-tax"></span></td>
                                    <td class="col-md-1 text-right">
                                        <button class="fa fa-book" data-toggle="modal" data-target="#myModal"></button>
                                        <button class="fa fa-star" data-toggle="modal" data-target="#myModal2"></button>
                                        <button name="delete" class="btn btn-danger btn-xs fa fa-times"></button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td class="col-md-5"><strong>Totaal</strong></td>
                                    <td class="col-md-1">&nbsp;</td>
                                    <td class="col-md-1">&nbsp;</td>
                                    <td class="col-md-1">&nbsp;</td>
                                    <td class="col-md-1"><strong class="mat_subtotal">{{-- '&euro; '.number_format(CalculationRegister::calcMaterialTotal($activity->id, $profit_mat), 2, ",",".") --}}</span></td>
                                    <td class="col-md-1"><strong class="mat_subtotal_profit">{{-- '&euro; '.number_format(CalculationRegister::calcMaterialTotalProfit($activity->id, $profit_mat), 2, ",",".") --}}</span></td>
                                    <td class="col-md-1">&nbsp;</td>
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
                                    <th class="col-md-1">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($layer('material', $activity)::where('activity_id', $activity->id)->orderBy('id')->get() as $material)
                                <tr style="height:33px" data-id="{{ $material->id }}">
                                    <td class="col-md-5">@ifallowed ($features['rows.material.edit'])<input name="name" maxlength="100" id="name" type="text" value="{{ $material->material_name }}" class="form-control-sm-text newrow" />@else{{ $material->material_name }}@endifallowed</td>
                                    <td class="col-md-1">@ifallowed ($features['rows.material.edit'])<input name="unit" maxlength="10" id="name" type="text" value="{{ $material->unit }}" class="form-control-sm-text" />@else{{ $material->unit }}@endifallowed</td>
                                    <td class="col-md-1">@ifallowed ($features['rows.material.edit'])<input name="rate" id="name" type="text" value="{{ number_format($material->rate, 2,",",".") }}" class="form-control-sm-number" />@else{{ number_format($material->rate, 2,",",".") }}@endifallowed</td>
                                    <td class="col-md-1">@ifallowed ($features['rows.material.edit'])<input name="amount" id="name" type="text" value="{{ number_format($material->amount, 2,",",".") }}" class="form-control-sm-number" />@else{{ number_format($material->amount, 2,",",".") }}@endifallowed</td>
                                    <td class="col-md-1"><span class="total-ex-tax">{{-- '&euro; ' . \BynqIO\Dynq\Services\FormatService::monetary($material->rate * $material->amount) --}}</span></td>
                                    <td class="col-md-1"><span class="total-incl-tax">{{-- '&euro; ' . \BynqIO\Dynq\Services\FormatService::monetary($material->rate * $material->amount * ((100+$profit_mat)/100)) --}}</span></td>
                                    <td class="col-md-1 text-right">
                                        @ifallowed ($features['rows.material.edit'])
                                        <button class="btn btn-xs btn-primary fa fa-book" data-toggle="modal" data-target="#myModal"></button>
                                        @ifallowed ($features['rows.material.remove'])
                                        <button name="delete" class="btn btn-danger btn-xs fa fa-times"></button>
                                        @endifallowed
                                        @ifallowed ($features['rows.material.reset'])
                                        <button name="reset" class="btn btn-xs btn-warning fa fa-undo btn-x"></button>
                                        @endifallowed
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
                                    <td class="col-md-1"><span class="total-ex-tax"></span></td>
                                    <td class="col-md-1"><span class="total-incl-tax"></span></td>
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
                                    <td class="col-md-1">&nbsp;</td>
                                    <td class="col-md-1">&nbsp;</td>
                                    <td class="col-md-1">&nbsp;</td>
                                    <td class="col-md-1"><strong class="mat_subtotal">{{-- '&euro; ' . \BynqIO\Dynq\Services\FormatService::monetary(CalculationRegister::calcMaterialTotal($activity->id, $profit_mat)) --}}</span></td>
                                    <td class="col-md-1"><strong class="mat_subtotal_profit">{{-- '&euro; ' . \BynqIO\Dynq\Services\FormatService::monetary(CalculationRegister::calcMaterialTotalProfit($activity->id, $profit_mat)) --}}</span></td>
                                    <td class="col-md-1">&nbsp;</td>
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
                                    <th class="col-md-1">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($layer('other', $activity)::where('activity_id', $activity->id)->orderBy('id')->get() as $equipment)
                                <tr style="height:33px" data-id="{{ $equipment->id }}">
                                    <td class="col-md-5">@ifallowed ($features['rows.other.edit'])<input name="name" maxlength="100" id="name" type="text" value="{{ $equipment->equipment_name }}" class="form-control-sm-text esave newrow" />@else{{ $equipment->equipment_name }}@endifallowed</td>
                                    <td class="col-md-1">@ifallowed ($features['rows.other.edit'])<input name="unit" maxlength="10" id="name" type="text" value="{{ $equipment->unit }}" class="form-control-sm-text esave" />@else{{ $equipment->unit }}@endifallowed</td>
                                    <td class="col-md-1">@ifallowed ($features['rows.other.edit'])<input name="rate" id="name" type="text" value="{{ number_format($equipment->rate, 2,",",".") }}" class="form-control-sm-number esave" />@else{{ number_format($equipment->rate, 2,",",".") }}@endifallowed</td>
                                    <td class="col-md-1">@ifallowed ($features['rows.other.edit'])<input name="amount" id="name" type="text" value="{{ number_format($equipment->amount, 2,",",".") }}" class="form-control-sm-number esave" />@else{{ number_format($equipment->amount, 2,",",".") }}@endifallowed</td>
                                    <td class="col-md-1"><span class="total-ex-tax">{{-- '&euro; '.number_format($equipment->rate*$equipment->amount, 2,",",".") --}}</span></td>
                                    <td class="col-md-1"><span class="total-incl-tax">{{-- '&euro; '.number_format($equipment->rate*$equipment->amount*((100+$profit_equip)/100), 2,",",".") --}}</span></td>
                                    <td class="col-md-1 text-right">
                                        @ifallowed ($features['rows.other.edit'])
                                        <button class="btn btn-xs btn-primary fa fa-book" data-toggle="modal" data-target="#myModal"></button>
                                        @ifallowed ($features['rows.other.remove'])
                                        <button name="delete" class="btn btn-danger btn-xs fa fa-times"></button>
                                        @endifallowed
                                        @ifallowed ($features['rows.other.reset'])
                                        <button name="reset" class="btn btn-xs btn-warning fa fa-undo btn-x"></button>
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
                                    <td class="col-md-1"><span class="total-ex-tax"></span></td>
                                    <td class="col-md-1"><span class="total-incl-tax"></span></td>
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
                                    <td class="col-md-1">&nbsp;</td>
                                    <td class="col-md-1">&nbsp;</td>
                                    <td class="col-md-1">&nbsp;</td>
                                    <td class="col-md-1"><strong class="equip_subtotal">{{-- '&euro; '.number_format(CalculationRegister::calcEquipmentTotal($activity->id, $profit_equip), 2, ",",".") --}}</span></td>
                                    <td class="col-md-1"><strong class="equip_subtotal_profit">{{-- '&euro; '.number_format(CalculationRegister::calcEquipmentTotalProfit($activity->id, $profit_equip), 2, ",",".") --}}</span></td>
                                    <td class="col-md-1">&nbsp;</td>
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
                            <input type="text" maxlength="100" class="form-control" name="name" id="name" value="" placeholder="Nieuwe Werkzaamheid">
                            <div class="input-group-btn">
                                <button class="btn btn-primary btn-primary-activity"><i class="fa fa-plus">&nbsp;&nbsp;</i> Voeg toe</button>
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
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
                                <li><a href="/inline/changename?id={{ $chapter->id }}&level=1&name={{ urlencode($chapter->chapter_name) }}" data-toggle="modal" data-target="#asyncModal"><i class="fa fa-pencil-square-o">&nbsp;</i>Naam wijzigen</a></a></li>
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
                <input type="text" maxlength="100" class="form-control" name="name" id="name" value="" placeholder="Nieuw onderdeel">
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
