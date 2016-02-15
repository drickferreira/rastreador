@extends('layouts.base')

@section('main')
@include('companies::layouts.menu', ['buttons' => ['save', 'cancel']])

<div class="editview">
    {!! Form::config(array(
                'class' => 'form-horizontal',
                'columns' => 4,
                'labelWidth' => 1,
                'objectWidth' => 2, 
                'labelOptions' => [],
                'objectOptions' => [],
            ))!!}

    {!! Form::open(['route' => 'companies.store']) !!}
    
    {!! Form::openGroup('name') !!}
        {!! Form::text('name', old('name'), ['label'=> 'Nome', 'extend' => 3]) !!}
        {!! Form::text('cnpj', old('cnpj'), ['label'=> 'CNPJ']) !!}
        {!! Form::text('insc', old('insc'), ['label'=> 'Insc. Est']) !!}
    {!! Form::closeGroup() !!}
    {!! Form::openGroup('address') !!}
    		{!! Form::text('address', old('address'), ['label'=> 'Endereço', 'extend' => 3]) !!}
    		{!! Form::text('number', old('number'), ['label'=> 'Número']) !!}
    		{!! Form::text('comp', old('comp'), ['label'=> 'Complemento']) !!}
    {!! Form::closeGroup() !!}
    {!! Form::openGroup('bairro') !!}
    		{!! Form::text('quarter', old('quarter'), ['label'=> 'Bairro', 'extend' => 3]) !!}
    		{!! Form::text('city', old('city'), ['label'=> 'Cidade', 'extend' => 3]) !!}
    {!! Form::closeGroup() !!}
    {!! Form::openGroup('pais') !!}
    		{!! Form::text('state', old('state'), ['label'=> 'Estado']) !!}
    		{!! Form::text('country', old('country'), ['label'=> 'País']) !!}
    		{!! Form::text('postalcode', old('postalcode'), ['label'=> 'CEP']) !!}
    {!! Form::closeGroup() !!}
    {!! Form::openGroup('phone') !!}
        {!! Form::text('phone1', old('phone1'), ['label'=> 'Telefone']) !!}
        {!! Form::text('phone2', old('phone2'), ['label'=> 'Telefone']) !!}
        {!! Form::text('email', old('email'), ['label'=> 'Email', 'extend' => 3]) !!}
    {!! Form::closeGroup() !!}

    {!! Form::close() !!}
</div>
@endsection