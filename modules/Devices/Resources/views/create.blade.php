@extends('layouts.base')

@section('main')
@include('devices::layouts.menu', ['buttons' => ['save', 'cancel']])

<div class="editview">
    {!! Form::loadConfig('2column') !!}

    {!! Form::open(['route' => 'devices.store']) !!}
    
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

