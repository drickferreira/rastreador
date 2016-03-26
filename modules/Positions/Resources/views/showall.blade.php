@extends('layouts.base')
@section('custom-css')
{!! Html::style('assets/leaflet/leaflet.css') !!}
{!! Html::style('assets/css/easy-button.css') !!}
@endsection
@section('main')
<div id="map"></div>
@endsection
@section('custom-js')
{!! Html::script('assets/leaflet/leaflet.js') !!}
{!! Html::script('assets/js/easy-button.js') !!}
<script type="text/javascript">
	var _token = '{{ csrf_token() }}';
	var markers = [];
	var markerGroup = L.layerGroup();	
	var map;
	$(document).ready(function(e) {
		map = L.map('map');
		L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a>',
            maxZoom: 18
         }).addTo(map);
		var latlngBounds = [];
		@foreach($locations as $loc)
			var latlng = new L.LatLng({{ $loc['lat'] }}, {{ $loc['lon'] }});
			var marker = L.marker([{{ $loc['lat'] }}, {{ $loc['lon'] }}]).bindPopup('{!! $loc['html'] !!}');
			markers['{{ $loc["vehicle_id"] }}'] = marker;
			markerGroup.addLayer(marker);
			latlngBounds.push(latlng);
		@endforeach
		map.addLayer(markerGroup);
		var bounds = new L.latLngBounds(latlngBounds);
		map.fitBounds(bounds, { padding: [50, 50] });
		autoRefresh = setInterval(function(){
			recriaPosicoes();
		}, 10000);	
		var toggle = L.easyButton({
			states: [{
				icon: 'fa-check-square-o',
				stateName: 'stop',
				onClick: function(control) {
					control.state('reload');
					autoRefresh = setInterval(function(){
						recriaPosicoes();
					}, 10000);	
				},
				title: 'Parar Atualização'
			},{
				stateName: 'reload',
				icon: 'fa-square-o',
				title: 'Atualizar em tempo real',
				onClick: function(control) {
					control.state('stop');
					clearInterval(autoRefresh);
				}
			}]
		});
		toggle.addTo(map);
		});

function recriaPosicoes(){
	var ids = [];
	for (i in markers){
		ids.push(i);
	}
	$.post(
		"/positions/updatePositions", 
		{
				"_token": _token,
				"ids" : JSON.stringify(ids),
		}, function(positions){
			var latlngBounds = [];
			for (m in positions){
				var position = positions[m];
				var id = position.vehicle_id;
				var marker = markers[id];
				var latlng = new L.LatLng(position.lat, position.lon);
				marker.setLatLng(latlng).bindPopup(position.html).update();
				latlngBounds.push(latlng);
			}
			var bounds = new L.latLngBounds(latlngBounds);
			map.fitBounds(bounds, { padding: [50, 50] });	
		}, 'json'
	);
}
</script>
@endsection
