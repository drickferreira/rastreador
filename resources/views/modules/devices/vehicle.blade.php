@extends('layouts.base')
@section('main')
<div class="editview">
{!! $form !!}
</div>
<script type="text/javascript" defer="defer">
$(document).ready(function(e) {
  $("form.form-horizontal").submit(function(e) {
    e.preventDefault();
		swal(
		{   
				title: "Atenção!",
				text: "Confirma a retirada do aparelho?",   
				type: "warning",   
				showCancelButton: true,   
				closeOnConfirm: false,
//				confirmButtonText: "Confirmar!",   
		}, function()
		{
			swal("Aparelho retirado!", "O aparelho foi desvinculado do veículo!", "success");
			$("form.form-horizontal").submit();
		});
		
  });
});
</script>
@endsection