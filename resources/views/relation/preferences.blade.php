@extends('relation.layout', ['page' => 'preferences'])

@section('relation_section_name', 'Voorkeuren')

@section('relation_content')

<?php
use \BynqIO\CalculatieTool\Models\Relation;
$relation = Relation::find(Route::Input('relation_id'));
?>

<div class="white-row">
    <form method="post" action="/relation/updatecalc">
        {!! csrf_field() !!}
        <input type="hidden" name="id" id="id" value="{{ $relation->id }}"/>
        <div class="row">
            <div class="col-md-5"><h5><strong>Eigen uurtarief <a data-toggle="tooltip" data-placement="bottom" data-original-title="Geef hier uw uurtarief op wat door heel de calculatie gebruikt wordt voor dit project. Of stel deze in bij Voorkeuren om bij elk project te kunnen gebruiken." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></strong></h5></div>
            <div class="col-md-1"></div>
            <div class="col-md-2"><h5><strong>Calculatie</strong></h5></div>
            <div class="col-md-2"><h5><strong>Meerwerk</strong></h5></div>
        </div>
        <div class="row">
            <div class="col-md-5"><label for="hour_rate">Uurtarief excl. BTW</label></div>
            <div class="col-md-1"><div class="pull-right">&euro;</div></div>
            <div class="col-md-2">
                <input name="hour_rate" type="text" value="{{ old('hour_rate') ? old('hour_rate') : number_format($relation->hour_rate, 2,",",".") }}" class="form-control form-control-sm-number"/>
            </div>
            <div class="col-md-2">
                <input name="more_hour_rate" id="more_hour_rate" type="text" value="{{ old('more_hour_rate') ? old('more_hour_rate') : number_format($relation->hour_rate_more, 2,",",".") }}" class="form-control form-control-sm-number"/>
            </div>
        </div>

        <h5><strong>Aanneming <a data-toggle="tooltip" data-placement="bottom" data-original-title="Geef hier uw winstpercentage op wat u over uw materiaal en overig wilt gaan rekenen. Of stel deze in bij Voorkeuren om bij elk project te kunnen gebruiken." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></strong></h5></strong></h5>
        <div class="row">
            <div class="col-md-5"><label for="profit_material_1">Winstpercentage materiaal</label></div>
            <div class="col-md-1"><div class="pull-right">%</div></div>
            <div class="col-md-2">
                <input name="profit_material_1" id="profit_material_1" type="number" min="0" max="200" value="{{ old('profit_material_1') ? old('profit_material_1') : $relation->profit_calc_contr_mat }}" class="form-control form-control-sm-number"/>
            </div>
            <div class="col-md-2">
                <input name="more_profit_material_1" id="more_profit_material_1" type="number" min="0" max="200" value="{{ old('more_profit_material_1') ? old('more_profit_material_1') : $relation->profit_more_contr_mat }}" class="form-control form-control-sm-number"/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5"><label for="profit_equipment_1">Winstpercentage overig</label></div>
            <div class="col-md-1"><div class="pull-right">%</div></div>
            <div class="col-md-2">
                <input name="profit_equipment_1" id="profit_equipment_1" type="number" min="0" max="200" value="{{ old('profit_equipment_1') ? old('profit_equipment_1') : $relation->profit_calc_contr_equip }}" class="form-control form-control-sm-number"/>
            </div>
            <div class="col-md-2">
                <input name="more_profit_equipment_1" id="more_profit_equipment_1" type="number" min="0" max="200" value="{{ old('more_profit_equipment_1') ? old('more_profit_equipment_1') : $relation->profit_more_contr_equip }}" class="form-control form-control-sm-number"/>
            </div>
        </div>

        <h5><strong>Onderaanneming <a data-toggle="tooltip" data-placement="bottom" data-original-title="Onderaanneming: Geef hier uw winstpercentage op wat u over het materiaal en overig van uw onderaanneming wilt gaan rekenen. Of stel deze in bij Voorkeuren om bij elk project te kunnen gebruiken." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></strong></h5></strong></h5>
        <div class="row">
            <div class="col-md-5"><label for="profit_material_2">Winstpercentage materiaal</label></div>
            <div class="col-md-1"><div class="pull-right">%</div></div>
            <div class="col-md-2">
                <input name="profit_material_2" id="profit_material_2" type="number" min="0" max="200" value="{{ old('profit_material_2') ? old('profit_material_2') : $relation->profit_calc_subcontr_mat }}" class="form-control form-control-sm-number"/>
            </div>
            <div class="col-md-2">
                <input name="more_profit_material_2" id="more_profit_material_2" type="number" min="0" max="200" value="{{ old('more_profit_material_2') ? old('more_profit_material_2') : $relation->profit_more_subcontr_mat }}" class="form-control form-control-sm-number"/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5"><label for="profit_equipment_2">Winstpercentage overig</label></div>
            <div class="col-md-1"><div class="pull-right">%</div></div>
            <div class="col-md-2">
                <input name="profit_equipment_2" id="profit_equipment_2" type="number" min="0" max="200" value="{{ old('profit_equipment_2') ? old('profit_equipment_2') : $relation->profit_calc_subcontr_equip }}" class="form-control form-control-sm-number"/>
            </div>
            <div class="col-md-2">
                <input name="more_profit_equipment_2" id="more_profit_equipment_2" type="number" min="0" max="200" value="{{ old('more_profit_equipment_2') ? old('more_profit_equipment_2') : $relation->profit_more_subcontr_equip }}" class="form-control form-control-sm-number"/>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
            </div>
        </div>
    </form>
</div>
@endsection
