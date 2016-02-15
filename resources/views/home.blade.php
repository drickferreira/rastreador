@extends('layouts.base')

@section('main')
@include('layouts.dashboard', ['itens' => array(
	0 => [
		'class' => 'primary',
		'icon' => 'car',
		'text' => 'Veículos',
		'count' => 2,
		'link' => 'devices',
	],	
)])	
@endsection