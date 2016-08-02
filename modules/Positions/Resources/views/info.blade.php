@extends('layouts.base')
@section('main')
<div class="editview">
    {!! Form::open(array('class' => 'form-horizontal')) !!}
    <div class="form-group">
        <label for="data" class="col-md-2 control-label">Data</label>
        <div class="col-md-3">
        {!! Form::text('data', $position->date->format('d/m/Y'), ['class' => 'form-control', 'readonly']) !!}
        </div>
        <label for="hora" class="col-md-2 control-label">Hora</label>
        <div class="col-md-3">
        {!! Form::text('hora', $position->date->format('H:i:s'), ['class' => 'form-control', 'readonly']) !!}
        </div>
    </div>
    <div class="form-group">
        <label for="motivo" class="col-md-2 control-label">Motivo</label>
        <div class="col-md-8">
        {!! Form::text('motivo', fieldValue('transmission_reason', $position->transmission_reason), ['class' => 'form-control', 'readonly'] ) !!}
        </div>
    </div>
    <div class="form-group">
        <label for="latitude" class="col-md-2 control-label">Latitude</label>
        <div class="col-md-3">
        {!! Form::text('latitude', $position->latitude, ['class' => 'form-control', 'readonly'] ) !!}
        </div>
        <label for="longitude" class="col-md-2 control-label">Latitude</label>
        <div class="col-md-3">
        {!! Form::text('longitude', $position->longitude, ['class' => 'form-control', 'readonly'] ) !!}
        </div>
    </div>
    <div class="form-group">
        <label for="ignition" class="col-md-2 control-label">Ignição</label>
        <div class="col-md-3">
        {!! Form::text('ignition', $position->ignition ? 'Ligada': 'Desligada', ['class' => 'form-control', 'readonly'] ) !!}
        </div>
        <label for="speed" class="col-md-2 control-label">Velocidade</label>
        <div class="col-md-3">
        {!! Form::text('speed', $position->speed, ['class' => 'form-control', 'readonly'] ) !!}
        </div>
    </div>
    <div class="form-group">
	    	<label for="power_supply" class="col-md-2 control-label">Alimentação</label>
        <div class="col-md-3">
            <div class="input-group">
                <input readonly="readonly" aria-describedby="volts" class="form-control" name="power_supply" type="text" value="{{$position->power_supply}}" id="power_supply">
                <span class="input-group-addon" id="volts">Volts</span>
            </div>
        </div>
        <label for="temperature" class="col-md-2 control-label">Temperatura</label>
        <div class="col-md-3">
            <div class="input-group">
                <input readonly="readonly" aria-describedby="graus" class="form-control" name="temperature" type="text" value="{{$position->temperature}}" id="temperature">
                <span class="input-group-addon" id="graus">&#8451;</span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="panic" class="col-md-2 control-label">Pânico</label>
        <div class="col-md-3">
        {!! Form::text('panic', $position->panic ? 'Ligado': 'Desligado', ['class' => 'form-control', 'readonly'] ) !!}
        </div>
        <label for="battery_charging" class="col-md-2 control-label">Bateria Carregando</label>
        <div class="col-md-3">
        {!! Form::text('battery_charging', $position->battery_charging ? 'Sim': 'Não', ['class' => 'form-control', 'readonly'] ) !!}
        </div>
    </div>
    <div class="form-group">
        <label for="hodometer" class="col-md-2 control-label">Hodômetro</label>
        <div class="col-md-3">
        {!! Form::text('hodometer', $position->hodometer, ['class' => 'form-control', 'readonly'] ) !!}
        </div>
        <label for="battery_failure" class="col-md-2 control-label">Falha na Bateria</label>
        <div class="col-md-3">
        {!! Form::text('battery_failure', $position->battery_failure ? 'Sim': 'Não', ['class' => 'form-control', 'readonly'] ) !!}
        </div>
    </div>
    {!! Form::close() !!}
    </div>
</div>
@endsection


