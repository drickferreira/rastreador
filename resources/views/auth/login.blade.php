@extends('layouts.master')
@section('content')
<div class="container">
  @if($_SERVER['HTTP_HOST'] == 'rastreador.admassistencia.com.br')
        <div class="adm-bar">
          <div class="adm-header">
            <div class="adm-logo"></div>
            <span class="adm-title">Sistema de Rastreamento</span>
          </div>
        </div>
        <div class="clear"></div>
      </div>
      <div class="form-signin form-login adm-form">
  @elseif($_SERVER['HTTP_HOST'] == 'rastreador.inclusivabeneficios.com.br')
        <div class="inc-bar">
          <div class="inc-header">
            <div class="inc-logo"></div>
            <span class="inc-title">Sistema de Rastreamento</span>
          </div>
        </div>
        <div class="clear"></div>
      </div>
      <div class="form-signin form-login inc-form">
  @else
      <div class="form-signin form-login">
  @endif
        <h2 class="form-signin-heading">Login</h2>
        {!! Form::open(['action' => 'Auth\AuthController@postLogin']) !!}
            <div class="form-group">
            	<label for="username">Usuário</label>
              {!! Form::text('username', old('username'), ['class' => 'form-control', 'placeholder' => 'Usuário']) !!}
            </div>
            <div class="form-group">
            	<label for="username">Senha</label>
	            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Senha']) !!}
            </div>
            <div class="form-group">
            	<label for="username">
	              {!! Form::checkbox('remember') !!}
              Lembrar</label>
              <a class="pull-right" href="/password/email">Esqueci a senha</a>
            </div>
            {!! Form::submit('Login', [ 'class' => 'btn btn-lg btn-primary btn-block']) !!}

        {!! Form::close() !!}

    </div>
</div>
@endsection