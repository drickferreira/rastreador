$(document).ready(function(){
	var refreshDiv = '<div id="refresh"><input type="checkbox" name="autorefresh" checked="true" onclick="setRefresh()" id="autorefresh">Atualizar Posições</div>';
	$("#gmap").append(refreshDiv);
	setRefresh();
})

function setRefresh(){
	if ($("#autorefresh").is(":checked")){
		autoRefresh = setInterval(function(){
			recriaPosicoes();
		}, 5000);	
	} else {
		clearInterval(autoRefresh);
	}
}

function recriaPosicoes(){
	var ids = [];
	for (i in maplace.markers){
		var content = maplace.markers[i].html;
		var id = $(content).find('#device').val();
		ids.push(id);
	}
     $.post(
        "/positions/updatePositions", 
        {
            "_token": _token,
            "ids" : JSON.stringify(ids),
        }, function(positions){
             for (m in positions){
             	var marker = maplace.markers[m];
             	var location = maplace.o.locations[m];
             	var position = positions[m];
             	location.lat = position.lat;
             	location.lon = position.lon;
             	location.html = position.html;
             	marker.setPosition(new google.maps.LatLng(position.lat, position.lon));
             }
        }, 'json'
    );
}