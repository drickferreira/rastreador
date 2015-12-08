@extends('layouts.master')

@section('custom-css')
    {!! Html::style('/assets/css/login.css') !!}
@endsection

@section('content')

    {!! Form::open([ 'action' => 'Auth\AuthController@postRegister', 'class' => 'form-signin' ]) !!}
    
    <h2 class="form-signin-heading">Cadastro</h2>

    {!! Form::openGroup('name') !!}
        {!! Form::text('name', old('name'), ['placeholder' => 'Nome']) !!}
    {!! Form::closeGroup() !!}

    {!! Form::openGroup('email') !!}
        {!! Form::email('email', old('email'), ['placeholder' => 'E-mail']) !!}
    {!! Form::closeGroup() !!}

    {!! Form::openGroup('username') !!}
        {!! Form::text('username', old('username'), ['placeholder' => 'Usuário']) !!}
    {!! Form::closeGroup() !!}

    {!! Form::openGroup('password') !!}
        {!! Form::password('password', ['placeholder' => 'Senha']) !!}
    {!! Form::closeGroup() !!}

    {!! Form::openGroup('password_confirmation') !!}
        {!! Form::password('password_confirmation', ['placeholder' => 'Repita a Senha']) !!}
    {!! Form::closeGroup() !!}

    {!! Form::openGroup('remember') !!}
        {!! Form::submit('Enviar', [ 'class' => 'btn btn-lg btn-primary btn-block' ]) !!}
    {!! Form::closeGroup() !!}


    {!! Form::close() !!}


</form>
@endsection