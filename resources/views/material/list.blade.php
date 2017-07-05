<?php
use \BynqIO\Dynq\Models\ProductGroup;
use \BynqIO\Dynq\Models\ProductCategory;
use \BynqIO\Dynq\Models\ProductSubCategory;
use \BynqIO\Dynq\Models\Supplier;
use \BynqIO\Dynq\Models\Product;
use \BynqIO\Dynq\Models\Wholesale;
?>

@extends('layout.master')

@section('title', 'Producten')

@push('scripts')
<script src="/plugins/summernote/summernote.min.js"></script>
<script src="/plugins/jquery.number.min.js"></script>
@endpush

@section('content')
<script type="text/javascript">
Number.prototype.formatMoney = function(c, d, t){
var n = this,
    c = isNaN(c = Math.abs(c)) ? 2 : c,
    d = d == undefined ? "." : d,
    t = t == undefined ? "," : t,
    s = n < 0 ? "-" : "",
    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};
$(document).ready(function() {
    $('#tab-supplier').click(function(e){
        sessionStorage.toggleTabMat{{Auth::user()->id}} = 'supplier';
    });
    $('#tab-material').click(function(e){
        sessionStorage.toggleTabMat{{Auth::user()->id}} = 'material';
    });
    $('#tab-favorite').click(function(e){
        sessionStorage.toggleTabMat{{Auth::user()->id}} = 'favorite';
    });
    $('#tab-favorite-activity').click(function(e){
        sessionStorage.toggleTabMat{{Auth::user()->id}} = 'favorite-activity';
    });
    if (sessionStorage.toggleTabMat{{Auth::user()->id}}){
        $toggleOpenTab = sessionStorage.toggleTabMat{{Auth::user()->id}};
        $('#tab-'+$toggleOpenTab).addClass('active');
        $('#'+$toggleOpenTab).addClass('active');
    } else {
        sessionStorage.toggleTabMat{{Auth::user()->id}} = 'supplier';
        $('#tab-supplier').addClass('active');
        $('#supplier').addClass('active');
    }
    $req = false;
    $("#search").keyup(function() {
        $val = $(this).val();
        if ($val.length > 2 && !$req) {
            var $wholesale = $('#wholesale option:selected').val();
            $req = true;
            $.post("/material/search", {query:$val,wholesale:$wholesale}, function(data) {
                if (data) {
                    $('#alllist tbody tr').remove();
                    $.each(data, function(i, item) {
                        $('#alllist tbody').append('<tr data-id="'+item.id+'"><td>'+item.description+'</td><td>'+item.unit+'</td><td>'+item.price+'</td><td>'+item.tprice+'</td><td><a href="javascript:void(0);" class="toggle-fav"><i style="color:'+(item.favorite ? '#FFD600' : '#000')+';" class="fa '+(item.favorite ? 'fa-star' : 'fa-star-o')+'"></i></a></td></tr>');
                    });
                    $req = false;
                }
            });
        }
    });
    $('#group').change(function(){
        $val = $(this).val();
        var $wholesale = $('#wholesale option:selected').val();
        $.post("/material/search", {group:$val,wholesale:$wholesale}, function(data) {
            if (data) {
                $('#alllist tbody tr').remove();
                $.each(data, function(i, item) {
                    $('#alllist tbody').append('<tr data-id="'+item.id+'"><td>'+item.description+'</td><td>'+item.unit+'</td><td>'+item.price+'</td><td>'+item.tprice+'</td><td><a href="javascript:void(0);" class="toggle-fav"><i style="color:'+(item.favorite ? '#FFD600' : '#000')+';" class="fa '+(item.favorite ? 'fa-star' : 'fa-star-o')+'"></i></a></td></tr>');
                });
                $req = false;
            }
        });
    });
    $("body").on("click", ".toggle-fav", function(){
        $curr = $(this);
        $matid = $curr.closest('tr').attr('data-id');
        $.post("/material/favorite", {matid:$matid}, function(data) {
            var json = data;
            if (json.success) {
                $curr.find('i').toggleClass('fa-star-o fa-star');
                if ($curr.find('i').css('color') == 'rgb(0, 0, 0)') {
                    $curr.find('i').css('color','#FFD600');
                } else {
                    $curr.find('i').css('color','#000');
                }
            }
        });
    });
    // $('#btn-load-csv').change(function() {
    // 	$('#upload-csv').submit();
    // });

    $('select[name="ngroup2"]').change(function(e){
        var $curThis = $(this);
        var $name = $curThis.find('option:selected').attr('data-name');
        var $value = $curThis.find('option:selected').val();

        $.get('/material/subcat/' + $name + '/' + $value, function(data) {
            $curThis.closest("tr").find("select[name='ngroup']").find('option').remove();
            $.each(data, function(idx, item){
                $curThis.closest("tr").find("select[name='ngroup']").append($('<option>', {
                    value: item.id,
                    text: item.name
                }));
            });
        });
    });

    $('.getsub').change(function(e){
        var $name = $('#group2 option:selected').attr('data-name');
        var $value = $('#group2 option:selected').val();
        var $wholesale = $('#wholesale option:selected').val();
        $.get('/material/subcat/' + $name + '/' + $value, function(data) {
            $('#group').find('option').remove();
            $.each(data, function(idx, item){
                $('#group').append($('<option>', {
                    value: item.id,
                    text: item.name
                }));
            });

            $.post("/material/search", {group:data[0].id,wholesale:$wholesale}, function(data) {
                if (data) {
                    $('#alllist tbody tr').remove();
                    $.each(data, function(i, item) {
                        $('#alllist tbody').append('<tr data-id="'+item.id+'"><td>'+item.description+'</td><td>'+item.unit+'</td><td>'+item.price+'</td><td>'+item.tprice+'</td><td><a href="javascript:void(0);" class="toggle-fav"><i style="color:'+(item.favorite ? '#FFD600' : '#000')+';" class="fa '+(item.favorite ? 'fa-star' : 'fa-star-o')+'"></i></a></td></tr>');
                    });
                    $req = false;
                }
            });

        });

    });

    $('.toggle').click(function(e){
        $id = $(this).attr('id');
        if ($(this).hasClass('active')) {
            if (sessionStorage.toggleOpen{{Auth::id()}}){
                $toggleOpen = JSON.parse(sessionStorage.toggleOpen{{Auth::id()}});
            } else {
                $toggleOpen = [];
            }
            if (!$toggleOpen.length)
                $toggleOpen.push($id);
            for(var i in $toggleOpen){
                if ($toggleOpen.indexOf( $id ) == -1)
                    $toggleOpen.push($id);
            }
            sessionStorage.toggleOpen{{Auth::id()}} = JSON.stringify($toggleOpen);
        } else {
            $tmpOpen = [];
            if (sessionStorage.toggleOpen{{Auth::id()}}){
                $toggleOpen = JSON.parse(sessionStorage.toggleOpen{{Auth::id()}});
                for(var i in $toggleOpen){
                    if($toggleOpen[i] != $id)
                        $tmpOpen.push($toggleOpen[i]);
                }
            }
            sessionStorage.toggleOpen{{Auth::id()}} = JSON.stringify($tmpOpen);
        }
    });
    if (sessionStorage.toggleOpen{{Auth::id()}}){
        $toggleOpen = JSON.parse(sessionStorage.toggleOpen{{Auth::id()}});
        for(var i in $toggleOpen){
            $('#'+$toggleOpen[i]).addClass('active').children('.toggle-content').toggle();
        }
    }

    $req = false;
    $("#mod-search").keyup(function() {
        $val = $(this).val();
        if ($val.length > 2 && !$req) {
            var $wholesale = $('#mod-wholesale option:selected').val();
            $req = true;
            $.post("/material/search", {query: $val,wholesale:$wholesale}, function(data) {
                if (data) {
                    $('#tbl-materialx tbody tr').remove();
                    $.each(data, function(i, item) {
                        $('#tbl-materialx tbody').append('<tr><td><a data-name="'+item.description+'" data-unit="'+item.punit+'" data-price="'+item.pricenum+'" href="javascript:void(0);">'+item.description+'</a></td><td>'+item.unit+'</td><td>'+item.price+'</td><td>'+item.tprice+'</td></tr>');
                    });
                    $('#tbl-materialx tbody a').on("click", onmaterialclick);
                    $req = false;
                }
            });
        }
    });
    $('#mod-group').change(function(){
        $val = $(this).val();
        var $wholesale = $('#mod-wholesale option:selected').val();
        $.post("/material/search", {group:$val,wholesale:$wholesale}, function(data) {
            if (data) {
                $('#tbl-materialx tbody tr').remove();
                $.each(data, function(i, item) {
                    $('#tbl-materialx tbody').append('<tr><td><a data-name="'+item.description+'" data-unit="'+item.punit+'" data-price="'+item.pricenum+'" href="javascript:void(0);">'+item.description+'</a></td><td>'+item.unit+'</td><td>'+item.price+'</td><td>'+item.tprice+'</td></tr>');
                });
                $('#tbl-materialx tbody a').on("click", onmaterialclick);
                $req = false;
            }
        });
    });
    $('.mod-getsub').change(function(e){
        var $name = $('#mod-group2 option:selected').attr('data-name');
        var $value = $('#mod-group2 option:selected').val();
        var $wholesale = $('#mod-wholesale option:selected').val();
        $.get('/material/subcat/' + $name + '/' + $value, function(data) {
            $('#mod-group').find('option').remove();
            $.each(data, function(idx, item){
                $('#mod-group').append($('<option>', {
                    value: item.id,
                    text: item.name
                }));
            });

            $.post("/material/search", {group:data[0].id,wholesale:$wholesale}, function(data) {
                if (data) {
                    $('#tbl-materialx tbody tr').remove();
                    $.each(data, function(i, item) {
                        $('#tbl-materialx tbody').append('<tr><td><a data-name="'+item.description+'" data-unit="'+item.punit+'" data-price="'+item.pricenum+'" href="javascript:void(0);">'+item.description+'</a></td><td>'+item.unit+'</td><td>'+item.price+'</td><td>'+item.tprice+'</td></tr>');
                    });
                    $('#tbl-materialx tbody a').on("click", onmaterialclick);
                    $req = false;
                }
            });

        });

    });

    $("button[data-target='#myModal']").click(function(e) {
        $newinputtr = $(this).closest("tr");
    });
    function onmaterialclick(e) {
        $newinputtr.find("input[name='name']").val($(this).attr('data-name'));
        $newinputtr.find("input[name='unit']").val($(this).attr('data-unit'));
        $newinputtr.find("input[name='rate']").val($(this).attr('data-price'));
        $newinputtr.find(".newrow").change();
        $('#myModal').modal('toggle');
    }

    $('.summernote').summernote({
        height: $(this).attr("data-height") || 200,
        toolbar: [
            ["style", ["bold", "italic", "underline", "strikethrough", "clear"]],
            ["para", ["ul", "ol", "paragraph"]],
            ["media", ["link", "picture"]],
        ]
    });
});
</script>

