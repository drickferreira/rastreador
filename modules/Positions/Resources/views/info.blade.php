@extends('layouts.base')
@section('main')
<div class="editview">
    {!! Form::loadConfig('2column') !!}

    {!! Form::open() !!}
    
    {!! Form::openGroup('data') !!}
        {!! Form::text('data', $position->date->format('d/m/Y'), ['label' => 'Data', 'readonly']) !!}
        {!! Form::text('hora', $position->date->format('H:i:s'), ['label' => 'Hora', 'readonly']) !!}
    {!! Form::closeGroup() !!}
    {!! Form::openGroup('motivo') !!}
        {!! Form::text('motivo', fieldValue('transmission_reason', $position->transmission_reason), ['label'=> 'Motivo', 'readonly', 'extend' => 'full'] ) !!}
    {!! Form::closeGroup() !!}

    {!! Form::openGroup('position') !!}
        {!! Form::text('latitude', $position->latitude, ['label'=> 'Latitude', 'readonly'] ) !!}
        {!! Form::text('longitude', $position->longitude, ['label'=> 'Longitude', 'readonly'] ) !!}
    {!! Form::closeGroup() !!}

    {!! Form::openGroup('direction') !!}
        {!! Form::text('direction', fieldValue('directions', $position->direction), ['label'=> 'Direção', 'readonly'] ) !!}
        {!! Form::text('speed', $position->speed, ['label'=> 'Velocidade', 'readonly'] ) !!}
    {!! Form::closeGroup() !!}
    {!! Form::openGroup('power') !!}
        <label for="power_supply" class="col-md-2 control-label">Alimentação</label>
        <div class="col-md-4">
            <div class="input-group">
                <input readonly="readonly" aria-describedby="volts" class="form-control" name="power_supply" type="text" value="{{$position->power_supply}}" id="power_supply">
                <span class="input-group-addon" id="volts">Volts</span>
            </div>
        </div>
        <label for="temperature" class="col-md-2 control-label">Temperatura</label>
        <div class="col-md-4">
            <div class="input-group">
                <input readonly="readonly" aria-describedby="graus" class="form-control" name="temperature" type="text" value="{{$position->temperature}}" id="temperature">
                <span class="input-group-addon" id="graus">&#8451;</span>
            </div>
        </div>
    {!! Form::closeGroup() !!}
    {!! Form::openGroup('ignition') !!}
        {!! Form::text('ignition', $position->ignition ? 'Ligada': 'Desligada', ['label'=> 'Ignição', 'readonly'] ) !!}
        {!! Form::text('panic', $position->panic ? 'Ligado': 'Desligado', ['label'=> 'Pânico', 'readonly'] ) !!}
    {!! Form::closeGroup() !!}


    {!! Form::close() !!}
</div>
@endsection


