@extends('layouts.base')

@section('main')
@include('devices::layouts.menu', ['buttons' => ['edit', 'delete', 'cancel', '|', 'list', 'new'], 'id' => $device->id])
<div class="editview">
    {!! Form::loadConfig('2column') !!}

    {!! Form::open() !!}
    
    {!! Form::openGroup('name', ['class' => 'underline']) !!}
        {!! Form::text('name', $device->name, ['label' => 'Nome', 'extend' => 'full', 'readonly']) !!}
    {!! Form::closeGroup() !!}

    {!! Form::openGroup('model') !!}
        {!! Form::text('model', fieldValue('devices', $device->model), ['label'=> 'Modelo', 'readonly'] ) !!}
        {!! Form::text('serial', $device->serial, ['label'=> 'Número de Série', 'readonly']) !!}
    {!! Form::closeGroup() !!}

    {!! Form::close() !!}
</div>
@endsection