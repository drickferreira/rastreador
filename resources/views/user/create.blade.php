@extends('layouts.base')
@section('main')
<div class="col-md-4 col-md-offset-4 editview">
  {!! Form::open([ 'action' => 'Auth\AuthController@postRegister']) !!}
  
    @if($errors->has())
      <div class="bg-danger">
      @foreach ($errors->all() as $error)
        {{ $error }}
      @endforeach
      </div>
    @endif
  
    <div class="form-group">
      <label for="name">Nome</label>
      {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => 'Nome']) !!}
    </div>
    <div class="form-group">
      <label for="name">E-mail</label>
      {!! Form::text('email', old('email'), ['class' => 'form-control', 'placeholder' => 'E-mail']) !!}
    </div>
    @if (Auth::user()->isAdmin())
      {!! Form::hidden('company_id', Auth::user()->company_id) !!}
    @else
    <div class="form-group">
      <label for="company_id">Empresa</label>
      {!! Form::select('company_id', $companies, ['class' => 'form-control']) !!}
    </div>
    @endif
    <div class="form-group">
      {!! Form::submit('Enviar', ['class' => 'btn btn-lg btn-primary btn-block']) !!}
    </div>
  {!! Form::close() !!}
</div>
@endsection