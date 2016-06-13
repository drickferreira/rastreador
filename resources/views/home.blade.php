@extends('layouts.base')
@section('main')
@if(isset($itens))
@include('layouts.dashboard', ['itens' => $itens])	
@else
@include('layouts.dashboard',['itens' => array()])
@endif
@endsection