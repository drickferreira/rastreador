@extends('layouts.master')

@section('content')

<div class="container">

    <div class="form-signin form-login">

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
            </div>
            {!! Form::submit('Login', [ 'class' => 'btn btn-lg btn-primary btn-block']) !!}

        {!! Form::close() !!}

    </div>
</div>
@endsection