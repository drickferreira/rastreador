<!-- Sidebar -->
<div class="col-sm-3 sidebar collapse in">
    <!-- Left column -->
    <ul class="nav nav-stacked collapse in" id="userMenu">
        <li class="active"> <a href="/"><i class="glyphicon glyphicon-home"></i> Home</a></li>
        <li><a href="/devices"><i class="glyphicon glyphicon-cog"></i> Aparelhos</a></li>
        <li><a href="/positions"><i class="glyphicon glyphicon-map-marker"></i> Posições</span></a></li>
        <li><a href="#" onclick="loadXml()"><i class="glyphicon glyphicon-refresh"></i> Processar Arquivos XML</a></li>
        <li><a href="/mail"><i class="glyphicon glyphicon-send"></i> Enviar email</span></a></li>
        <li><a href="{!! action('Auth\AuthController@getLogout') !!}"><i class="glyphicon glyphicon-off"></i> Logout</a></li>
    </ul>
<hr>
</div>
<script type="text/javascript" async defer>
    function loadXml()
    {
        swal(
        {   
            title: "Atenção!",
            text: "O processamento dos Arquivos pode demorar alguns instantes... Confirma?",   
            type: "info",   
            showCancelButton: true,   
            closeOnConfirm: false,   
            showLoaderOnConfirm: true, 
        }, function()
        {   
            $.get(
                "/maps/create", 
                {
                    "_token": '{{csrf_token()}}',
                }, function(data){
                    //console.log(data);
                    swal("Finalizado!", data + ' Posições importadas!'); 
                }
            );
        });
    }
</script>
<!-- /Sidebar -->