<?php

use BynqIO\CalculatieTool\Models\Chapter;
use BynqIO\CalculatieTool\Calculus\CalculationOverview;
use BynqIO\CalculatieTool\Models\Activity;
use BynqIO\CalculatieTool\Models\PartType;
use BynqIO\CalculatieTool\Models\Part;
use BynqIO\CalculatieTool\Models\Tax;
use BynqIO\CalculatieTool\Models\Supplier;
use BynqIO\CalculatieTool\Models\Wholesale;

use BynqIO\CalculatieTool\Models\Product;
use BynqIO\CalculatieTool\Models\ProductSubGroup;
use BynqIO\CalculatieTool\Models\ProductGroup;
use BynqIO\CalculatieTool\Models\ProductSubCategory;

use BynqIO\CalculatieTool\Models\FavoriteActivity;

use BynqIO\CalculatieTool\Models\CalculationLabor;
use BynqIO\CalculatieTool\Calculus\CalculationRegister;
use BynqIO\CalculatieTool\Models\CalculationMaterial;
use BynqIO\CalculatieTool\Models\CalculationEquipment;

?>

@push('scripts')
<script src="/plugins/jquery.number.min.js"></script>
@endpush

@section('component_buttons')
<div class="pull-right">
    <a href="/project/{{ $project->id }}-{{ $project->slug() }}/printoverview" class="btn btn-primary" target="new" type="button"><i class="fa fa-file-pdf-o">&nbsp;</i>Projectoverzicht</a>
</div>
@endsection

<div class="modal fade" id="nameChangeChapModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <form method="POST" action="/calculation/calc/rename_chapter" accept-charset="UTF-8">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel2">Naam onderdeel</h4>
            </div>

            <div class="modal-body">
                <div class="form-horizontal">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <div class="col-md-4">
                            <label>Naam</label>
                        </div>
                        <div class="col-md-12">
                            <input value="" maxlength="100" name="chapter_name" id="nc_chapter_name" class="form-control" />
                            <input value="" name="chapter" id="nc_chapter" type="hidden" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
            </div>
        </div>
        </form>
    </div>
</div>
<div class="modal fade" id="nameChangeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <form method="POST" action="/calculation/calc/rename_activity" accept-charset="UTF-8">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel2">Naam werkzaamheid</h4>
            </div>

            <div class="modal-body">
                <div class="form-horizontal">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <div class="col-md-4">
                            <label>Naam</label>
                        </div>
                        <div class="col-md-12">
                            <input value="" maxlength="100" name="activity_name" id="nc_activity_name" class="form-control" />
                            <input value="" name="activity" id="nc_activity" type="hidden" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
            </div>
        </div>
        </form>
    </div>
</div>
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
<div class="modal fade" id="descModal" tabindex="-1" role="dialog" aria-labelledby="descModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Omschrijving werkzaamheid</h4>
            </div>
            
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-md-12">
                        <textarea name="note" id="note" rows="5" class="form-control"></textarea>
                        <input type="hidden" name="noteact" id="noteact" />
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal"><i class="fa fa-check"></i> Opslaan</button>
            </div>

        </div>
    </div>
</div>

