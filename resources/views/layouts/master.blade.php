<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name=description content="Rastreamento">
	<meta name=viewport content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Rastreamento WEB 1.0</title>
	{!! Html::style('http://fonts.googleapis.com/css?family=Open+Sans') !!}
  {!! Html::style('assets/css/bootstrap.css') !!}
	{!! Html::style('assets/css/bootstrap-theme.css') !!}
	{!! Html::style('assets/css/style.css') !!}
	{!! Html::style('assets/css/sweetalert.css') !!}
	{!! Html::style('assets/css/font-awesome.min.css') !!}
  {!! Rapyd::styles() !!}
	@yield('custom-css')
  {!! Html::script('/assets/js/jquery.min.js') !!}
  {!! Html::script('/assets/js/bootstrap.min.js') !!}
  {!! Html::script('/assets/js/sweetalert.min.js') !!}
  {!! Rapyd::scripts() !!}
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
	@yield('navbar')
	<div class="container-fluid">
    @yield('content')
	</div>
@yield('custom-js')
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
 
  ga('create', 'UA-75686420-2', 'auto');
  ga('send', 'pageview');
 
</script>
</body>
</html>