<div class="dashboard">
<div class="row">
@foreach($itens as $item)
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="panel {{$item['class']}}">
            <div class="panel-heading">
            <a href="{{$item['link']}}">
              <i class="fa fa-{{$item['icon']}} fa-2x"></i>
              <div class="huge">{{$item['text']}}</div>
            </a>
            </div>
            <div class="panel-footer">
  	          @foreach($item['lines'] as $line)
		            <a href="{!! $line['link'] !!}" class="bottom-space btn btn-{{$line['color']}}">
                  <span class="pull-left">{!! $line['title'] !!}</span>
                  <span class="pull-right badge">{!! $line['count'] !!}</span>
	              </a>
	            @endforeach
            </div>
        </div>
    </div>
@endforeach
</div>
{!! Html::style('assets/leaflet/leaflet.css') !!}
{!! Html::style('assets/css/easy-button.css') !!}
@if(isset($locations))
<div id="map" class=".hidden-xs"></div>
{!! Html::script('assets/leaflet/leaflet.js') !!}
{!! Html::script('assets/js/easy-button.js') !!}
<script type="text/javascript">
	var _token = '{{ csrf_token() }}';
	var markers = [];
	var markerGroup = L.layerGroup();	
	var map;
	var h = window.innerHeight - 250;
	$(document).ready(function(e) {
		$("#map").css('height', h);
		map = L.map('map');
		L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a>',
            maxZoom: 18
         }).addTo(map);
		var latlngBounds = [];
		@foreach($locations as $loc)
			var latlng = new L.LatLng({{ $loc['lat'] }}, {{ $loc['lon'] }});
			var marker = L.marker([{{ $loc['lat'] }}, {{ $loc['lon'] }}]);
			marker.bindPopup('{!! $loc['html'] !!}', {maxWidth : 500}).openPopup();
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
			states: [
			{
				stateName: 'stop',
				icon: 'fa-check-square-o',
				title: 'Parar Atualização',
				onClick: function(control) {
					control.state('reload');
					clearInterval(autoRefresh);
				}
			},
			{
				stateName: 'reload',
				icon: 'fa-square-o',
				title: 'Atualizar em tempo real',
				onClick: function(control) {
					control.state('stop');
					autoRefresh = setInterval(function(){
						recriaPosicoes();
					}, 10000);	
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
@endif
</div>