<?php

use BynqIO\CalculatieTool\Models\ProductGroup;
use BynqIO\CalculatieTool\Models\ProductCategory;
use BynqIO\CalculatieTool\Models\ProductSubCategory;
use BynqIO\CalculatieTool\Models\Chapter;
use BynqIO\CalculatieTool\Calculus\CalculationOverview;
use BynqIO\CalculatieTool\Models\ProjectType;
use BynqIO\CalculatieTool\Models\Product;
use BynqIO\CalculatieTool\Models\Activity as ProjectActivity;
use BynqIO\CalculatieTool\Models\FavoriteActivity;
use BynqIO\CalculatieTool\Models\PartType;
use BynqIO\CalculatieTool\Models\Part;
use BynqIO\CalculatieTool\Calculus\CalculationEndresult;
use BynqIO\CalculatieTool\Models\Tax;
use BynqIO\CalculatieTool\Models\Supplier;
use BynqIO\CalculatieTool\Models\Wholesale;
use BynqIO\CalculatieTool\Models\CalculationLabor;
use BynqIO\CalculatieTool\Calculus\CalculationRegister;
use BynqIO\CalculatieTool\Models\CalculationMaterial;
use BynqIO\CalculatieTool\Models\CalculationEquipment;
use BynqIO\CalculatieTool\Models\EstimateLabor;
use BynqIO\CalculatieTool\Models\EstimateMaterial;
use BynqIO\CalculatieTool\Models\EstimateEquipment;

?>

@extends('layout.master')

@section('content')
<div id="wrapper">
    <section class="container fix-footer-bottom">

        @include('wizard.index')

        @if (Session::has('success'))
        <div class="alert alert-success">
            <i class="fa fa-check-circle"></i>
            <strong>{{ Session::get('success') }}</strong>
        </div>
        @endif

        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <i class="fa fa-frown-o"></i>
            <strong>De volgende fouten zijn opgetreden:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                <li><h5 class="nomargin">{{ $error }}</h5></li>
                @endforeach
            </ul>
        </div>
        @endif

        @yield('component_buttons')

        <h2><strong>{{ ucfirst($title) }}</strong></h2>

        @yield('component_content')

    </section>
</div>
@stop
