@extends('layouts.base')
@section('main')
<div class="editview">
{!! $form !!}
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="modalAlert">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header error">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Erro!</h4>
      </div>
      <div class="modal-body">
        <p>A Data de Instalação não pode ser maior que a data de hoje!</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Voltar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript" defer="defer">
$(document).ready(function(e) {
  $("input[type='submit']").click(function(e) {
		if ($('input[name="action"]').val() == "remove") {
			e.preventDefault();
			swal(
			{   
					title: "Atenção!",
					text: "Confirma a retirada do aparelho?",   
					type: "warning",   
					showCancelButton: true,   
					closeOnConfirm: false,
					confirmButtonText: "Confirmar!",   
			}, function()	{
				swal("Aparelho retirado!", "O aparelho foi desvinculado do veículo!", "success");
				$("form.form-horizontal").submit();
			});
		}		
  });
	$("#install_date").change(function(e) {
		var date = $(this).val().split('/'),
				vdate = date[2]+'-'+date[1]+'-'+date[0]+ ' 00:00:00';
    var install_date = new Date(vdate),
				hoje = new Date();
		if (install_date > hoje){
			$('#modalAlert').modal('show');
			$(this).val('');
		}
  });
});
</script>
@endsection