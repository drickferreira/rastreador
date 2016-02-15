@extends('layouts.base')
@section('main')
{!! $map['html'] !!}
<div class="editview">
    {!! Form::loadConfig('2column') !!}

    {!! Form::open() !!}
    
    {!! Form::openGroup('device') !!}
        {!! Form::text('model', fieldValue('devices', $data['type']), ['label' => 'Modelo', 'readonly']) !!}
        {!! Form::text('serial', $data['serial'], ['label'=> 'Número de Série', 'readonly']) !!}
    {!! Form::closeGroup() !!}

    {!! Form::openGroup('position') !!}
        {!! Form::text('latitude', $data['lat'], ['label'=> 'Latitude', 'readonly'] ) !!}
        {!! Form::text('longitude', $data['long'], ['label'=> 'Longitude', 'readonly'] ) !!}
    {!! Form::closeGroup() !!}

    {!! Form::openGroup('aditional') !!}
        {!! Form::text('data', $data['data'], ['label'=> 'Data e Hora', 'readonly'] ) !!}
        {!! Form::text('ipv4', $data['ip'], ['label'=> 'Ipv4', 'readonly'] ) !!}
    {!! Form::closeGroup() !!}

    {!! Form::close() !!}
</div>

@endsection

@section('custom-js')
{!! Html::script('/assets/js/scripts.js') !!}
{!! $map['js'] !!}
@endsection
