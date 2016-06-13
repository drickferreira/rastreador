@extends('layouts.master')

@section('content')

<div class="container">
  <div class="form-signin form-register">
  <h2 class="form-signin-heading">Cadastro de Senha</h2>
  {!! Form::open([ 'action' => 'Auth\PasswordController@postReset']) !!}
    @if($errors->has())
      <div class="bg-danger">
      @foreach ($errors->all() as $error)
        {{ $error }}
      @endforeach
      </div>
    @endif
    <input type="hidden" name="token" value="{!! $token !!}">
    <div class="form-group">
      <label for="email">Informe seu Email</label>
      {!! Form::text('email', old('email'), ['class' => 'form-control', 'placeholder' => 'Email']) !!}
    </div>
    <div class="form-group">
      <label for="password">Senha</label>
      {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Senha']) !!}
    </div>
    <div class="form-group">
      <label for="password_confirmation">Confirme a Senha</label>
      {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Senha']) !!}
    </div>
    <div>
    <div class="form-group">
      {!! Form::submit('Enviar', ['class' => 'btn btn-lg btn-primary btn-block']) !!}
    </div>
    </div>
  {!! Form::close() !!}  
  </div>
</div>
@endsection