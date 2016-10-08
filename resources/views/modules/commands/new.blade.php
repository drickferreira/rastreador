@extends('layouts.base')
@section('main')
<div class="editview">
  <div class="rpd-dataform">
  {!! Form::open(array('url' => '/commands/arguments', 'method' => 'get', 'class' => "form-horizontal", 'role' => "form")) !!}
      <div class="btn-toolbar" role="toolbar">
        <div class="pull-left">
          <h2>Tipo de comando</h2>
        </div>
        <div class="pull-right"> <a href="/devices" class="btn btn-default">Voltar</a> </div>
      </div>
      <br>
      <input name="device_id" type="hidden" value="{{$device->id}}">
      <div class="form-group clearfix" id="fg_device_name">
        <label for="device_name" class="col-sm-2 control-label">Aparelho</label>
        <div class="col-sm-10" id="div_device_name">
          <div>{{$device->serial}}</div>
        </div>
      </div>
      <div class="form-group clearfix" id="fg_id_command">
        <label for="id_command" class="col-sm-2 control-label">Tipo de Comando</label>
        <div class="col-sm-10" id="div_id_command">
          {!! Form::select('id_command', $commands, null, ['class' => 'form-control']) !!}
        </div>
      </div>
      <div class="btn-toolbar" role="toolbar">
        <div class="pull-left">
          {!! Form::submit('Selecionar', ['class' => 'btn btn-primary']) !!}
        </div>
      </div>
      <br>
    </form>
  </div>
</div>
@endsection