<?php
use \Calctool\Models\ProductGroup;
use \Calctool\Models\ProductCategory;
use \Calctool\Models\ProductSubCategory;
use \Calctool\Models\Supplier;
use \Calctool\Models\Product;
use \Calctool\Models\Tax;
use \Calctool\Models\Wholesale;
use \Calctool\Models\FavoriteActivity;
use \Calctool\Models\FavoriteMaterial;
use \Calctool\Models\FavoriteLabor;
use \Calctool\Models\FavoriteEquipment;
use \Calctool\Calculus\CalculationRegister;
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
	$("body").on("change", ".newrow", function(){
		var i = 1;
		if($(this).val()){
			if(!$(this).closest("tr").next().length){
				var $curTable = $(this).closest("table");
				$curTable.find("tr:eq(1)").clone().removeAttr("data-id").find("input").each(function(){
					$(this).val("").removeClass("error-input").attr("id", function(_, id){ return id + i });
				}).end().find(".total-ex-tax, .total-incl-tax").text("").end().appendTo($curTable);
				$("button[data-target='#myModal']").on("click", function() {
					$newinputtr = $(this).closest("tr");
				});
				i++;
			}
		}
	});
	$("body").on("change", ".dsave", function(){
		var $curThis = $(this);
		if($curThis.closest("tr").attr("data-id")){
			$.post("/material/updatematerial", {
				id: $curThis.closest("tr").attr("data-id"),
				name: $curThis.closest("tr").find("input[name='name']").val(),
				unit: $curThis.closest("tr").find("input[name='unit']").val(),
				rate: $curThis.closest("tr").find("input[name='rate']").val(),
				group: $curThis.closest("tr").find("select[name='ngroup']").val()
			}, function(data){
				var json = data;
				$curThis.closest("tr").find("input").removeClass("error-input");
				if (json.success) {
					$curThis.closest("tr").attr("data-id", json.id);
				} else {
					$.each(json.message, function(i, item) {
						if(json.message['name'])
							$curThis.closest("tr").find("input[name='name']").addClass("error-input");
						if(json.message['unit'])
							$curThis.closest("tr").find("input[name='unit']").addClass("error-input");
						if(json.message['rate'])
							$curThis.closest("tr").find("input[name='rate']").addClass("error-input");
						if(json.message['group'])
							$curThis.closest("tr").find("input[select='ngroup']").addClass("error-input");
					});
				}
			}).fail(function(e){
				console.log(e);
			});
		}
	});
	$("body").on("click", ".dsave", function(){
		var flag = true;
		var $curThis = $(this);
		if($curThis.closest("tr").attr("data-id"))
			return false;
		$curThis.closest("tr").find("input").each(function(){
			if(!$(this).val())
				flag = false;
		});
		$curThis.closest("tr").find("select").each(function(){
			if($(this).val()=='0')
				flag = false;
		});
		if (flag) {
			$.post("/material/newmaterial", {
				name: $curThis.closest("tr").find("input[name='name']").val(),
				unit: $curThis.closest("tr").find("input[name='unit']").val(),
				rate: $curThis.closest("tr").find("input[name='rate']").val(),
				group: $curThis.closest("tr").find("select[name='ngroup']").val()
			}, function(data) {
				location.reload();
			}).fail(function(e){
				console.log(e);
			});
		}
	});
	$("body").on("click", ".sdeleterow", function(){
		var $curThis = $(this);
		if($curThis.closest("tr").attr("data-id"))
			$.post("/material/deletematerial", {id: $curThis.closest("tr").attr("data-id")}, function(){
				$curThis.closest("tr").hide("slow");
			}).fail(function(e) { console.log(e); });
	});
	$("body").on("click", ".fdeleterow", function(){
		var $curThis = $(this);
		if($curThis.closest("tr").attr("data-id"))
			$.post("/material/favorite", {matid: $curThis.closest("tr").attr("data-id")}, function(){
				$curThis.closest("tr").hide("slow");
			}).fail(function(e) { console.log(e); });
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

	/*
	 FavoriteActivity
	*/

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

	var $newinputtr;
	var $newinputtr2;
	$("body").on("change", ".form-control-sm-number", function(){
		$(this).val(parseFloat($(this).val().split('.').join('').replace(',', '.')).formatMoney(2, ',', '.'));
	});
	$("body").on("change", ".newrow2", function(){
		var i = 1;
		if($(this).val()){
			if(!$(this).closest("tr").next().length){
				var $curTable = $(this).closest("table");
				$curTable.find("tr:eq(1)").clone().removeAttr("data-id").find("input").each(function(){
					$(this).val("").removeClass("error-input").attr("id", function(_, id){ return id + i });
				}).end().find(".total-ex-tax").text("").end().appendTo($curTable);
				$("button[data-target='#myModal']").on("click", function() {
					$newinputtr = $(this).closest("tr");
					$newinputtr2 = $(this).closest("tr");
				});
				i++;
			}
		}
	});
	$("body").on("change", ".lsave", function(){
		var $curThis = $(this);
		if($curThis.closest("tr").attr("data-id")){
			$.post("/favorite/updatelabor", {
				id: $curThis.closest("tr").attr("data-id"),
				rate: $curThis.closest("tr").find("input[name='rate']").val(),
				amount: $curThis.closest("tr").find("input[name='amount']").val(),
			}, function(data){
				var json = data;
				$curThis.closest("tr").find("input").removeClass("error-input");
				if (json.success) {
					$curThis.closest("tr").attr("data-id", json.id);
					var rate = $curThis.closest("tr").find("input[name='rate']").val()
					rate = rate.toString().split('.').join('').replace(',', '.');
					var amount = $curThis.closest("tr").find("input[name='amount']").val().toString().split('.').join('').replace(',', '.');
					$curThis.closest("tr").find(".total-ex-tax").text('€ '+$.number(rate*amount,2,',','.'));
				} else {
					$.each(json.message, function(i, item) {
						if(json.message['name'])
							$curThis.closest("tr").find("input[name='name']").addClass("error-input");
						if(json.message['unit'])
							$curThis.closest("tr").find("input[name='unit']").addClass("error-input");
						if(json.message['rate'])
							$curThis.closest("tr").find("input[name='rate']").addClass("error-input");
						if(json.message['amount'])
							$curThis.closest("tr").find("input[name='amount']").addClass("error-input");
					});
				}
			}).fail(function(e){
				console.log(e);
			});
		}
	});
	$("body").on("blur", ".lsave", function(){
		var flag = true;
		var $curThis = $(this);
		if($curThis.closest("tr").attr("data-id"))
			return false;
		$curThis.closest("tr").find("input").each(function(){
			if(!$(this).val())
				flag = false;
		});
		if(flag){
			$.post("/favorite/newlabor", {
				rate: $curThis.closest("tr").find("input[name='rate']").val(),
				amount: $curThis.closest("tr").find("input[name='amount']").val(),
				activity: $curThis.closest("table").attr("data-id"),
			}, function(data){
				var json = data;
				$curThis.closest("tr").find("input").removeClass("error-input");
				if (json.success) {
					$curThis.closest("tr").attr("data-id", json.id);
					var rate = 1;
					var amount = $curThis.closest("tr").find("input[name='amount']").val().toString().split('.').join('').replace(',', '.');
					$curThis.closest("tr").find(".total-ex-tax").text('€ '+$.number(rate*amount,2,',','.'));
				} else {
					$.each(json.message, function(i, item) {
						if(json.message['amount'])
							$curThis.closest("tr").find("input[name='amount']").addClass("error-input");
					});
				}
			}).fail(function(e){
				console.log(e);
			});
		}
	});

	$("body").on("change", ".ddsave", function(){
		var $curThis = $(this);
		if($curThis.closest("tr").attr("data-id")){
			$.post("/favorite/updatematerial", {
				id: $curThis.closest("tr").attr("data-id"),
				name: $curThis.closest("tr").find("input[name='name']").val(),
				unit: $curThis.closest("tr").find("input[name='unit']").val(),
				rate: $curThis.closest("tr").find("input[name='rate']").val(),
				amount: $curThis.closest("tr").find("input[name='amount']").val(),
			}, function(data){
				var json = data;
				$curThis.closest("tr").find("input").removeClass("error-input");
				if (json.success) {
					$curThis.closest("tr").attr("data-id", json.id);
					var rate = $curThis.closest("tr").find("input[name='rate']").val().toString().split('.').join('').replace(',', '.');
					var amount = $curThis.closest("tr").find("input[name='amount']").val().toString().split('.').join('').replace(',', '.');
					var profit = $curThis.closest("tr").find('td[data-profit]').data('profit');
					$curThis.closest("tr").find(".total-ex-tax").text('€ '+$.number(rate*amount,2,',','.'));
					$curThis.closest("tr").find(".total-incl-tax").text('€ '+$.number(rate*amount*((100+profit)/100),2,',','.'));
					var sub_total = 0;
					$curThis.closest("tbody").find(".total-ex-tax").each(function(index){
						var _cal = parseFloat($(this).text().substring(2).split('.').join('').replace(',', '.'));
						if (_cal)
							sub_total += _cal;
					});
					$curThis.closest("table").find('.mat_subtotal').text('€ '+$.number(sub_total,2,',','.'));
					var sub_total_profit = 0;
					$curThis.closest("tbody").find(".total-incl-tax").each(function(index){
						var _cal = parseFloat($(this).text().substring(2).split('.').join('').replace(',', '.'));
						if (_cal)
							sub_total_profit += _cal;
					});
					$curThis.closest("table").find('.mat_subtotal_profit').text('€ '+$.number(sub_total_profit,2,',','.'));
				} else {
					$.each(json.message, function(i, item) {
						if(json.message['name'])
							$curThis.closest("tr").find("input[name='name']").addClass("error-input");
						if(json.message['unit'])
							$curThis.closest("tr").find("input[name='unit']").addClass("error-input");
						if(json.message['rate'])
							$curThis.closest("tr").find("input[name='rate']").addClass("error-input");
						if(json.message['amount'])
							$curThis.closest("tr").find("input[name='amount']").addClass("error-input");
					});
				}
			}).fail(function(e){
				console.log(e);
			});
		}
	});
	$("body").on("blur", ".ddsave", function(){
		var flag = true;
		var $curThis = $(this);
		if($curThis.closest("tr").attr("data-id"))
			return false;
		$curThis.closest("tr").find("input").each(function(){
			if(!$(this).val())
				flag = false;
		});
		if(flag){
			$.post("/favorite/newmaterial", {
				name: $curThis.closest("tr").find("input[name='name']").val(),
				unit: $curThis.closest("tr").find("input[name='unit']").val(),
				rate: $curThis.closest("tr").find("input[name='rate']").val(),
				amount: $curThis.closest("tr").find("input[name='amount']").val(),
				activity: $curThis.closest("table").attr("data-id"),
			}, function(data){
				var json = data;
				$curThis.closest("tr").find("input").removeClass("error-input");
				if (json.success) {
					$curThis.closest("tr").attr("data-id", json.id);
					var rate = $curThis.closest("tr").find("input[name='rate']").val().toString().split('.').join('').replace(',', '.');
					var amount = $curThis.closest("tr").find("input[name='amount']").val().toString().split('.').join('').replace(',', '.');
					var profit = $curThis.closest("tr").find('td[data-profit]').data('profit');
					$curThis.closest("tr").find(".total-ex-tax").text('€ '+$.number(rate*amount,2,',','.'));
					$curThis.closest("tr").find(".total-incl-tax").text('€ '+$.number(rate*amount*((100+profit)/100),2,',','.'));
					var sub_total = 0;
					$curThis.closest("tbody").find(".total-ex-tax").each(function(index){
						var _cal = parseFloat($(this).text().substring(2).split('.').join('').replace(',', '.'));
						if (_cal)
							sub_total += _cal;
					});
					$curThis.closest("table").find('.mat_subtotal').text('€ '+$.number(sub_total,2,',','.'));
					var sub_total_profit = 0;
					$curThis.closest("tbody").find(".total-incl-tax").each(function(index){
						var _cal = parseFloat($(this).text().substring(2).split('.').join('').replace(',', '.'));
						if (_cal)
							sub_total_profit += _cal;
					});
					$curThis.closest("table").find('.mat_subtotal_profit').text('€ '+$.number(sub_total_profit,2,',','.'));
				} else {
					$.each(json.message, function(i, item) {
						if(json.message['name'])
							$curThis.closest("tr").find("input[name='name']").addClass("error-input");
						if(json.message['unit'])
							$curThis.closest("tr").find("input[name='unit']").addClass("error-input");
						if(json.message['rate'])
							$curThis.closest("tr").find("input[name='rate']").addClass("error-input");
						if(json.message['amount'])
							$curThis.closest("tr").find("input[name='amount']").addClass("error-input");
					});
				}
			}).fail(function(e){
				console.log(e);
			});
		}
	});

	$("body").on("change", ".eesave", function(){
		var $curThis = $(this);
		if($curThis.closest("tr").attr("data-id")){
			$.post("/favorite/updateequipment", {
				id: $curThis.closest("tr").attr("data-id"),
				name: $curThis.closest("tr").find("input[name='name']").val(),
				unit: $curThis.closest("tr").find("input[name='unit']").val(),
				rate: $curThis.closest("tr").find("input[name='rate']").val(),
				amount: $curThis.closest("tr").find("input[name='amount']").val(),
			}, function(data){
				var json = data;
				$curThis.closest("tr").find("input").removeClass("error-input");
				if (json.success) {
					$curThis.closest("tr").attr("data-id", json.id);
					var rate = $curThis.closest("tr").find("input[name='rate']").val().toString().split('.').join('').replace(',', '.');
					var amount = $curThis.closest("tr").find("input[name='amount']").val().toString().split('.').join('').replace(',', '.');
					var profit = $curThis.closest("tr").find('td[data-profit]').data('profit');
					$curThis.closest("tr").find(".total-ex-tax").text('€ '+$.number(rate*amount,2,',','.'));
					$curThis.closest("tr").find(".total-incl-tax").text('€ '+$.number(rate*amount*((100+profit)/100),2,',','.'));
					var sub_total = 0;
					$curThis.closest("tbody").find(".total-ex-tax").each(function(index){
						var _cal = parseFloat($(this).text().substring(2).split('.').join('').replace(',', '.'));
						if (_cal)
							sub_total += _cal;
					});
					$curThis.closest("table").find('.equip_subtotal').text('€ '+$.number(sub_total,2,',','.'));
					var sub_total_profit = 0;
					$curThis.closest("tbody").find(".total-incl-tax").each(function(index){
						var _cal = parseFloat($(this).text().substring(2).split('.').join('').replace(',', '.'));
						if (_cal)
							sub_total_profit += _cal;
					});
					$curThis.closest("table").find('.equip_subtotal_profit').text('€ '+$.number(sub_total_profit,2,',','.'));
				} else {
					$.each(json.message, function(i, item) {
						if(json.message['name'])
							$curThis.closest("tr").find("input[name='name']").addClass("error-input");
						if(json.message['unit'])
							$curThis.closest("tr").find("input[name='unit']").addClass("error-input");
						if(json.message['rate'])
							$curThis.closest("tr").find("input[name='rate']").addClass("error-input");
						if(json.message['amount'])
							$curThis.closest("tr").find("input[name='amount']").addClass("error-input");
					});
				}
			}).fail(function(e){
				console.log(e);app/views/calc/more_closed.blade.php
			});
		}
	});
	$("body").on("blur", ".eesave", function(){
		var flag = true;
		var $curThis = $(this);
		if($curThis.closest("tr").attr("data-id"))
			return false;
		$curThis.closest("tr").find("input").each(function(){
			if(!$(this).val())
				flag = false;
		});
		if(flag){
			$.post("/favorite/newequipment", {
				name: $curThis.closest("tr").find("input[name='name']").val(),
				unit: $curThis.closest("tr").find("input[name='unit']").val(),
				rate: $curThis.closest("tr").find("input[name='rate']").val(),
				amount: $curThis.closest("tr").find("input[name='amount']").val(),
				activity: $curThis.closest("table").attr("data-id"),
			}, function(data){
				var json = data;
				$curThis.closest("tr").find("input").removeClass("error-input");
				if (json.success) {
					$curThis.closest("tr").attr("data-id", json.id);
					var rate = $curThis.closest("tr").find("input[name='rate']").val().toString().split('.').join('').replace(',', '.');
					var amount = $curThis.closest("tr").find("input[name='amount']").val().toString().split('.').join('').replace(',', '.');
					var profit = $curThis.closest("tr").find('td[data-profit]').data('profit');
					$curThis.closest("tr").find(".total-ex-tax").text('€ '+$.number(rate*amount,2,',','.'));
					$curThis.closest("tr").find(".total-incl-tax").text('€ '+$.number(rate*amount*((100+profit)/100),2,',','.'));
					var sub_total = 0;
					$curThis.closest("tbody").find(".total-ex-tax").each(function(index){
						var _cal = parseFloat($(this).text().substring(2).split('.').join('').replace(',', '.'));
						if (_cal)
							sub_total += _cal;
					});
					$curThis.closest("table").find('.equip_subtotal').text('€ '+$.number(sub_total,2,',','.'));
					var sub_total_profit = 0;
					$curThis.closest("tbody").find(".total-incl-tax").each(function(index){
						var _cal = parseFloat($(this).text().substring(2).split('.').join('').replace(',', '.'));
						if (_cal)
							sub_total_profit += _cal;
					});
					$curThis.closest("table").find('.equip_subtotal_profit').text('€ '+$.number(sub_total_profit,2,',','.'));
				} else {
					$.each(json.message, function(i, item) {
						if(json.message['name'])
							$curThis.closest("tr").find("input[name='name']").addClass("error-input");
						if(json.message['unit'])
							$curThis.closest("tr").find("input[name='unit']").addClass("error-input");
						if(json.message['rate'])
							$curThis.closest("tr").find("input[name='rate']").addClass("error-input");
						if(json.message['amount'])
							$curThis.closest("tr").find("input[name='amount']").addClass("error-input");
					});
				}
			}).fail(function(e){
				console.log(e);
			});
		}
	});

	$("body").on("click", ".ldeleterow", function(){
		var $curThis = $(this);
		if($curThis.closest("tr").attr("data-id"))
			$.post("/favorite/deletelabor", {id: $curThis.closest("tr").attr("data-id")}, function(){
				$curThis.closest("tr").find("input").val("0,00");
				$curThis.closest("tr").find(".total-ex-tax").text('€ 0,00');
			}).fail(function(e) { console.log(e); });
	});

	$("body").on("click", ".ssdeleterow", function(){
		var $curThis = $(this);
		if($curThis.closest("tr").attr("data-id"))
			$.post("/favorite/deletematerial", {id: $curThis.closest("tr").attr("data-id")}, function(){
				var body = $curThis.closest("tbody");
				var table = $curThis.closest("table");
				$curThis.closest("tr").remove();
				var sub_total = 0;
				body.find(".total-ex-tax").each(function(index){
					var _cal = parseFloat($(this).text().substring(2).split('.').join('').replace(',', '.'));
					if (_cal)
						sub_total += _cal;
				});
				table.find('.mat_subtotal').text('€ '+$.number(sub_total,2,',','.'));
				var sub_total_profit = 0;
				body.find(".total-incl-tax").each(function(index){
					var _cal = parseFloat($(this).text().substring(2).split('.').join('').replace(',', '.'));
					if (_cal)
						sub_total_profit += _cal;
				});
				table.find('.mat_subtotal_profit').text('€ '+$.number(sub_total_profit,2,',','.'));
			}).fail(function(e) { console.log(e); });
	});
	$("body").on("click", ".eedeleterow", function(){
		var $curThis = $(this);
		if($curThis.closest("tr").attr("data-id"))
			$.post("/favorite/deleteequipment", {id: $curThis.closest("tr").attr("data-id")}, function(){
				var body = $curThis.closest("tbody");
				var table = $curThis.closest("table");
				$curThis.closest("tr").remove();
				var sub_total = 0;
				body.find(".total-ex-tax").each(function(index){
					var _cal = parseFloat($(this).text().substring(2).split('.').join('').replace(',', '.'));
					if (_cal)
						sub_total += _cal;
				});
				table.find('.equip_subtotal').text('€ '+$.number(sub_total,2,',','.'));
				var sub_total_profit = 0;
				body.find(".total-incl-tax").each(function(index){
					var _cal = parseFloat($(this).text().substring(2).split('.').join('').replace(',', '.'));
					if (_cal)
						sub_total_profit += _cal;
				});
				table.find('.equip_subtotal_profit').text('€ '+$.number(sub_total_profit,2,',','.'));
			}).fail(function(e) { console.log(e); });
	});

	$("body").on("click", ".deleteact", function(e){
		e.preventDefault();
		if(confirm('Weet je het zeker?')){
			var $curThis = $(this);
			if($curThis.attr("data-id"))
				$.post("/favorite/deleteactivity", {activity: $curThis.attr("data-id")}, function(){
					$('#toggle-activity-'+$curThis.attr("data-id")).hide('slow');
				}).fail(function(e) { console.log(e); });
		}
	});
	$('.changename').click(function(e) {
		$activityid = $(this).attr('data-id');
		$activity_name = $(this).attr('data-name');
		$('#nc_activity').val($activityid);
		$('#nc_activity_name').val($activity_name);
	});

	var $notecurr;
	$('.notemod').click(function(e) {
		$notecurr = $(this);
		$curval = $(this).attr('data-note');
		$curid = $(this).attr('data-id');
		$('.summernote').code($curval);
		$('#noteact').val($curid);
	});
	$('#descModal').on('hidden.bs.modal', function() {
		$.post("/favorite/noteactivity", {activity: $('#noteact').val(), note: $('.summernote').code()}, function(){
			$notecurr.attr('data-note', $('.summernote').code());
			$('.summernote').code('');
		}).fail(function(e) { console.log(e); });
	});

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
				  <li><a href="/">Home</a></li>
				  <li class="active">Producten</li>
				</ol>
			<div>
			<br>

			<h2><strong>Producten</strong></h2>

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
					<li id="tab-favorite-activity">
						<a href="#favorite-activity" data-toggle="tab"><i class="fa fa-star-o"></i> Favorieten werkzaamheden</a>
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

					<div id="favorite-activity" class="tab-pane">
						<div class="row">
							<div class="col-md-6"><h4>Favorieten Werkzaamheden</h4></div>
						</div>

						@if (!FavoriteActivity::where('user_id', Auth::id())->count())
						<div>
						<h5>Nog geen favoriete werkzaamheden</h5>
						<ul>
								<li>Stap 1: Ga naar een <i>Calculatie</i></li>
								<li>Stap 2: Open een <i>Onderdeel</i></li>
								<li>Stap 3: Klik op de knop <i>Werkzaamheid</i></li>
								<li>Stap 4: Klik vervolgens op <i>Opslaan als Favoriet</i></li>
								<li>Stap 5: De <i>Werkzaamheid</i> zal in dit overzicht verschijnen</li>
						</ul>
						</div>
						@endif

						<?php
						foreach(FavoriteActivity::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get() as $activity) {
						?>
						<div id="toggle-activity-{{ $activity->id }}" class="toggle toggle-activity">
							<label><span>{{ $activity->activity_name }}</span></label>
							<div class="toggle-content">
								<div class="row">
									<div class="col-md-12 text-right">
										<button id="pop-{{ $activity->id }}" data-id="{{ $activity->id }}" data-note="{{ $activity->note }}" data-toggle="modal" data-target="#descModal" class="btn btn-default btn-xs notemod">Omschrijving</button>
										<div class="btn-group" role="group">
										  <button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Werkzaamheid&nbsp;&nbsp;<span class="caret"></span></button>
										  <ul class="dropdown-menu">
										    <li><a href="#" data-id="{{ $activity->id }}" data-name="{{ $activity->activity_name }}" data-toggle="modal" data-target="#nameChangeModal" class="changename">Naam wijzigen</a></li>
										    <li><a href="#" data-id="{{ $activity->id }}" class="deleteact">Verwijderen</a></li>
										  </ul>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-2"><h4>Arbeid</h4></div>
									<div class="col-md-9"></div>
								</div>

								<table class="table table-striped" data-id="{{ $activity->id }}">
									<thead>
										<tr>
											<th class="col-md-5">Omschrijving</th>
											<th class="col-md-1">&nbsp;</th>
											<th class="col-md-1">Tarief</th>
											<th class="col-md-1">Aantal</th>
											<th class="col-md-1">&nbsp;</th>
											<th class="col-md-1">Prijs</th>
											<th class="col-md-1">&nbsp;</th>
										</tr>
									</thead>

									<tbody>
										<tr data-id="{{ FavoriteLabor::where('activity_id',$activity->id)->first()['id'] }}">
											<td class="col-md-5">Arbeidsuren</td>
											<td class="col-md-1">&nbsp;</td>
											<td class="col-md-1">&nbsp;</td>
											<td class="col-md-1"><input data-id="{{ $activity->id }}" name="amount" type="text" value="{{ number_format(FavoriteLabor::where('activity_id','=', $activity->id)->first()['amount'], 2, ",",".") }}" class="form-control-sm-number labor-amount lsave" /></td>
											<td class="col-md-1">&nbsp;</td>
											<td class="col-md-1"><span class="total-ex-tax">{{ '&euro; '.number_format(FavoriteLabor::where('activity_id',$activity->id)->first()['amount'], 2, ",",".") }}</span></td>
											<td class="col-md-1 text-right"><button class="btn btn-danger ldeleterow btn-xs fa fa-times"></button></td>
										</tr>
									</tbody>
								</table>

								<div class="row">
									<div class="col-md-2"><h4>Materiaal</h4></div>
									<div class="col-md-9"></div>
								</div>

								<table class="table table-striped" data-id="{{ $activity->id }}">
									<thead>
										<tr>
											<th class="col-md-6">Omschrijving</th>
											<th class="col-md-1">Eenheid</th>
											<th class="col-md-1">&euro; / Eenh.</th>
											<th class="col-md-1">Aantal</th>
											<th class="col-md-1">Prijs</th>
											<th class="col-md-1">&nbsp;</th>
										</tr>
									</thead>

									<tbody>
										<?php $material_total = 0; ?>
										@foreach (FavoriteMaterial::where('activity_id', $activity->id)->get() as $material)
										<?php $material_total += ($material->rate * $material->amount); ?>
										<tr data-id="{{ $material->id }}">
											<td class="col-md-6"><input name="name" maxlength="100" id="name" type="text" value="{{ $material->material_name }}" class="form-control-sm-text ddsave newrow2" /></td>
											<td class="col-md-1"><input name="unit" maxlength="10" id="name" type="text" value="{{ $material->unit }}" class="form-control-sm-text ddsave" /></td>
											<td class="col-md-1"><input name="rate" id="name" type="text" value="{{ number_format($material->rate, 2,",",".") }}" class="form-control-sm-number ddsave" /></td>
											<td class="col-md-1"><input name="amount" id="name" type="text" value="{{ number_format($material->amount, 2,",",".") }}" class="form-control-sm-number ddsave" /></td>
											<td class="col-md-1"><span class="total-ex-tax">{{ '&euro; '.number_format($material->rate * $material->amount, 2,",",".") }}</span></td>
											<td class="col-md-1 text-right"">
												<button class="fa fa-book" data-toggle="modal" data-target="#myModal"></button>
												<button class="btn btn-danger btn-xs ssdeleterow fa fa-times"></button>
											</td>
										</tr>
										@endforeach
										<tr>
											<td class="col-md-6"><input name="name" maxlength="100" id="name" type="text" class="form-control-sm-text ddsave newrow2" /></td>
											<td class="col-md-1"><input name="unit" maxlength="10" id="name" type="text" class="form-control-sm-text ddsave" /></td>
											<td class="col-md-1"><input name="rate" id="name" type="text" class="form-control-sm-number ddsave" /></td>
											<td class="col-md-1"><input name="amount" id="name" type="text" class="form-control-sm-number ddsave" /></td>
											<td class="col-md-1"><span class="total-ex-tax"></span></td>
											<td class="col-md-1 text-right">
												<button class="fa fa-book" data-toggle="modal" data-target="#myModal"></button>
												<button class="btn btn-danger btn-xs ssdeleterow fa fa-times"></button>
											</td>
										</tr>
									</tbody>
									<tbody>
										<tr>
											<td class="col-md-6"><strong>Totaal</strong></td>
											<td class="col-md-1">&nbsp;</td>
											<td class="col-md-1">&nbsp;</td>
											<td class="col-md-1">&nbsp;</td>
											<td class="col-md-1"><strong class="mat_subtotal">{{ '&euro; '.number_format($material_total, 2, ",",".") }}</span></td>
											<td class="col-md-1">&nbsp;</td>
										</tr>
									</tbody>
								</table>
								
								<div class="row">
									<div class="col-md-2"><h4>Overig</h4></div>
									<div class="col-md-9"></div>
								</div>

								<table class="table table-striped" data-id="{{ $activity->id }}">
									<thead>
										<tr>
											<th class="col-md-6">Omschrijving</th>
											<th class="col-md-1">Eenheid</th>
											<th class="col-md-1">&euro; / Eenh.</th>
											<th class="col-md-1">Aantal</th>
											<th class="col-md-1">Prijs</th>
											<th class="col-md-1">&nbsp;</th>
										</tr>
									</thead>
									<tbody>
										<?php $equipment_total = 0; ?>
										@foreach (FavoriteEquipment::where('activity_id','=', $activity->id)->get() as $equipment)
										<?php $equipment_total += ($equipment->rate * $equipment->amount); ?>
										<tr data-id="{{ $equipment->id }}">
											<td class="col-md-6"><input name="name" maxlength="100" id="name" type="text" value="{{ $equipment->equipment_name }}" class="form-control-sm-text eesave newrow2" /></td>
											<td class="col-md-1"><input name="unit" maxlength="10" id="name" type="text" value="{{ $equipment->unit }}" class="form-control-sm-text eesave" /></td>
											<td class="col-md-1"><input name="rate" id="name" type="text" value="{{ number_format($equipment->rate, 2,",",".") }}" class="form-control-sm-number eesave" /></td>
											<td class="col-md-1"><input name="amount" id="name" type="text" value="{{ number_format($equipment->amount, 2,",",".") }}" class="form-control-sm-number eesave" /></td>
											<td class="col-md-1"><span class="total-ex-tax">{{ '&euro; '.number_format($equipment->rate * $equipment->amount, 2,",",".") }}</span></td>
											<td class="col-md-1 text-right">
												<button class="fa fa-book" data-toggle="modal" data-target="#myModal"></button>
												<button class="btn btn-danger btn-xs eedeleterow fa fa-times"></button>
											</td>
										</tr>
										@endforeach
										<tr>
											<td class="col-md-6"><input name="name" maxlength="100" id="name" type="text" class="form-control-sm-text eesave newrow2" /></td>
											<td class="col-md-1"><input name="unit" maxlength="10" id="name" type="text" class="form-control-sm-text eesave" /></td>
											<td class="col-md-1"><input name="rate" id="name" type="text" class="form-control-sm-number eesave" /></td>
											<td class="col-md-1"><input name="amount" id="name" type="text" class="form-control-sm-number eesave" /></td>
											<td class="col-md-1"><span class="total-ex-tax"></span></td>
											<td class="col-md-1 text-right">
												<button class="fa fa-book" data-toggle="modal" data-target="#myModal"></button>
												<button class="btn btn-danger btn-xs eedeleterow fa fa-times"></button>
											</td>
										</tr>
									</tbody>
									<tbody>
										<tr>
											<td class="col-md-6"><strong>Totaal</strong></td>
											<td class="col-md-1">&nbsp;</td>
											<td class="col-md-1">&nbsp;</td>
											<td class="col-md-1">&nbsp;</td>
											<td class="col-md-1"><strong class="equip_subtotal">{{ '&euro; '.number_format($equipment_total, 2, ",",".") }}</span></td>
											<td class="col-md-1">&nbsp;</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<?php } ?>

					</div>

				</div>

			</div>

		</div>

	</section>

</div>
@stop
