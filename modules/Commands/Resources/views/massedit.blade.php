@extends('layouts.base')
@section('main')
<div class="editview">
  <div class="rpd-dataform">
    {!! Form::open(array('url' => '/commands/massdevices', 'method' => 'get', 'class' => "form-horizontal", 'role' => "form")) !!}
      <div class="btn-toolbar" role="toolbar">
        <div class="pull-left">
          <h2>{!! $name !!}</h2>
        </div>
        <div class="pull-right"> <a href="/commands/mass" class="btn btn-default">Voltar</a> </div>
      </div>
      @for($p = 0; $p < count($params); $p++)
      	{!! Form::hidden('PAR_'.$p, $params[$p]['ID']) !!}
      	@if($params[$p]['VALUE'] !== '')
          @if(is_array($params[$p]['VALUE']))
            <div class="form-group clearfix">
            <label for="VAL_{!! $p !!}" class="col-sm-2 control-label">{!! $params[$p]['LABEL'] !!}</label>
            <div class="col-sm-10">
          		{!! Form::select('VAL_'.$p, $params[$p]['VALUE'], null, ['class' => 'form-control']) !!}
            </div>
            </div>
          @else
          	{!! Form::hidden('VAL_'.$p, $params[$p]['VALUE']) !!}
          @endif
        @else
          <div class="form-group clearfix">
          <label for="VAL_{!! $p !!}" class="col-sm-2 control-label">{!! $params[$p]['LABEL'] !!}</label>
          <div class="col-sm-10">
	        	{!! Form::text('VAL_'.$p, $params[$p]['VALUE'], ['class' => 'form-control']) !!}
          </div>
          </div>
        @endif
      @endfor
      {!! Form::hidden('param_count', $p) !!}
      <div class="btn-toolbar" role="toolbar">
        <div class="pull-left">
        	{!! Form::submit('Salvar', ['class' => 'btn btn-primary']) !!}
        </div>
      </div>
      <br>
	{!! Form::close() !!}
  </div>
</div>
@endsection