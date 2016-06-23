@extends('layouts.base')
@section('main')
<div class="listview">
@if(isset($filter))
	{!! $filter !!}
@endif
{!! $grid !!} 
</div>
@endsection