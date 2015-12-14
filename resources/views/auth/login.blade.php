@extends('layouts.master')

@section('content')

<div class="container">

    <div class="form-signin form-login">

        <h2 class="form-signin-heading">Login</h2>

        {!! Form::loadConfig('horizontal') !!}
        
        {!! Form::open(['action' => 'Auth\AuthController@postLogin']) !!}

        {!! Form::openGroup('username') !!}
            {!! Form::text('username', old('username'), ['label' => 'Usuário', 'placeholder' => 'Usuário']) !!}
        {!! Form::closeGroup() !!}

        {!! Form::openGroup('password') !!}
            {!! Form::password('password', ['label' => 'Senha', 'placeholder' => 'Senha']) !!}
        {!! Form::closeGroup() !!}

        {!! Form::openGroup('remember')!!}
            {!! Form::checkbox('remember', 1, null, ['label' => 'Lembrar', 'offset' => 2]) !!}
        {!! Form::closeGroup()!!}

        {!! Form::openGroup('login') !!}
            {!! Form::submit('Login', [ 'class' => 'btn btn-lg btn-primary btn-block', 'offset' => 2 ]) !!}
        {!! Form::closeGroup() !!}

        {!! Form::close() !!}

    </div>
</div>
@endsection