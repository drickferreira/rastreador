@extends('layouts.base')
@section('custom-css')
{!! Html::style('assets/leaflet/leaflet.css') !!}
@endsection
@section('main')
<div id="map"></div>
@endsection
@section('custom-js')
{!! Html::script('assets/leaflet/leaflet.js') !!}
<script type="text/javascript">
	var _token = '{{ csrf_token() }}';
	var markers = [];	
	$(document).ready(function(e) {
		var map = L.map('map');
		L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a>',
            maxZoom: 18
         }).addTo(map);
		var latlngBounds = [];
		@foreach($locations as $loc)
			var latlng = new L.LatLng({{ $loc['lat'] }}, {{ $loc['lon'] }});
			var marker = L.marker(latlng).addTo(map);
			marker.bindPopup('{!! $loc['html'] !!}');
			markers.push(marker);
			latlngBounds.push(latlng);
		@endforeach
		var bounds = new L.latLngBounds(latlngBounds);
		map.fitBounds(bounds, { padding: [50, 50] });
	});
</script>
@endsection
