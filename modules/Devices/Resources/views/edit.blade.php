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
        {!! Form::text('name', old('name'), ['label'=> 'Placa', 'maxlength' => '8']) !!}
        {!! Form::text('label', old('label'), ['label' => 'Label', 'maxlength' => '1']) !!}
        {!! Form::label('icone', 'Ícone', ['class' => 'col-md-2 control-label']) !!}
        <div class="col-md-2">   
            <div class="dropdown">
              <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
              <span id="labelDropdown">Selecione...</span>
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                @foreach($icons as $icon)
                   <li><a href="#" onclick="$('#icon').val('{{ $icon }}'); $('#labelDropdown').empty();$(this).children().clone().appendTo('#labelDropdown')" ><img height="20px" src="{{ $icon }}"></a></li>
                @endforeach
              </ul>
            </div>
            {!! Form::hidden('icon', old('icon'), ['id' => 'icon']) !!}
        </div>
    {!! Form::closeGroup() !!}

    {!! Form::openGroup('model') !!}
        {!! getDropdown('devices','model', old('model'), ['label'=> 'Modelo'] ) !!}
        {!! Form::text('serial', old('serial'), ['label'=> 'Número de Série']) !!}
    {!! Form::closeGroup() !!}

    {!! Form::close() !!}
</div>
@endsection