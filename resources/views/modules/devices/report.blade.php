@extends('layouts.master')
@section('content')
<div class="editview">
	<div class="btn-toolbar hidden-print" role="toolbar">
		<div class="pull-right">
    	<input class="btn btn-default" type="button" onclick="window.print();" value="Imprimir" />
      <a href="/devices/" class="btn btn-default">Fechar</a>
    </div>
  </div>
{!! $grid !!}
</div>
@endsection