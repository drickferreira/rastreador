@extends('layouts.base')
@section('main')
<div id="gmap"></div>
@endsection
@section('custom-js')
<script src="http://maps.google.com/maps/api/js?libraries=geometry&v=3.22&key=AIzaSyCG32Qqxvj8OwbMWMcR2TDRsdeJ8ni0V1o">
</script>
<script src="{{ asset('assets/js/maplace.min.js') }}"></script>
{!! $map_js !!}
{!! Html::script('/assets/js/mapAll.js') !!}
<script type="text/javascript">
	var _token = '{{ csrf_token() }}';
</script>
@endsection
