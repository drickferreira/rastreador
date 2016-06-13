@extends('layouts.base')
@section('main')
<div class="btn-toolbar clearfix">
  <div class="btn-group btn-group-sm">
    <button class="btn btn-default" onclick="javascript:history.back();"><i class="fa fa-lg fa-undo"></i> Voltar</button>
    <button class="btn btn-default" onclick="theNext();"><i class="fa fa-lg fa-search"></i> Pesquisar Endereços</button>
    <button type="button" form="idLista" class="btn btn-default" onclick="validaChecked()"><i class="fa fa-lg fa-map"></i> Ver no Mapa</button>
  </div>
</div>
<div class="listview clearfix">
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
  <table class="table table-striped table-hover table-condensed">
    <thead>
      <tr>
        <th><input type="checkbox" name="todos" id="todos" onclick="selectTodos()"></th>
        <th>Veículo</th>
        <th>Data / Hora</th>
        <th>Endereço</th>
        <th class="text-center">Ignição</th>
        <th class="text-right">Velocidade</th>
        <th class="text-center">Visualizar</th>
      </tr>
    </thead>
    <tbody>
    
    @forelse ($positions as $position)
    <tr>
      <td><input type="checkbox" name="ids[]" value="{{ $position->vehicle_id }}" onclick="checkSelected()"></td>
      <td><a href="{!! route('positions.showLast', $position->vehicle_id) !!}">{{$position->name}}</a></td>
      <td>{{ $position->date->format('d/m/Y H:i:s') }}</td>
      <td><span id="{{ $position->id }}" class="address" geo-lat="{{ $position->latitude }}" geo-lng="{{ $position->longitude }}"></span></td>
      <td class="text-center">@if($position->ignition) <i class="fa fa-lg fa-circle on"></i> @else <i class="fa fa-lg fa-circle off"></i> @endif </td>
      <td class="text-right">{{ $position->speed }} km/h</td>
      <td class="text-center"><div class="btn-group btn-group-xs"><a class="btn btn-success" title="Ver no Mapa" href="{!! route('positions.showMap', $position->id) !!}"><i class="fa fa-lg fa-map-marker"></i></a><a target="_blank" class="btn btn-danger" title="Visualizar no Google Maps" href="https://www.google.com/maps?q={{ $position->latitude }},{{ $position->longitude }}"><i class="fa fa-lg fa-google"></i></a><a class="btn btn-primary" title="Últimas Posições" href="{!! route('positions.showLast', $position->vehicle_id) !!}"> <i class="fa fa-lg fa-list-ol"></i></a><button type="button" class="btn btn-info" title="Buscar Endereço" onclick="searchAddr('{{ $position->id }}')"><i class="fa fa-lg fa-search"></i></button></div></td>
    </tr>
    @empty
    <tr>
      <td colspan="4">Nenhum registro encontrado.</td>
    </tr>
    @endforelse
      </tbody>
    
  </table>
  </div>
  {!! Form::close() !!} </div>
@endsection
@section('custom-js')
{!! Html::script('/assets/js/geocode.js') !!}
@endsection