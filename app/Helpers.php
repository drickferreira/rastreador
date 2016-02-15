<?php

function getDropdown($list, $name, $selected = null, array $options = [])
{
	$res = config("dropdown.$list");
	return Form::select($name, $res, $selected, $options);
}

function fieldValue($name, $key, $value = 'Valor inválido!')
{	
	$res = config("dropdown.$name");
	return isset($res[$key]) ? $res[$key] : $value;
}

function xmlGetVal($xml, $path, $type = 'str')
{
	$temp = $xml->xpath($path);
	if (is_array($temp)) $temp = current($temp);
	$func = $type.'val';
	return call_user_func($func, $temp);
}

function reverseGeocode($lat, $lon) {

	$api_key = 'AIzaSyCG32Qqxvj8OwbMWMcR2TDRsdeJ8ni0V1o';
	
	$url  = "https://maps.googleapis.com/maps/api/geocode/json?";
	$url .= "latlng=" . $lat .",". $lon;
	$url .= "&key=".$api_key;
	
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	$output = curl_exec($ch); 
	curl_close($ch);  
	
	$address = json_decode($output, true);	
	if ($address['status'] == 'OK') {
		return $address;
	} else {
		return false;
	}
}

?>