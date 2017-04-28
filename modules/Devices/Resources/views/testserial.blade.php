@extends('layouts.base')
@section('main')
<div class="editview">
	{!! Form::open(array('class' => 'form-horizontal', 'onsubmit' => 'return false')) !!}
    <div class="form-group">
      <label for="model" class="col-md-2 control-label">Modelo</label>
      <div class="col-md-3">
				{!! getDropdown('devices','model', null, ['class'=> 'form-control'] ) !!}
      </div>
      <label for="serial" class="col-md-1 control-label">Serial</label>
      <div class="col-md-2">
      	{!! Form::text('serial', null, ['class' => 'form-control'] ) !!}
      </div>
      <input id="btnBusca" class="btn btn-default" type="button" value="Iniciar" onclick="posicoes(this.value)">
    </div>	
    <div class="form-group">
      <label for="log" class="col-md-2 control-label">Posições</label>
      <div class="col-md-6">
	      {!! Form::textarea('log', null, ['class' => 'form-control'] ) !!}
      </div>
    </div>	
  {!! Form::close() !!}
</div>
@endsection
@section('custom-js')
<script type="text/javascript">
var _token = '{{ csrf_token() }}';
var timer;
var index = 0;
var tentativa;
var textlog = $("textarea[name='log']");
var serial = $("input[name='serial']");
var model = $("select[name='model']");
function posicoes(text){
	$("#btnBusca").toggleClass('active');
	if (text == "Iniciar"){
		tentativa = 0;
		textlog.val('');
		getLast();
		$("#btnBusca").val('Parar');
	} else {
		$("#btnBusca").val('Iniciar');
		clearInterval(timer);
	}
}

function getLast(){
	tentativa++;
	var text = textlog.val();
	text += "Tentativa de Conexão " + tentativa + "\r\n";
	textlog.val(text);
	$.post(
		"/devices/lastPosition", 
		{
				"_token": _token,
				"model": model.find("option:selected").val(),
				"serial": serial.val(),
		}, function(position){
			if (position){
				index = position.date;
				var text = textlog.val();
				text += 'Última posição no sistema: ' + position.date + "\r\n";
				textlog.val(text);
				timer = setInterval(function(){
					atualiza();
				},10000);
			} else {
				if (tentativa < 5) {
					setTimeout(function(){
						getLast();
					}, 10000);
				} else {
					alert('Aparelho não reportando, tente novamente!') + "\r\n";
					setTimeout(function(){
						$("#btnBusca").toggleClass('active').val('Iniciar');
					}, 500);
				}
			}
		}, 'json'
	);
}


function atualiza(){
	$.post(
		"/devices/searchserial", 
		{
				"_token": _token,
				"model": model.find("option:selected").val(),
				"serial": serial.val(),
				"index": index,
		}, function(positions){
			for (p in positions){
				position = positions[p];
				index = position.date;
				var text = textlog.val();
				text += "Nova Posição: " + position.date + "\r\n";
				textlog.val(text);
			}
		}, 'json'
	);
}

</script>
@endsection