@extends('layouts.base')
@section('main')
{!! $map['html'] !!}
@endsection
@section('custom-js')
{!! Html::script('/assets/js/scripts.js') !!}
{!! $map['js'] !!}
@endsection
