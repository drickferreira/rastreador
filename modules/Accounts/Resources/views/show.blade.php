@extends('layouts.base')

@section('main')
@include('devices::layouts.menu', ['buttons' => ['edit', 'delete', 'cancel', '|', 'list', 'new'], 'id' => $device->id])
<div class="editview">
    {!! Form::config(array(
                'class' => 'form-horizontal',
                'columns' => 3,
                'labelWidth' => 2,
                'objectWidth' => 2, 
                'labelOptions' => [],
                'objectOptions' => [],
            ))!!}

    {!! Form::open() !!}
    
    {!! Form::openGroup('name' ) !!}
        {!! Form::text('name', $device->name , ['label'=> 'Placa', 'readonly']) !!}
        {!! Form::text('model', fieldValue('devices', $device->model), ['label'=> 'Modelo', 'readonly'] ) !!}
        {!! Form::text('serial', $device->serial, ['label'=> 'Número de Série', 'readonly']) !!}
    {!! Form::closeGroup() !!}

    {!! Form::close() !!}
</div>
@endsection