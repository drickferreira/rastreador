<div class="btn-toolbar">
    <div class="btn-group btn-group-{{isset($size) ? $size : 'sm'}}">
    @foreach($buttons as $button)
        @if ($button == 'list')
            <a class="btn btn-default" href="{!! route('user.index') !!}"><i class="glyphicon glyphicon-list"></i> Listar Usuários</a>
        @elseif ($button == 'edit')
            <a class="btn btn-default" href="{!! route('user.edit', ['id' => $id]) !!}"><i class="glyphicon glyphicon-edit"></i> Editar</a>
        @elseif ($button == 'save')
            <button class="btn btn-default" onclick="document.forms[0].submit();"><i class="glyphicon glyphicon-ok"></i> Salvar</button>
        @elseif ($button == 'new')
            <a class="btn btn-default" href="{!! route('user.create') !!}"><i class="glyphicon glyphicon-plus"></i> Novo Usuário</a>
        @elseif ($button == 'cancel')
            <button class="btn btn-default" onclick="javascript:history.back();"><i class="glyphicon glyphicon-repeat"></i> Cancelar</button>
        @elseif ($button == 'delete')
            <button class="btn btn-default" onclick="confirmDelete('{!! $id !!}');"><i class="glyphicon glyphicon-remove"></i> Excluir</button>
        @elseif ($button == 'show')
            <a class="btn btn-default" href="{!! route('user.show', ['id' => $id]) !!}"><i class="glyphicon glyphicon-share"></i> Detalhes</a>
        @elseif ($button == '|')
            </div>
            <div class="btn-group btn-group-{{isset($size) ? $size : 'sm'}}">
        @endif
    @endforeach
    </div>
</div>
<script type="text/javascript">
    function confirmDelete(id){
        swal(
        {   
            title: "Confirma Exclusão?",
            text: "Você não poderá desfazer essa operação!",
            type: "warning",   
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Sim, Excluir!",   
            closeOnConfirm: false 
        }, function()
        {   
            $.post(
                '/user/' + id,
                {
                    _method : 'DELETE',
                    _token : '{{csrf_token()}}',
                },  
                function(result) {
                    swal(
                    {
                        title: "Usuário Excluído!", 
                        text: "O Usuário foi excluído.",
                        type: "success",
                        timer: 2000,
                    });
                    document.location.href = '/user';
                }
            );
            
        });
    }
</script>

 