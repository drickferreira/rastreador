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
  {!! Rapyd::styles() !!}
  {!! Html::script('/assets/js/jquery.min.js') !!}
  {!! Html::script('/assets/js/bootstrap.min.js') !!}
  {!! Rapyd::scripts() !!}
</head>
<body class="no-back">
<div class="auditview">
{!! $grid !!}
</div>
</body>
</html>
