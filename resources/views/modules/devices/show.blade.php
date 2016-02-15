@extends('layouts.base')

@section('main')
@include('devices::layouts.menu', ['buttons' => ['edit', 'delete', 'cancel', '|', 'list', 'new'], 'id' => $device->id])
<div class="editview">
    {!! Form::open(array('class' => 'form-horizontal')) !!}
    <div class="form-group">
      <label for="name" class="col-sm-2 control-label">Identificação</label>
      <div class="col-sm-10">
      	{!! Form::text('name', $device->name, ['class' => 'form-control', 'readonly']) !!}
      </div>
	  </div>
    <div class="form-group">
      <label for="name" class="col-sm-2 control-label">Modelo</label>
      <div class="col-sm-10">
		    {!! Form::text('model', fieldValue('devices', $device->model), ['class' => 'form-control', 'readonly'] ) !!}
      </div>
	  </div>
    <div class="form-group">
      <label for="name" class="col-sm-2 control-label">Serial</label>
      <div class="col-sm-10">
		    {!! Form::text('serial', $device->serial, ['class' => 'form-control', 'readonly']) !!}
      </div>
	  </div>
    {!! Form::close() !!}
</div>
@endsection