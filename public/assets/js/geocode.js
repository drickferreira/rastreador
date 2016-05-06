var nextAddress = 0;
var delay = 100;

$(document).ready(function(e) {
//	theNext();
});

function theNext() {
	var index = nextAddress;
	if (nextAddress < $(".address").length) {
		setTimeout( function(){
			getAddress(index,theNext);
		}, delay);
		nextAddress++;
	}
}

function searchAddr(id){
	var lat = $("#" + id).attr("geo-lat"),
			lng = $("#" + id).attr("geo-lng");
			
	$.getJSON("http://nominatim.openstreetmap.org/reverse",
	{
		format: 'json',
		lat: lat,
		lon: lng,
		addressdetails: 1,
	}, function(data){		
		$("#" + id).text(decodeAddress(data.address));
	});
}

function getAddress(index, next) {
	var lat = $(".address").eq(index).attr("geo-lat"),
			lng = $(".address").eq(index).attr("geo-lng");
			
	$.getJSON("http://nominatim.openstreetmap.org/reverse",
	{
		format: 'json',
		lat: lat,
		lon: lng,
		addressdetails: 1,
	}, function(data){		
		$(".address").eq(index).text(decodeAddress(data.address));
	});
	next();
}

function decodeAddress(add){
	var address = '';
	var keys = ['park', 'road', 'suburb', 'city', 'state', 'country_code'];
	for (k in keys){
		var key = keys[k];
		if (key in add){
			if (key == 'country_code'){
				if (address != '') address = address + ' - ';
				address = address + add[key].toUpperCase();
			} else {
				if (address != '') address = address + ', ';
				address = address + add[key];
			}
		}
	}
	return address;
}

function validaChecked(){
	var selected = $("input[name='ids[]']:checked").length;
	if (selected>0){
		$("#idLista").submit();
	} else {
		swal("Erro!", "É necessário selecionar pelo menos uma posição", "error"); 
		return false;
	}
}

function selectTodos(){
	if ($("input#todos").is(':checked')){
		$("input[name='ids[]']").prop('checked',true);
	} else {
		$("input[name='ids[]']").prop('checked',false);
	}
}

function checkSelected(){
	var count = $("input[name='ids[]']").length;
	var selected = $("input[name='ids[]']:checked").length;
	if (selected > 0){
		if (selected == count){
			$("input#todos").prop('indeterminate', false)
							.prop('checked', true);
		} else {
			$("input#todos").prop('checked', false)
							.prop('indeterminate', true);
		}
	} else {
		$("input#todos").prop('checked', false)
						.prop('indeterminate', false);
	}
}

