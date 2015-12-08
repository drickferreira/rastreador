<div class="container-fluid">
    <div class="row">
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Navegação</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                {!! link_to('/', 'Rastreador 1.0', [ 'class' => 'navbar-brand']) !!}
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    @if (Auth::guest())
                        <li>{!! link_to_action('Auth\AuthController@getLogin', 'Login') !!}</li>
                        <li>{!! link_to_action('Auth\AuthController@getRegister', 'Registro') !!}</li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <i class="glyphicon glyphicon-user"></i> {!! Auth::user()->name !!} 
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="#"><i class="glyphicon glyphicon-user"></i> Perfil</a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="{!! action('Auth\AuthController@getLogout') !!}"><i class="glyphicon glyphicon-off
        "></i> Logout</a>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
    </div>
</div>