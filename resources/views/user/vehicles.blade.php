@extends('layouts.base')
@section('main')
<div class="editview">
@if($errors->has())
  <div class="bg-danger">
  @foreach ($errors->all() as $error)
    {{ $error }}
  @endforeach
  </div>
@endif
{!! $form !!}
</div>
@endsection