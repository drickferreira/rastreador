@extends('layouts.base')
@section('main')
<div class="listview">
<table class="table table-striped table-hover table-condensed">
	<thead>
		<tr>
			<th>Nome</th>
			<th>Modelo</th>
			<th>Serial</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		@forelse ($devices as $device)
		    <tr>
		    	<td>{{ $device->name }}</td>
		    	<td>{{ fieldValue('devices', $device->model) }}</td>
		    	<td>{{ $device->serial }}</td>
		    	<td class="text-center">
					<a class="btn btn-primary btn-xs" href="{!! route('positions.index', ['device_id' => $device->id]) !!}">
				    <i class="left glyphicon glyphicon-road"></i>
				    Últimas Posições
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