<div id="wrapper">

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
                            <textarea name="note" id="note" rows="5" class="form-control summernote"></textarea>
                            <input type="hidden" name="noteact" id="noteact" />
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal">Opslaan</button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="nameChangeModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <form method="POST" action="/favorite/rename_activity" accept-charset="UTF-8">

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
                    <button class="btn btn-primary">Opslaan</button>
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
                                <select id="mod-wholesale" class="mod-getsub form-control" style="background-color: #E5E7E9; color:#000;">
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
                                <select id="mod-group2" class="mod-getsub form-control" style="background-color: #E5E7E9; color:#000;">
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
                                <select id="mod-group" class="form-control" style="background-color: #E5E7E9; color:#000">
                                    <option value="0" selected>Selecteer Subcategorie</option>
                                    @foreach (ProductSubCategory::all() as $subcat)
                                    <option value="{{ $subcat->id }}">{{ $subcat->sub_category_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                          <input type="text" maxlength="100" id="mod-search" value="" class="form-control" placeholder="Zoek in alle producten">
                    </div>

                    <div class="table-responsive">
                        <table id="tbl-materialx" class="table table-hover">
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
                    <button class="btn btn-primary" data-dismiss="modal">Sluiten</button>
                </div>

            </div>
        </div>
    </div>

    <section class="container">

        @if (Session::has('success'))
        <div class="alert alert-success">
            <i class="fa fa-check-circle"></i>
            <strong>{{ Session::get('success') }}</strong>
        </div>
        @endif

        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <i class="fa fa-frown-o"></i>
            <strong>Fout</strong>
            @foreach ($errors->all() as $error)
                {{ $error }}
            @endforeach
        </div>
        @endif

        <div class="col-md-12">

            <div>
                <ol class="breadcrumb">
                  <li><a href="/">Dashboard</a></li>
                  <li class="active">Producten</li>
                </ol>
            <div>

            <h2><strong>Producten</strong></h2>

            <div class="pull-right">
                <div class="row">
                    <div class="col-md-3">
                        <div class="btn-group">
                            <a href="/wholesale" class="btn btn-primary"><i class="fa fa-truck"></i> Leveranciers</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tabs nomargin-top">

                <ul class="nav nav-tabs">
                    <li id="tab-supplier">
                        <a href="#supplier" data-toggle="tab"><i class="fa fa-cart-arrow-down"></i> Producten</a>
                    </li>
                    <li id="tab-material">
                        <a href="#material" data-toggle="tab"><i class="fa fa-wrench"></i> Mijn producten</a>
                    </li>
                    <li id="tab-favorite">
                        <a href="#favorite" data-toggle="tab"><i class="fa fa-star"></i> Favorieten producten</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div id="supplier" class="tab-pane">

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

                        <div class="form-group input-group-lg">
                              <input type="text" maxlength="100" id="search" value="" class="form-control" placeholder="Zoek in alle producten">
                        </div>

                        <div class="table-responsive">
                            <table id="alllist" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Omschrijving</th>
                                        <th>Eenheid</th>
                                        <th>&euro; / Eenheid</th>
                                        <th>Totaalprijs</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <td colspan="4"><center>Geen producten gevonden</center></td>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="material" class="tab-pane">
                        @if (0)
                        <div class="pull-right">
                            <form id="upload-csv" action="material/upload" method="post" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                                <label class="btn btn-primary btn-file">
                                    CSV laden <input type="file" name="csvfile" id="btn-load-csv" style="display: none;">
                                </label>
                            </form>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-2"><h4>Mijn producten</h4></div>
                        </div>

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="col-md-5">Omschrijving</th>
                                    <th class="col-md-1">Eenheid</th>
                                    <th class="col-md-1">&euro; / Eenheid</th>
                                    <th class="col-md-2">Categorie</th>
                                    <th class="col-md-2">Subcategorie</th>
                                    <th class="col-md-1">&nbsp;</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                    $mysupplier = Supplier::where('user_id', Auth::id())->first();
                                    if ($mysupplier) {
                                ?>
                                @foreach (Product::where('supplier_id', $mysupplier->id)->orderBy('id')->limit(150)->get() as $product)
                                <tr data-id="{{ $product->id }}">
                                    <td class="col-md-5"><input name="name" maxlength="255" type="text" value="{{ $product->description }}" class="form-control-sm-text dsave newrow" /></td>
                                    <td class="col-md-1"><input name="unit" maxlength="30" type="text" value="{{ $product->unit }}" class="form-control-sm-text dsave" /></td>
                                    <td class="col-md-1"><input name="rate" type="text" value="{{ number_format($product->price, 2,",",".") }}" class="form-control-sm-number dsave" /></td>
                                    <td class="col-md-2">
                                        <select name="ngroup2" class="form-control-sm-text pointer">
                                        <option value="0" selected>Selecteer Categorie</option>
                                        @foreach (ProductGroup::all() as $group)
                                        <option data-name="group" value="{{ $group->id }}">{{ $group->group_name }}</option>
                                        @foreach (ProductCategory::where('group_id', $group->id)->get() as $cat)
                                        <option data-name="cat" value="{{ $cat->id }}"> - {{ $cat->category_name }}</option>
                                        @endforeach
                                        @endforeach
                                        </select>
                                    </td>
                                    <td class="col-md-2">
                                        <select name="ngroup" class="form-control-sm-text pointer dsave">
                                        @foreach (ProductSubCategory::orderBy('sub_category_name')->get() as $subcat)
                                        <option {{ ($product->group_id == $subcat->id ? 'selected' : '') }} value="{{ $subcat->id }}">{{ $subcat->sub_category_name }}</option>
                                        @endforeach
                                        </select>
                                    </td>
                                    <td class="col-md-1 text-right">
                                        <button class="btn btn-danger btn-xs sdeleterow fa fa-times"></button>
                                    </td>
                                </tr>
                                @endforeach
                                <?php } ?>
                                <tr>
                                    <td class="col-md-5"><input name="name" maxlength="255" type="text" class="form-control-sm-text"></td>
                                    <td class="col-md-1"><input name="unit" maxlength="30" type="text" class="form-control-sm-text"></td>
                                    <td class="col-md-1"><input name="rate" type="text" class="form-control-sm-number"></td>
                                    <td class="col-md-2">
                                        <select name="ngroup2" class="form-control-sm-text pointer">
                                        <option value="0" selected>Selecteer Categorie</option>
                                        @foreach (ProductGroup::all() as $group)
                                        <option data-name="group" value="{{ $group->id }}">{{ $group->group_name }}</option>
                                        @foreach (ProductCategory::where('group_id', $group->id)->get() as $cat)
                                        <option data-name="cat" value="{{ $cat->id }}"> - {{ $cat->category_name }}</option>
                                        @endforeach
                                        @endforeach
                                        </select>
                                    </td>
                                    <td class="col-md-2">
                                        <select name="ngroup" class="form-control-sm-text pointer">
                                        <option value="0">Selecteer</option>
                                        @foreach (ProductSubCategory::orderBy('sub_category_name')->get() as $subcat)
                                        <option value="{{ $subcat->id }}">{{ $subcat->sub_category_name }}</option>
                                        @endforeach
                                        </select>
                                    </td>
                                    <td class="col-md-1 text-right">
                                        <button class="btn btn-primary btn-xs dsave">Opslaan</button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr></tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="favorite" class="tab-pane">
                        <div class="row">
                            <div class="col-md-2"><h4>Favorieten</h4></div>
                        </div>

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="col-md-5">Omschrijving</th>
                                    <th class="col-md-1">Eenheid</th>
                                    <th class="col-md-2">&euro; / Eenheid</th>
                                    <th class="col-md-3">Categorie</th>
                                    <th class="col-md-1">&nbsp;</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if (!Auth::user()->productFavorite()->count())
                                <tr>
                                    <td colspan="5"><center>Geen favoriete werkzaamheden</center></td>
                                </tr>
                                @endif

                                @foreach (Auth::user()->productFavorite()->get() as $product)
                                <tr data-id="{{ $product->id }}">
                                    <td class="col-md-5">{{ $product->description }}</td>
                                    <td class="col-md-1">{{ $product->unit }}</td>
                                    <td class="col-md-2">{{ number_format($product->price, 2,",",".") }}</td>
                                    <td class="col-md-3">{{ ProductSubCategory::find($product->group_id)->sub_category_name }}</td>
                                    <td class="col-md-2 text-right">
                                        <button class="btn btn-danger btn-xs fdeleterow fa fa-times"></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>

        </div>

    </section>

</div>
@stop
