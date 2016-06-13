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

?>