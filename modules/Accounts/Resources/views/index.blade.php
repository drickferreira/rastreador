@extends('layouts.base')
@section('main')
<div class="listview">
{!! $filter !!}
{!! $grid !!} 
</div>
@endsection