@extends('layouts.base')
@section('main')
<div class="hidden-print">
<div class="btn-toolbar">
    <div class="btn-group btn-group-sm">
        <button class="btn btn-default" onclick="javascript:history.back();"><i class="fa fa-lg fa-undo"></i> Voltar</button>
  		  <button class="btn btn-default" onclick="theNext();"><i class="fa fa-lg fa-search"></i> Pesquisar Endereços</button>
    </div>
</div>
<div class="clearfix">&nbsp;</div>
	{!! $filter !!}
</div>
<div class="listview">
  <h1>{{ $vehicle->name }}</h1>
  {!! $grid !!}
</div>
@endsection
@section('custom-js')
{!! Html::script('/assets/js/geocode.js') !!}
<script type="text/javascript">
function toExcel()
{
	window.open('data:application/vnd.ms-excel;charset=UTF-8,' + escape("<table>" + $('#tabelaPosicoes').html() + "</table>"));
}
</script>
@endsection