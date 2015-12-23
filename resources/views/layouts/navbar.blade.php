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
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#"><i class="glyphicon glyphicon-user"></i> {!! Auth::user()->name !!}  <span class="caret"></span></a>
                    <ul id="g-account-menu" class="dropdown-menu" role="menu">
                        <li><a href="#">Perfil</a></li>
                    </ul>
                </li>
                <li><a href="{!! action('Auth\AuthController@getLogout') !!}"><i class="glyphicon glyphicon-lock"></i> Logout</a></li>
            </ul>
        </div>
    </div>
    <!-- /container -->
</div>
<!-- /Navbar -->