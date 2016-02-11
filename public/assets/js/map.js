function autoCenter(){
	$("#map_canvas").css("height", "90%");
	var latlngbounds = new google.maps.LatLngBounds();
	$.each(lat_longs_map, function(i, m){
	   latlngbounds.extend(m);
	});
	map.setCenter(latlngbounds.getCenter());
	map.fitBounds(latlngbounds); 
}
	
