@extends('layouts.master')

@section('content')

<div class="container">
    
    <div class="form-signin form-register">

    <h2 class="form-signin-heading">Cadastro</h2>

    {!! Form::loadConfig('2column') !!}

    {!! Form::open([ 'action' => 'Auth\AuthController@postRegister', ]) !!}
    

    {!! Form::openGroup('name') !!}
        {!! Form::text('name', old('name'), ['label'=> 'Nome', 'placeholder' => 'Nome', 'extend' => 'full']) !!}
    {!! Form::closeGroup() !!}

    {!! Form::openGroup('email') !!}
        {!! Form::email('email', old('email'), ['label'=> 'E-mail', 'placeholder' => 'E-mail', 'extend' => 'full']) !!}
    {!! Form::closeGroup() !!}

    {!! Form::openGroup('username') !!}
        {!! Form::text('username', old('username'), ['label'=> 'Usuário', 'placeholder' => 'Usuário', 'extend' => 'full']) !!}
    {!! Form::closeGroup() !!}

    {!! Form::openGroup('password') !!}
        {!! Form::password('password', ['label'=> 'Senha', 'placeholder' => 'Senha']) !!}
        {!! Form::password('password_confirmation', ['label'=> 'Confirme', 'placeholder' => 'Repita a Senha']) !!}
    {!! Form::closeGroup() !!}

    {!! Form::openGroup('enviar') !!}
        {!! Form::submit('Enviar', ['class' => 'btn btn-lg btn-primary btn-block', 'extend' => 'full' ]) !!}
    {!! Form::closeGroup() !!}


    {!! Form::close() !!}

    </div>
</div>
@endsection