@extends('layouts.master')

@section('navbar')
	@include('layouts.navbar')
@endsection

@section('sidebar')
	@include('layouts.sidebar')
@endsection

@section('content')
    <div class="col-sm-9">
        @yield('main')
    </div>     
@endsection

@section('custom-js')
@parent
@endsection