<div class="toogle">
    @foreach (Chapter::where('project_id', $project->id)->orderBy('priority')->get() as $chapter)
    <div id="toggle-chapter-{{ $chapter->id }}" class="toggle toggle-chapter">
        <label>{{ $chapter->chapter_name }}</label>
        <div class="toggle-content">

            <div class="toogle">
                <?php
                $activity_total = 0;
                foreach(Activity::where('chapter_id','=', $chapter->id)->whereNull('detail_id')->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->orderBy('priority')->get() as $activity) {
                    if (Part::find($activity->part_id)->part_name=='contracting') {
                        $profit_mat = $project->profit_calc_contr_mat;
                        $profit_equip = $project->profit_calc_contr_equip;
                        $activity_total = CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip);
                    } else if (Part::find($activity->part_id)->part_name=='subcontracting') {
                        $profit_mat = $project->profit_calc_subcontr_mat;
                        $profit_equip = $project->profit_calc_subcontr_equip;
                        $activity_total = CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip);
                    }
                ?>
                <div id="toggle-activity-{{ $activity->id }}" class="toggle toggle-activity">
                    <label>
                        <span>{{ $activity->activity_name }}</span>
                        <span style="float: right;margin-right: 30px;">{{ '&euro; '.number_format($activity_total, 2, ",",".") }}</span>
                    </label>
                    <div class="toggle-content">
                        <div class="row">
                            <div class="col-md-6">
                                @if ($project->use_subcontract)
                                <label class="radio-inline"><input data-id="{{ $activity->id }}" class="radio-activity" name="soort{{ $activity->id }}" value="{{ Part::where('part_name','=','contracting')->first()->id }}" type="radio" {{ ( Part::find($activity->part_id)->part_name=='contracting' ? 'checked' : '') }}/>Aanneming</label>
                                <label class="radio-inline"><input data-id="{{ $activity->id }}" class="radio-activity" name="soort{{ $activity->id }}" value="{{ Part::where('part_name','=','subcontracting')->first()->id }}" type="radio" {{ ( Part::find($activity->part_id)->part_name=='subcontracting' ? 'checked' : '') }}/>Onderaanneming</label>
                                @endif
                            </div>
                            
                            <div class="col-md-6 text-right">
                                <button id="pop-{{$chapter->id.'-'.$activity->id}}" data-id="{{ $activity->id }}" data-note="{{ $activity->note }}" data-toggle="modal" data-target="#descModal" class="btn btn-default btn-xs notemod"><i class="fa fa-file-text-o">&nbsp;&nbsp;</i>Omschrijving</button>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Werkzaamheid&nbsp;&nbsp;<span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                    <li><a href="#" data-id="{{ $activity->id }}" class="lsavefav"><i class="fa fa-star-o">&nbsp;</i>Opslaan als favoriet</a></li>
                                    <li><a href="#" data-id="{{ $activity->id }}" data-name="{{ $activity->activity_name }}" data-toggle="modal" data-target="#nameChangeModal" class="changename"><i class="fa fa-pencil-square-o">&nbsp;</i>Naam wijzigen</a></li>
                                    <li><a href="#" data-id="{{ $activity->id }}" class="moveupactivity"><i class="fa fa-arrow-up">&nbsp;</i>Verplaats omhoog</a></li>
                                    <li><a href="#" data-id="{{ $activity->id }}" class="movedownactivity"><i class="fa fa-arrow-down">&nbsp;</i>Verplaats omlaag</a></li>
                                    <li><a href="#" data-id="{{ $activity->id }}" class="deleteact"><i class="fa fa-times">&nbsp;</i>Verwijderen</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2"><h4>Arbeid</h4></div>
                            @if ($project->tax_reverse)
                            <div class="col-md-1 text-right label label-info"><strong>BTW 0%</strong></div>
                            <div class="col-md-2"></div>
                            @else
                            <div class="col-md-1 text-right"><strong>BTW</strong></div>	
                            <div class="col-md-2">
                                <select name="btw" data-id="{{ $activity->id }}" data-type="calc-labor" id="type" class="form-control-sm-text pointer select-tax">
                                    @foreach (Tax::all() as $tax)
                                    <?php
                                    if ($tax->id == 1)
                                        continue;
                                    ?>
                                    <option value="{{ $tax->id }}" {{ ($activity->tax_labor_id==$tax->id ? 'selected="selected"' : '') }}>{{ $tax->tax_rate }}%</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="col-md-6"></div>
                        </div>

                        <table class="table table-striped" data-id="{{ $activity->id }}">
                            <thead>
                                <tr>
                                    <th class="col-md-5">Omschrijving</th>
                                    <th class="col-md-1">&nbsp;</th>
                                    <th class="col-md-1">Uurtarief</th>
                                    <th class="col-md-1">Aantal</th>
                                    <th class="col-md-1">&nbsp;</th>
                                    <th class="col-md-1">Prijs</th>
                                    <th class="col-md-1">&nbsp;</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr data-id="{{ CalculationLabor::where('activity_id','=', $activity->id)->first()['id'] }}">
                                    <td class="col-md-5">Arbeidsuren</td>
                                    <td class="col-md-1">&nbsp;</td>
                                    <td class="col-md-1"><span class="rate">{!! Part::find($activity->part_id)->part_name=='subcontracting' ? '<input name="rate" type="text" value="'.number_format(CalculationLabor::where('activity_id','=', $activity->id)->first()['rate'], 2,",",".").'" class="form-control-sm-number labor-amount lsave">' : number_format($project->hour_rate, 2,",",".") !!}</span></td>
                                    <td class="col-md-1"><input data-id="{{ $activity->id }}" name="amount" type="text" value="{{ number_format(CalculationLabor::where('activity_id','=', $activity->id)->first()['amount'], 2, ",",".") }}" class="form-control-sm-number labor-amount lsave" /></td>
                                    <td class="col-md-1">&nbsp;</td>
                                    <td class="col-md-1"><span class="total-ex-tax">{{ '&euro; '.number_format(CalculationRegister::calcLaborTotal(Part::find($activity->part_id)->part_name=='subcontracting' ? CalculationLabor::where('activity_id','=', $activity->id)->first()['rate'] : $project->hour_rate, CalculationLabor::where('activity_id','=', $activity->id)->first()['amount']), 2, ",",".") }}</span></td>
                                    <td class="col-md-1 text-right"><button class="btn btn-danger ldeleterow btn-xs fa fa-times"></button></td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-md-2"><h4>Materiaal</h4></div>
                            @if ($project->tax_reverse)
                            <div class="col-md-1 text-right label label-info"><strong>BTW 0%</strong></div>
                            <div class="col-md-2"></div>
                            @else
                            <div class="col-md-1 text-right"><strong>BTW</strong></div>	
                            <div class="col-md-2">
                                <select name="btw" data-id="{{ $activity->id }}" data-type="calc-material" id="type" class="form-control-sm-text pointer select-tax">
                                    @foreach (Tax::all() as $tax)
                                    <?php
                                    if ($tax->id == 1)
                                        continue;
                                    ?>
                                    <option value="{{ $tax->id }}" {{ ($activity->tax_material_id==$tax->id ? 'selected="selected"' : '') }}>{{ $tax->tax_rate }}%</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="col-md-6"></div>
                        </div>

                        <table class="table table-striped" data-id="{{ $activity->id }}">
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
                                @foreach (CalculationMaterial::where('activity_id','=', $activity->id)->get() as $material)
                                <tr data-id="{{ $material->id }}">
                                    <td class="col-md-5"><input name="name" maxlength="100" id="name" type="text" value="{{ $material->material_name }}" class="form-control-sm-text dsave newrow" /></td>
                                    <td class="col-md-1"><input name="unit" maxlength="10" id="name" type="text" value="{{ $material->unit }}" class="form-control-sm-text dsave" /></td>
                                    <td class="col-md-1"><input name="rate" id="name" type="text" value="{{ number_format($material->rate, 2,",",".") }}" class="form-control-sm-number dsave" /></td>
                                    <td class="col-md-1"><input name="amount" id="name" type="text" value="{{ number_format($material->amount, 2,",",".") }}" class="form-control-sm-number dsave" /></td>
                                    <td class="col-md-1"><span class="total-ex-tax">{{ '&euro; '.number_format($material->rate*$material->amount, 2,",",".") }}</span></td>
                                    <td class="col-md-1"><span class="total-incl-tax">{{ '&euro; '.number_format($material->rate*$material->amount*((100+$profit_mat)/100), 2,",",".") }}</span></td>
                                    <td class="col-md-1 text-right" data-profit="{{ $profit_mat }}">
                                        <button class="fa fa-book" data-toggle="modal" data-target="#myModal"></button>
                                        <button class="fa fa-star" data-toggle="modal" data-target="#myModal2"></button>
                                        <button class="btn btn-danger btn-xs sdeleterow fa fa-times"></button>
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td class="col-md-5"><input name="name" maxlength="100" id="name" type="text" class="form-control-sm-text dsave newrow" /></td>
                                    <td class="col-md-1"><input name="unit" maxlength="10" id="name" type="text" class="form-control-sm-text dsave" /></td>
                                    <td class="col-md-1"><input name="rate" id="name" type="text" class="form-control-sm-number dsave" /></td>
                                    <td class="col-md-1"><input name="amount" id="name" type="text" class="form-control-sm-number dsave" /></td>
                                    <td class="col-md-1"><span class="total-ex-tax"></span></td>
                                    <td class="col-md-1"><span class="total-incl-tax"></span></td>
                                    <td class="col-md-1 text-right" data-profit="{{ $profit_mat }}">
                                        <button class="fa fa-book" data-toggle="modal" data-target="#myModal"></button>
                                        <button class="fa fa-star" data-toggle="modal" data-target="#myModal2"></button>
                                        <button class="btn btn-danger btn-xs sdeleterow fa fa-times"></button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td class="col-md-5"><strong>Totaal</strong></td>
                                    <td class="col-md-1">&nbsp;</td>
                                    <td class="col-md-1">&nbsp;</td>
                                    <td class="col-md-1">&nbsp;</td>
                                    <td class="col-md-1"><strong class="mat_subtotal">{{ '&euro; '.number_format(CalculationRegister::calcMaterialTotal($activity->id, $profit_mat), 2, ",",".") }}</span></td>
                                    <td class="col-md-1"><strong class="mat_subtotal_profit">{{ '&euro; '.number_format(CalculationRegister::calcMaterialTotalProfit($activity->id, $profit_mat), 2, ",",".") }}</span></td>
                                    <td class="col-md-1">&nbsp;</td>
                                </tr>
                            </tbody>
                        </table>
                        
                        @if ($project->use_equipment)
                        <div class="row">
                            <div class="col-md-2"><h4>Overig</h4></div>
                            @if ($project->tax_reverse)
                            <div class="col-md-1 text-right label label-info"><strong>BTW 0%</strong></div>
                            <div class="col-md-2"></div>
                            @else
                            <div class="col-md-1 text-right"><strong>BTW</strong></div>	
                            <div class="col-md-2">
                                <select name="btw" data-id="{{ $activity->id }}" data-type="calc-equipment" id="type" class="form-control-sm-text pointer select-tax">
                                    @foreach (Tax::all() as $tax)
                                    <?php
                                    if ($tax->id == 1)
                                        continue;
                                    ?>
                                    <option value="{{ $tax->id }}" {{ ($activity->tax_equipment_id==$tax->id ? 'selected="selected"' : '') }}>{{ $tax->tax_rate }}%</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="col-md-6"></div>
                        </div>

                        <table class="table table-striped" data-id="{{ $activity->id }}">
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
                                @foreach (CalculationEquipment::where('activity_id','=', $activity->id)->get() as $equipment)
                                <tr data-id="{{ $equipment->id }}">
                                    <td class="col-md-5"><input name="name" maxlength="100" id="name" type="text" value="{{ $equipment->equipment_name }}" class="form-control-sm-text esave newrow" /></td>
                                    <td class="col-md-1"><input name="unit" maxlength="10" id="name" type="text" value="{{ $equipment->unit }}" class="form-control-sm-text esave" /></td>
                                    <td class="col-md-1"><input name="rate" id="name" type="text" value="{{ number_format($equipment->rate, 2,",",".") }}" class="form-control-sm-number esave" /></td>
                                    <td class="col-md-1"><input name="amount" id="name" type="text" value="{{ number_format($equipment->amount, 2,",",".") }}" class="form-control-sm-number esave" /></td>
                                    <td class="col-md-1"><span class="total-ex-tax">{{ '&euro; '.number_format($equipment->rate*$equipment->amount, 2,",",".") }}</span></td>
                                    <td class="col-md-1"><span class="total-incl-tax">{{ '&euro; '.number_format($equipment->rate*$equipment->amount*((100+$profit_equip)/100), 2,",",".") }}</span></td>
                                    <td class="col-md-1 text-right" data-profit="{{ $profit_equip }}">
                                        <button class="fa fa-book" data-toggle="modal" data-target="#myModal"></button>
                                        <button class="fa fa-star" data-toggle="modal" data-target="#myModal2"></button>
                                        <button class="btn btn-danger btn-xs edeleterow fa fa-times"></button>
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td class="col-md-5"><input name="name" maxlength="100" id="name" type="text" class="form-control-sm-text esave newrow" /></td>
                                    <td class="col-md-1"><input name="unit" maxlength="10" id="name" type="text" class="form-control-sm-text esave" /></td>
                                    <td class="col-md-1"><input name="rate" id="name" type="text" class="form-control-sm-number esave" /></td>
                                    <td class="col-md-1"><input name="amount" id="name" type="text" class="form-control-sm-number esave" /></td>
                                    <td class="col-md-1"><span class="total-ex-tax"></span></td>
                                    <td class="col-md-1"><span class="total-incl-tax"></span></td>
                                    <td class="col-md-1 text-right" data-profit="{{ $profit_equip }}">
                                        <button class="fa fa-book" data-toggle="modal" data-target="#myModal"></button>
                                        <button class="fa fa-star" data-toggle="modal" data-target="#myModal2"></button>
                                        <button class="btn btn-danger btn-xs edeleterow fa fa-times"></button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td class="col-md-5"><strong>Totaal</strong></td>
                                    <td class="col-md-1">&nbsp;</td>
                                    <td class="col-md-1">&nbsp;</td>
                                    <td class="col-md-1">&nbsp;</td>
                                    <td class="col-md-1"><strong class="equip_subtotal">{{ '&euro; '.number_format(CalculationRegister::calcEquipmentTotal($activity->id, $profit_equip), 2, ",",".") }}</span></td>
                                    <td class="col-md-1"><strong class="equip_subtotal_profit">{{ '&euro; '.number_format(CalculationRegister::calcEquipmentTotalProfit($activity->id, $profit_equip), 2, ",",".") }}</span></td>
                                    <td class="col-md-1">&nbsp;</td>
                                </tr>
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
                <?php } ?>
            </div>

            <form method="POST" action="/calculation/calc/newactivity/{{ $chapter->id }}" accept-charset="UTF-8">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-md-6">

                    <div class="input-group">
                        <input type="text" maxlength="100" class="form-control" name="activity" id="activity" value="" placeholder="Nieuwe Werkzaamheid">
                        <span class="input-group-btn">
                            <button class="btn btn-primary btn-primary-activity"><i class="fa fa-plus">&nbsp;&nbsp;</i> Voeg toe</button>
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                            </button>
                                <ul class="dropdown-menu" role="menu">
                                <li><a href="#" class="lfavselect" data-id="{{ $chapter->id }}" data-toggle="modal" data-target="#myFavAct"><i class="fa fa-star-o">&nbsp;</i>Favoriet selecteren</a></li>
                                </ul>
                        </span>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Onderdeel&nbsp;&nbsp;<span class="caret"></span></button>
                        <ul class="dropdown-menu">
                        <li><a href="#" data-id="{{ $chapter->id }}" data-name="{{ $chapter->chapter_name }}" data-toggle="modal" data-target="#nameChangeChapModal" class="changenamechap"><i class="fa fa-pencil-square-o">&nbsp;</i>Naam wijzigen</a></li>
                        <li><a href="#" data-id="{{ $chapter->id }}" class="moveupchap"><i class="fa fa-arrow-up">&nbsp;</i>Verplaats omhoog</a></li>
                        <li><a href="#" data-id="{{ $chapter->id }}" class="movedownchap"><i class="fa fa-arrow-down">&nbsp;</i>Verplaats omlaag</a></li>
                        <li><a href="#" data-id="{{ $chapter->id }}" class="deletechap"><i class="fa fa-times">&nbsp;</i>Verwijderen</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
    @endforeach
