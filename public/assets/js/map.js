$(document).ready(function(){
	var btn = '<button id="fullscreen" data-toggle="collapse" data-target=".sidebar" class="btn btn-default" onclick="fullscreen()"><i class="glyphicon glyphicon-fullscreen"></i></button>';
	$(".main").append(btn);
	$("#map_canvas").css("height", "100%");
})

function fullscreen(){
	var center = map.getCenter();
	$(".main").toggleClass('col-sm-9 col-sm-12');
    google.maps.event.trigger(map, "resize");
    map.setCenter(center);
}
	
