<!-- Navbar -->
<div id="top-nav" class="navbar navbar-inverse navbar-static-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            {!! link_to('/', 'Easy Tracker', [ 'class' => 'navbar-brand']) !!}
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-left" id="userMenu">
                <li><a href="/"><i class="glyphicon glyphicon-home"></i> Home</a></li>
								@if (Auth::user()->isSuperAdmin())
                  <li><a href="/user"><i class="glyphicon glyphicon-user"></i> Usuários</a></li>
                  <li><a href="/companies"><i class="glyphicon glyphicon-briefcase"></i> Empresas</a></li>
                @endif
								@if (Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                  <li><a href="/devices"><i class="glyphicon glyphicon-cog"></i> Aparelhos</a></li>
                  <li><a href="/accounts"><i class="fa fa-users"></i> Clientes</a></li>
                  <li><a href="/vehicles"><i class="fa fa-car"></i> Veículos</a></li>
                @endif
                <li><a href="/positions"><i class="glyphicon glyphicon-map-marker"></i> Posições</span></a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#"><i class="glyphicon glyphicon-user"></i> {!! Auth::user()->name !!}  <span class="caret"></span></a>
                    <ul id="g-account-menu" class="dropdown-menu" role="menu">
                        <li><a href="#">Perfil</a></li>
                        <li><a href="{!! action('Auth\AuthController@getLogout') !!}"><i class="glyphicon glyphicon-lock"></i>Logout</a></li>
                    </ul>
                </li>
                 
            </ul>
        </div>
    </div>
    <!-- /container -->
</div>
<script type="text/javascript">
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
<!-- /Navbar -->