</div>

<form method="POST" action="/calculation/newchapter/{{ $project->id }}" accept-charset="UTF-8">
    {!! csrf_field() !!}
<div><hr></div>

<div class="row">
    <div class="col-md-6">
        <div class="input-group">
            <input type="text" maxlength="100" class="form-control" name="chapter" id="chapter" value="" placeholder="Nieuw onderdeel">
            <span class="input-group-btn">
                <button class="btn btn-primary btn-primary-chapter"><i class="fa fa-plus">&nbsp;&nbsp;</i> Voeg toe</button>
            </span>
        </div>
    </div>
</div>
@if (Auth::user()->isNewPeriod())
@if (!Chapter::where('project_id', $project->id)->count())
<br>
<h4>Een kleine uitleg voordat je begint met calculeren</h4>
<hr>
                            <ul>
        <li>Stap 1: Voeg nieuw <i>Onderdeel</i> toe</li>
        <li>Stap 2: Klik op het toegevoegde <i>Onderdeel</i></li>
        <li>Stap 3: Voeg <i>Werkzaamheid</i> toe</li>
        <li>Stap 4: Klik op de toegevoegde <i>Werkzaamheid</i></li>
        <li>Stap 5: Nu kunt u de <i>Werkzaamheid</i> gaan calculeren</li>
</ul>
<br>
<img src="/images/exp_calc.jpg" />
<br>
@endif
@endif

</form>
