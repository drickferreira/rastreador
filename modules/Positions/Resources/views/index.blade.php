@extends('layouts.base')
@section('main')
<div class="listview">
<table class="table-responsive table-striped table-hover table-condensed">
	<thead>
		<tr>
			<th width="3%"><input type="checkbox" name="todos" id="todos" ></th>
			<th width="16%">Data / Hora</th>
			<th>Endereço</th>
			<th class="text-center" width="9%">Ignição</th>
			<th class="text-right" width="11%">Velocidade</th>
			<th width="9%">Direção</th>
			<th class="text-center" width="12%">Visualizar</th>
		</tr>
	</thead>
	<tbody>
		@forelse ($positions as $position)
		    <tr>
		    	<td><input type="checkbox" name="id" value="{{ $position->id }}"></td>
		    	<td>{{ $position->date->format('d/m/Y H:i:s') }}</td>
		    	<td><small name="address" id="{{ $position->id }}"></small></td>
		    	<td class="text-center">
			    	@if($position->ignition)
			    		<span class="label label-success">Ligada</span>
			    	@else
			    		<span class="label label-default">Desligada</span>
			    	@endif
		    	</td>
		    	<td class="text-right">{{ $position->speed }} km/h</td>
		    	<td>
					<span class="label label-primary">
		    			<i class="fa fa-lg fa-arrow-{{ fieldValue('directions-class', $position->direction) }}"></i>
						{{ fieldValue('directions', $position->direction) }}
		    		</span>
		    	</td>
		    	<td class="text-center">
		    		<button class="btn btn-primary btn-xs" title="Buscar Nome da Rua" onclick="getAddress('{{ $position->id }}')">
					    <i class="fa fa-lg fa-road"></i>
					</button>
		    		<a class="btn btn-success btn-xs" title="Ver no Mapa" href="{!! route('positions.showMap', $position->id) !!}">
					    <i class="fa fa-lg fa-map"></i>
					</a>
		    		<a class="btn btn-danger btn-xs" title="Informações" href="{!! route('positions.showInfo', $position->id) !!}">
					    <i class="fa fa-lg fa-info-circle"></i>
					</a>
		    	</td>
		    </tr>
		@empty
			<tr><td colspan="4">Nenhum registro encontrado.</td></tr>
		@endforelse
	</tbody>
</table>
</div>
@endsection
@section('custom-js')
<script type="text/javascript" charset="utf-8" async defer>
	function getAddress(id)
	{
		$.get(
			"/positions/getAddress", 
			{
				"_token": '{{csrf_token()}}',
				"id": id,
			}, function(data){
				//console.log(data);
				$('#' + id).text(data);
			}
		);
	}
</script>
@endsection