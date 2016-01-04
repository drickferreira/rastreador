@extends('layouts.base')

@section('custom-css')
<link rel="stylesheet" href="http://openlayers.org/en/v3.12.1/css/ol.css" type="text/css">
@endsection

@section('main')
<div id="map" class="row" style="height: 450px;"></div>
{{--{!! $map['html'] !!}--}}
@endsection
@section('custom-js')
<script src="http://openlayers.org/en/v3.12.1/build/ol.js" type="text/javascript"></script>
<script type="text/javascript">
      var map = new ol.Map({
        target: 'map',
        layers: [
          new ol.layer.Tile({
            source: new ol.source.MapQuest({layer: 'osm'})
          })
        ],
        view: new ol.View({
          center: ol.proj.fromLonLat([-43.273876, -22.636289]),
          zoom: 4
        })
      });
</script>
{{--{!! $map['js'] !!}--}}
@endsection