@extends('layouts.master')

@section('custom-css')
    {!! Html::style('/assets/css/login.css') !!}
@endsection

@section('content')
    {!! Form::open([ 'action' => 'Auth\AuthController@postLogin', 'class' => 'form-signin' ]) !!}
    
    <h2 class="form-signin-heading">Login</h2>

    {!! Form::openGroup('username') !!}
        {!! Form::text('username', old('username'), ['placeholder' => 'Usuário']) !!}
    {!! Form::closeGroup() !!}

    {!! Form::openGroup('password') !!}
        {!! Form::password('password', ['placeholder' => 'Senha']) !!}
    {!! Form::closeGroup() !!}

    {!! Form::openGroup('remember')!!}
        {!! Form::checkbox('remember')!!}
        {!! Form::label('remember','Lembrar-me')!!}
    {!! Form::closeGroup()!!}

    {!! Form::openGroup('remember') !!}
        {!! Form::submit('Login', [ 'class' => 'btn btn-lg btn-primary btn-block' ]) !!}
    {!! Form::closeGroup() !!}


    {!! Form::close() !!}
@endsection