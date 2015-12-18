@extends('layouts.base')

@section('main')
@include('devices::layouts.menu', ['buttons' => ['save', 'cancel'], 'id' => $device->id])
<div class="editview">
    {!! Form::loadConfig('2column') !!}

    {!! Form::model($device, array('method' => 'PUT', 'route' => ['devices.update', $device->id])) !!}
    
    {!! Form::openGroup('name') !!}
        {!! Form::text('name', old('name'), ['label'=> 'Nome', 'extend' => 'full']) !!}
    {!! Form::closeGroup() !!}

    {!! Form::openGroup('model') !!}
        {!! getDropdown('devices','model', old('model'), ['label'=> 'Modelo'] ) !!}
        {!! Form::text('serial', old('serial'), ['label'=> 'Número de Série']) !!}
    {!! Form::closeGroup() !!}

    {!! Form::close() !!}
</div>
@endsection