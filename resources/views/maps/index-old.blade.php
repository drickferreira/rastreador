@extends('layouts.base')

@section('main')
<h3>Arquivos no diretório <span class="badge">{{sizeof($list)}}</span></h3>
@forelse($list as $file)
<div class="input-group">
	<span class="input-group-btn">
		<a class="btn btn-default" href="{!! route('maps.show', ['file' => $file]) !!}"><i class="glyphicon glyphicon-search"></i></a>
	</span>
	<input type="text" class="form-control" value="{{ $file }}" readonly>
</div>
@empty
	<p>Nenhum arquivo encontrado</p>
@endforelse
@endsection