@extends('layouts.base')
@section('custom-css')
@endsection
@section('main')
<div class="editview">
  <div class="rpd-dataform inline">
    <form method="GET" accept-charset="UTF-8" class="form-inline" role="form">
    <div class="form-group" id="fg_model">
      <label for="model" class="sr-only">Modelo</label>
      <span id="div_model">
      <select class="form-control form-control" type="select" id="model" name="model">
        <option value="" selected="selected">Modelo</option>
        @foreach($devices as $id => $label)
	        <option value="{!! $id !!}">{!! $label !!}</option>
        @endforeach
      </select>
      </span> </div>
    <div class="form-group" id="fg_hasvehicle">
      <label for="hasvehicle" class="sr-only">Atribuído</label>
      <span id="div_hasvehicle">
      <select class="form-control form-control" type="select" id="hasvehicle" name="hasvehicle">
        <option value="0">Todos</option>
        <option value="1">Com veículo</option>
        <option value="2">Sem Veículo</option>
      </select>
      </span> </div>
    <div class="form-group" id="fg_company_id">
      <label for="company_id" class="sr-only"></label>
      <span id="div_company_id">
      <select class="form-control form-control" type="select" id="company_id" name="company_id">
        <option value="" selected="selected">Empresa</option>
        @foreach($companies as $id => $label)
	        <option value="{!! $id !!}">{!! $label !!}</option>
        @endforeach
      </select>
      </span> </div>
      <input class="btn btn-primary" id="btn_buscar" type="button" value="Buscar" onclick="pesquisar()">
      <input class="btn btn-default" id="btn_limpar" type="button" value="Limpar" onclick="limpar()">
    </form>
  </div>
<div class="container">
    <br />
	<div class="row">

        <div class="dual-list list-left col-md-5">
            <div class="well text-right">
                <div class="row">
                    <div class="col-md-10">
                        <div class="input-group">
                            <span class="input-group-addon glyphicon glyphicon-search"></span>
                            <input type="text" name="SearchDualList" class="form-control" placeholder="pesquisar" />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="btn-group">
                            <a class="btn btn-default selector" title="select all"><i class="glyphicon glyphicon-unchecked"></i></a>
                        </div>
                    </div>
                </div>
                <div style="max-height: 300px;overflow-y:auto;">
                  <ul class="list-group">
                  </ul>
                </div>
            </div>
        </div>

        <div class="list-arrows col-md-1 text-center">
            <button class="btn btn-default btn-sm move-left">
                <span class="glyphicon glyphicon-chevron-left"></span>
            </button>

            <button class="btn btn-default btn-sm move-right">
                <span class="glyphicon glyphicon-chevron-right"></span>
            </button>
        </div>
				
        <div class="dual-list list-right col-md-5">
            <div class="well">
                <div class="row">
                    <div class="col-md-2">
                        <div class="btn-group">
                            <a class="btn btn-default selector" title="select all"><i class="glyphicon glyphicon-unchecked"></i></a>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <div class="input-group">
                            <input type="text" name="SearchDualList" class="form-control" placeholder="pesquisar" />
                            <span class="input-group-addon glyphicon glyphicon-search"></span>
                        </div>
                    </div>
                </div>
                <div style="max-height: 400px;overflow-y:auto;">
                
                <ul class="list-group">
                </ul>
                </div>
            </div>
        </div>
        {!! Form::open(array('url' => '/commands/createmass', 'method' => 'post', 'id' => "formdevices")) !!}
        <input class="btn btn-primary" type="submit" value="Enviar">
				{!! Form::close() !!}
	</div>
</div>
</div>
@endsection
@section('custom-js')
@parent
<script type="text/javascript">
var _token = '{{ csrf_token() }}';
$(function () {
		$('body').on('click', '.list-group .list-group-item', function () {
				$(this).toggleClass('active');
		});
		$('.list-arrows button').click(function () {
				var $button = $(this), actives = '';
				if ($button.hasClass('move-left')) {
						actives = $('.list-right ul li.active');
						actives.clone().appendTo('.list-left ul').removeClass('active');
						actives.remove();
				} else if ($button.hasClass('move-right')) {
						actives = $('.list-left ul li.active');
						actives.clone().appendTo('.list-right ul').removeClass('active');
						actives.each(function(index, element) {
							$('#formdevices').append('<input type="hidden" name="ids[]" value="' + $(element).attr('data-id') + '">');
            });
						actives.remove();
				}
		});
		$('.dual-list .selector').click(function () {
				var $checkBox = $(this);
				if (!$checkBox.hasClass('selected')) {
						$checkBox.addClass('selected').closest('.well').find('ul li:not(.active)').addClass('active');
						$checkBox.children('i').removeClass('glyphicon-unchecked').addClass('glyphicon-check');
				} else {
						$checkBox.removeClass('selected').closest('.well').find('ul li.active').removeClass('active');
						$checkBox.children('i').removeClass('glyphicon-check').addClass('glyphicon-unchecked');
				}
		});
		$('[name="SearchDualList"]').keyup(function (e) {
				var code = e.keyCode || e.which;
				if (code == '9') return;
				if (code == '27') $(this).val(null);
				var $rows = $(this).closest('.dual-list').find('.list-group li');
				var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
				$rows.show().filter(function () {
						var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
						return !~text.indexOf(val);
				}).hide();
		});
});

function pesquisar(){
	$.ajax({
		async:false,
		url:"/commands/devices",
		dataType:"json",
		type:'POST',
		data:{
			"_token": _token,
			"model" : $('#model option:selected').val(),
			"company_id" : $('#company_id option:selected').val(),
			"hasvehicle" : $('#hasvehicle option:selected').val()
		},
		success: function(data){
			console.log(data);
			var group = $('.list-left ul');
			for (id in data){
				group.append('<li class="list-group-item" data-id="' + id + '">' + data[id] + '</li>');
			}
		}
	});
}
</script>
@endsection