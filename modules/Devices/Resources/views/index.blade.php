@extends('layouts.base')
@section('main')
@if(Session::has('flash_message'))
<div class="alert alert-success alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  {!! session('flash_message') !!}
</div>
@endif
<div class="listview">
{!! $filter !!}
{!! $grid !!} 
</div>
@endsection
@section('custom-js')
@parent
<script type="text/javascript" defer="defer">
function active(id){
	swal(
	{   
			title: "Atenção!",
			text: "Confirma a Mudança de Status?",   
			type: "warning",   
			showCancelButton: true,   
			closeOnConfirm: false,
			confirmButtonText: "Confirmar!",   
	}, function()	{
		window.location.assign('http://' + window.location.hostname + '/devices/active/' + id);
	});
}
</script>
@endsection
