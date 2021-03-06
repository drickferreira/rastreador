@extends('layouts.base')
@section('main')
<div class="editview">
    {!! Form::open(array('class' => 'form-horizontal')) !!}
    @if($device->vehicle_id != '')
	    <h1>{!! $device->serial . ' - ' . $device->Vehicle->plate !!}</h1>
    @else
	    <h1>{!! $device->serial !!}</h1>
		@endif
    <br />    
    @if($position)
    <div class="form-group">
        <label for="date" class="col-md-2 control-label">Data do Módulo</label>
        <div class="col-md-3">
        {!! Form::text('date', $position->date->format('d/m/Y H:i:s'), ['class' => 'form-control', 'readonly']) !!}
        </div>
    </div>
    <div class="form-group">
        <label for="motivo" class="col-md-2 control-label">Motivo</label>
        <div class="col-md-8">
        {!! Form::text('motivo', fieldValue('transmission_reason', $position->Info->transmission_reason), ['class' => 'form-control', 'readonly'] ) !!}
        </div>
    </div>
    <div class="form-group">
        <label for="coordenadas" class="col-md-2 control-label">Coordenadas</label>
        <div class="col-md-3">
          <div class="input-group">
          {!! Form::text('coordenadas', $position->latitude . ',' . $position->longitude, ['class' => 'form-control', 'readonly'] ) !!}
          <span class="input-group-btn">
          <a class="btn btn-success" title="Ver no Mapa" href="{!! route('positions.showMap', $position->id) !!}"><i class="fa fa-lg fa-map-marker"></i></a>
          </span>
          </div>
        </div>
        <label for="panic" class="col-md-2 control-label">Pânico</label>
        <div class="col-md-3">
        {!! Form::text('panic', $position->Info->panic ? 'Ligado': 'Desligado', ['class' => 'form-control', 'readonly'] ) !!}
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
                <input readonly="readonly" aria-describedby="volts" class="form-control" name="power_supply" type="text" value="{{$position->Info->power_supply}}" id="power_supply">
                <span class="input-group-addon" id="volts">Volts</span>
            </div>
        </div>
        <label for="temperature" class="col-md-2 control-label">Temperatura</label>
        <div class="col-md-3">
            <div class="input-group">
                <input readonly="readonly" aria-describedby="graus" class="form-control" name="temperature" type="text" value="{{$position->Info->temperature}}" id="temperature">
                <span class="input-group-addon" id="graus">&#8451;</span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="battery_charging" class="col-md-2 control-label">Bateria Carregando</label>
        <div class="col-md-3">
        {!! Form::text('battery_charging', $position->Info->battery_charging ? 'Sim': 'Não', ['class' => 'form-control', 'readonly'] ) !!}
        </div>
        <label for="battery_failure" class="col-md-2 control-label">Falha na Bateria</label>
        <div class="col-md-3">
        {!! Form::text('battery_failure', $position->Info->battery_failure ? 'Sim': 'Não', ['class' => 'form-control', 'readonly'] ) !!}
        </div>
    </div>
    <div class="form-group">
        <label for="lifetime" class="col-md-2 control-label">Tempo Ligado</label>
        <div class="col-md-3">
        {!! Form::text('lifetime', secondsToTime($position->Info->lifetime), ['aria-describedby'=> 'seconds', 'class' => 'form-control', 'readonly'] ) !!}
        </div>
        <label for="hodometer" class="col-md-2 control-label">Hodômetro</label>
        <div class="col-md-3">
        {!! Form::text('hodometer', $position->Info->hodometer, ['class' => 'form-control', 'readonly'] ) !!}
        </div>
    </div>
    <div class="form-group">
        <label for="gps_signal" class="col-md-2 control-label">Sinal GPS</label>
        <div class="col-md-3">
        {!! Form::text('gps_signal', $position->Info->gps_signal ? 'Sim': 'Não', ['class' => 'form-control', 'readonly'] ) !!}
        </div>
        <label for="gps_antenna_failure" class="col-md-2 control-label">Falha da Antena GPS</label>
        <div class="col-md-3">
        {!! Form::text('gps_antenna_failure', $position->Info->gps_antenna_failure ? 'Sim': 'Não', ['class' => 'form-control', 'readonly'] ) !!}
        </div>
    </div>
    {!! Form::close() !!}
    @else
    <div class="alert alert-danger" role="alert">Aparelho não sincronizado!</div>
    @endif
    </div>
</div>
@endsection


