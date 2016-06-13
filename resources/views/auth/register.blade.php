@extends('layouts.master')

@section('content')

<div class="container">
    
    <div class="form-signin form-register">

        <h2 class="form-signin-heading">Cadastro</h2>

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
            {!! Form::hidden('company_id', $company_id) !!}
            {!! Form::hidden('role', $role) !!}
            {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => 'Nome']) !!}
          </div>
          <div class="form-group">
            <label for="name">E-mail</label>
            {!! Form::text('email', $email, ['class' => 'form-control', 'readonly']) !!}
          </div>
          <div class="form-group">
            <label for="username">Usuário</label>
            {!! Form::text('username', old('username'), ['class' => 'form-control', 'placeholder' => 'Usuário']) !!}
          </div>
          <div class="form-group">
            <label for="password">Senha</label>
            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Senha']) !!}
          </div>
          <div class="form-group">
            <label for="password_confirmation">Confirme a Senha</label>
            {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Senha']) !!}
          </div>
          <div class="form-group">
            {!! Form::submit('Enviar', ['class' => 'btn btn-lg btn-primary btn-block']) !!}
          </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection