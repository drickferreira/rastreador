@extends('layouts.base')
@section('main')
<div class="btn-toolbar">
    <div class="btn-group btn-group-sm">
        <button class="btn btn-default" onclick="javascript:history.back();"><i class="fa fa-lg fa-undo"></i> Voltar</button>
  		  <button class="btn btn-default" onclick="theNext();"><i class="fa fa-lg fa-search"></i> Pesquisar Endereços</button>
        <button type="button" form="idLista" class="btn btn-default" onclick="validaChecked()"><i class="fa fa-lg fa-map"></i> Ver no Mapa</button>
    </div>
</div>
<div class="clearfix">&nbsp;</div>
{!! $filter !!}
<div class="listview">
  <h1>{{ $vehicle->name }}</h1>
	{!! Form::open(array('route' => 'positions.showRoute', 'id' => 'idLista')) !!}
  {!! $grid !!}
  {!! Form::close() !!}
</div>
@endsection
@section('custom-js')
{!! Html::script('/assets/js/geocode.js') !!}
@endsection