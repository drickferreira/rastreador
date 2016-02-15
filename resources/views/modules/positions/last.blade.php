@extends('layouts.base')
@section('main')
<div class="btn-toolbar">
    <div class="btn-group btn-group-sm">
        <button class="btn btn-default" onclick="javascript:history.back();"><i class="fa fa-lg fa-undo"></i> Voltar</button>
        <button type="button" form="idLista" class="btn btn-default" onclick="validaChecked()"><i class="fa fa-lg fa-map"></i> Ver no Mapa</button>
    </div>
</div>
<div class="clearfix">&nbsp;</div>
<div class="listview">
<h1>{{ $vehicle->name }}</h1>
{!! Form::open(array('route' => 'positions.showRoute', 'id' => 'idLista')) !!}
	<table class="table-responsive table-striped table-hover table-condensed">
		<thead>
			<tr>
				<th width="3%"><input type="checkbox" name="todos" id="todos" onclick="selectTodos()"></th>
				<th width="14%">Data / Hora</th>
				<th>Endereço</th>
				<th class="text-center" width="5%">Ignição</th>
				<th class="text-right" width="9%">Velocidade</th>
				<th class="text-center" width="12%">Visualizar</th>
			</tr>
		</thead>
		<tbody>
			@forelse ($positions as $position)
			    <tr>
			    	<td><input type="checkbox" name="ids[]" value="{{ $position->id }}" onclick="checkSelected()"></td>
			    	<td>{{ $position->date->format('d/m/Y H:i:s') }}</td>
			    	<td><span class="address" geo-lat="{{ $position->latitude }}" geo-lng="{{ $position->longitude }}"></span></td>
			    	<td class="text-center">
				    	@if($position->ignition)
				    		<i class="fa fa-lg fa-circle on"></i>
				    	@else
				    		<i class="fa fa-lg fa-circle off"></i>
				    	@endif
			    	</td>
			    	<td class="text-right">{{ $position->speed }} km/h</td>
			    	<td class="text-center">
			    		<a class="btn btn-success btn-xs" title="Ver no Mapa" href="{!! route('positions.showMap', $position->id) !!}">
						    <i class="fa fa-lg fa-map-marker"></i>
						</a>
			    		<a class="btn btn-danger btn-xs" title="Informações" href="{!! route('positions.showInfo', $position->id) !!}">
						    <i class="fa fa-lg fa-info"></i>
						</a>
			    	</td>
			    </tr>
			@empty
				<tr><td colspan="3">Nenhum registro encontrado.</td></tr>
			@endforelse
		</tbody>
	</table>
{!! Form::close() !!}
</div>
@endsection
@section('custom-js')
{!! Html::script('/assets/js/geocode.js') !!}
@endsection