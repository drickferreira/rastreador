@extends('layouts.base')

@section('main')
@include('companies::layouts.menu', ['buttons' => ['edit', 'delete', 'cancel', '|', 'list', 'new'], 'id' => $company->id])
<div class="editview">
    {!! Form::config(array(
                'class' => 'form-horizontal',
                'columns' => 4,
                'labelWidth' => 1,
                'objectWidth' => 2, 
                'labelOptions' => [],
                'objectOptions' => [],
            ))!!}

    {!! Form::open() !!}
    
    {!! Form::openGroup('name') !!}
        {!! Form::text('name', $company->name, ['label'=> 'Nome', 'extend' => 3, 'readonly']) !!}
        {!! Form::text('cnpj', $company->cnpj, ['label'=> 'CNPJ', 'readonly']) !!}
        {!! Form::text('insc', $company->insc, ['label'=> 'Insc. Est', 'readonly']) !!}
    {!! Form::closeGroup() !!}
    {!! Form::openGroup('address') !!}
    		{!! Form::text('address', old('address'), ['label'=> 'Endereço', 'extend' => 3, 'readonly']) !!}
    		{!! Form::text('number', old('number'), ['label'=> 'Número', 'readonly']) !!}
    		{!! Form::text('comp', old('comp'), ['label'=> 'Complemento', 'readonly']) !!}
    {!! Form::closeGroup() !!}
    {!! Form::openGroup('bairro') !!}
    		{!! Form::text('quarter', old('quarter'), ['label'=> 'Bairro', 'extend' => 3, 'readonly']) !!}
    		{!! Form::text('city', old('city'), ['label'=> 'Cidade', 'extend' => 3, 'readonly']) !!}
    {!! Form::closeGroup() !!}
    {!! Form::openGroup('pais') !!}
    		{!! Form::text('state', old('state'), ['label'=> 'Estado', 'readonly']) !!}
    		{!! Form::text('country', old('country'), ['label'=> 'País', 'readonly']) !!}
    		{!! Form::text('postalcode', old('postalcode'), ['label'=> 'CEP', 'readonly']) !!}
    {!! Form::closeGroup() !!}
    {!! Form::openGroup('phone') !!}
        {!! Form::text('phone1', old('phone1'), ['label'=> 'Telefone', 'readonly']) !!}
        {!! Form::text('phone2', old('phone2'), ['label'=> 'Telefone', 'readonly']) !!}
        {!! Form::text('email', old('email'), ['label'=> 'Email', 'extend' => 3, 'readonly']) !!}
    {!! Form::closeGroup() !!}

    {!! Form::close() !!}
</div>
@endsection