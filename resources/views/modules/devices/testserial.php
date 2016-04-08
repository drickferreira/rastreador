@extends('layouts.base')
@section('main')
<div class="editview">
	{!! Form::open(array('class' => 'form-horizontal')) !!}
    <div class="form-group">
      <label for="model" class="col-md-2 control-label">Modelo</label>
      <div class="col-md-3">
				{!! getDropdown('devices','model', null, ['label'=> 'Modelo'] ) !!}
      </div>
      <label for="serial" class="col-md-2 control-label">Serial</label>
      <div class="col-md-3">
      	{!! Form::text('serial', null, ['class' => 'form-control'] ) !!}
      </div>
    </div>	{!! Form::close() !!}
</div>
@endsection


