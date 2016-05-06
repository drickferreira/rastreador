@extends('layouts.base')
@section('main')
@include('layouts.dashboard', ['itens' => $itens])	
@endsection