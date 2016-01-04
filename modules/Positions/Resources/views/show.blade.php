@extends('layouts.base')
@section('main')
{!! $map['html'] !!}
@endsection
@section('custom-js')
{!! $map['js'] !!}
{!! Html::script('/assets/js/map.js') !!}
@endsection
