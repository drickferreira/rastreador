@extends('layouts.base')

@section('main')
@include('devices::layouts.menu', ['buttons' => ['save', 'cancel'], 'id' => $device->id])
<div class="editview">
    {!! Form::config(array(
                'class' => 'form-horizontal',
                'columns' => 3,
                'labelWidth' => 2,
                'objectWidth' => 2, 
                'labelOptions' => [],
                'objectOptions' => [],
            ))!!}

    {!! Form::model($device, array('method' => 'PUT', 'route' => ['devices.update', $device->id])) !!}
    
    {!! Form::openGroup('name') !!}
        {!! Form::text('name', old('name'), ['label'=> 'Placa' ]) !!}
        {!! getDropdown('devices','model', old('model'), ['label'=> 'Modelo'] ) !!}
    {!! Form::closeGroup() !!}
    
    {!! Form::openGroup('name') !!}
        {!! Form::text('serial', old('serial'), ['label'=> 'Número de Série']) !!}
        {!! Form::select('company_id', $options, old('company_id'), ['label'=> 'Empresa']) !!}
    {!! Form::closeGroup() !!}

    {!! Form::close() !!}
</div>
@endsection