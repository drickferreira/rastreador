@extends('layouts.base')
@section('main')
<div class="editview">
  <div class="container">
  	<div class="row">
		<div class="col-md-5">
    <form method="GET" accept-charset="UTF-8" class="form-horizontal" role="form">
    <div class="form-group" id="fg_status">
      <label class="col-md-2 control-label" for="status">Status</label>
      <div class="col-md-10" id="div_status">
				<select class="form-control form-control" type="select" id="status" name="status">
	      	@if (Auth::user()->isSuperAdmin())
            <option value="3">Em Manutenção</option>
            <option value="4">Em RMA</option>
            <option value="5">Inativo</option>
  	      @elseif (Auth::user()->isAdmin())
            <option value="1">Indisponível</option>
            <option value="2">Disponível</option>
    	    @endif
        </select>
      </div> </div>
    @if (Auth::user()->isSuperAdmin())
    <div class="form-group" id="fg_company_id">
      <label class="col-md-2 control-label" for="company_id">Empresa</label>
      <div class="col-md-10" id="div_company_id">
      <select class="form-control" type="select" id="company_id" name="company_id" onchange="set_company()">
        <option value="" selected="selected">Empresa</option>
        @foreach($companies as $id => $label)
	        <option value="{!! $id !!}">{!! $label !!}</option>
        @endforeach
      </select>
      </div> </div>
    @endif
   	<input type="hidden" id="company_id_h" name="company_id_h" value="{!! Auth::user()->company_id !!}"/>
    <div class="form-group">
    <div class="col-md-10 col-md-offset-2">
      <input class="btn btn-primary" id="btn_buscar" type="button" value="Buscar" onclick="pesquisar()">
      <input class="btn btn-default" id="btn_limpar" type="button" value="Limpar" onclick="location.reload();">
    </form>
    </div>
    </div>
   </div>
    <div class="hidden col-md-6" id="painel">
      <form action="/devices/exchange" method='post' id="formdevices" class="form-horizontal" role="form">
      <input name="_token" type="hidden" value="{{ csrf_token() }}">
      <div class="form-group" id="fg_status">
        <label class="col-md-2 control-label" for="status">Novo Status</label>
        <div class="col-md-10" id="div_status">
        <select class="form-control form-control" type="select" id="status_v" name="status_v">
		    @if (Auth::user()->isSuperAdmin())
          <option value="2">Disponível</option>
          <option value="4">Em RMA</option>
          <option value="5">Inativo</option>
		    @else
          <option value="0">Ativo</option>
          <option value="3">Em Manutenção</option>
        @endif
        </select>
      </div>
      </div>
    <div class="form-group">
    <div class="col-md-10 col-md-offset-2">
      <input class="btn btn-primary" type="submit" value="Enviar">
    </div>
    </div>
    </form>
    </div>
    </div>
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
						actives.each(function(index, element) {
							$('#formdevices input:hidden').filter(function(){
									return this.value==$(element).attr('data-id');
							}).remove();
            });
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
						return !text.startsWith(val);
				}).hide();
		});
});

function pesquisar(){
	$.ajax({
		async:false,
		url:"/devices/search",
		dataType:"json",
		type:'POST',
		data:{
			"_token": _token,
			"company_id" : $('#company_id_h').val(),
			"status" : $('#status option:selected').val(),
		},
		success: function(data){
			//console.log(data);
			var group = $('.list-left ul');
			for (id in data){
				group.append('<li class="list-group-item" data-id="' + id + '">' + data[id] + '</li>');
			}
		}
	});
	$("#painel").toggleClass("hidden");
	set_option();
}


function set_company(){
	$("#company_id_h").val($("#company_id option:selected").val());
}

function set_option(){
	var value = $("#status option:selected").val();
	value = value == 1 ? 0 : value;
	value = value == 2 ? 3 : value;
	$("#status_v option").show().filter(function() {
    return $(this).val() == value;
  }).hide();
	$("#status_v option").each(function(index, element) {
    if ($(this).css('display') != 'none') {
			$(this).attr("selected","selected");
			return false;
		}
  });
}
</script>
@endsection