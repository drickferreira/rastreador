@extends('layouts.base')
@section('main')
@if(Session::has('flash_message'))
<div class="alert alert-success alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  {!! session('flash_message') !!}
</div>
@endif
<div class="btn-toolbar clearfix">
  <div class="btn-group btn-group-sm">
    <button class="btn btn-default" onclick="javascript:history.back();"><i class="fa fa-lg fa-undo"></i> Voltar</button>
    <button class="btn btn-default" onclick="theNext();"><i class="fa fa-lg fa-search"></i> Pesquisar Endereços</button>
    <button type="button" form="idLista" class="btn btn-default" onclick="validaChecked()"><i class="fa fa-lg fa-map"></i> Ver no Mapa</button>
  </div>
</div>
<div class="listview">
  <div class="rpd-dataform inline">
    <form method="GET" action="/positions?search=1" accept-charset="UTF-8" class="form-inline" role="form">
      <div class="form-group" id="fg_name">
        <label for="plate" class="sr-only">Placa</label>
        <span id="div_plate">
        <input class="form-control" placeholder="Placa" type="text" id="plate" name="plate">
        </span>
      </div>
      <input class="btn btn-primary" type="submit" value="Buscar">
      <a href="/positions" class="btn btn-default">Limpar</a>
      <input name="search" type="hidden" value="1">
    </form>
  </div>
  {!! Form::open(array('route' => 'positions.showAllMap', 'id' => 'idLista')) !!}
  <div class="table-responsive">
  {!! $grid !!} 
	</div>
	{!! Form::close() !!}
</div>
@endsection
@section('custom-js')
{!! Html::script('/assets/js/geocode.js') !!}
@endsection