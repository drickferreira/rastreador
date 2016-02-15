@extends('layouts.base')

@section('main')
@include('user.layouts.menu', ['buttons' => ['save', 'cancel']])

<div class="editview">
        {!! Form::loadConfig('2column') !!}

        {!! Form::open([ 'route' => 'user.store', ]) !!}

        {!! Form::openGroup('name') !!}
	        {!! Form::select('company_id', $options, old('company_id'), ['label'=> 'Empresa']) !!}
        {!! Form::closeGroup() !!}

        {!! Form::openGroup('name') !!}
            {!! Form::text('name', old('name'), ['label'=> 'Nome', 'placeholder' => 'Nome']) !!}
		        {!! getDropdown('roles','role', old('role'), ['label'=> 'Tipo de Usuário'] ) !!}
        {!! Form::closeGroup() !!}

        {!! Form::openGroup('email') !!}
            {!! Form::text('username', old('username'), ['label'=> 'Usuário', 'placeholder' => 'Usuário']) !!}
            {!! Form::email('email', old('email'), ['label'=> 'E-mail', 'placeholder' => 'E-mail']) !!}
        {!! Form::closeGroup() !!}

        {!! Form::openGroup('password') !!}
            {!! Form::password('password', ['label'=> 'Senha', 'placeholder' => 'Senha']) !!}
            {!! Form::password('password_confirmation', ['label'=> 'Confirme', 'placeholder' => 'Repita a Senha']) !!}
        {!! Form::closeGroup() !!}

        {!! Form::close() !!}
</div>
@endsection