@extends('layouts.master')
@section('custom-css')
@parent
@endsection
@section('navbar')
	@include('layouts.navbar')
@endsection
@section('content')
  <div class="main">
    @if(session()->has('message'))
    <div class="alert alert-success alert-dismissible fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      {!! session('message') !!}
    </div>
    @endif
    @if(session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      {!! session('error') !!}
    </div>
    @endif
    @if (count($errors) > 0)
    <div class="alert alert-danger alert-dismissible fade in" role="alert">
      <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif
	@yield('main')
  </div>     
@endsection
@section('custom-js')
{!! Html::script('/assets/js/jquery.mask.min.js') !!}
@parent
@endsection
