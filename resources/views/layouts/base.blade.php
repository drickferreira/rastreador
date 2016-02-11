@extends('layouts.master')

@section('custom-css')
@parent
@endsection

@section('navbar')
	@include('layouts.navbar')
@endsection

@section('content')
    <div class="main">
        @yield('main')
    </div>     
@endsection

@section('custom-js')
@parent
@endsection
