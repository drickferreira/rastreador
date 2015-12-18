<?php

function getDropdown($list, $name, $selected = null, array $options = [])
{
	$res = trans("dropdown.$list");
	return Form::select($name, $res, $selected, $options);
}

function fieldValue($name, $key)
{	
	$res = trans("dropdown.$name");
	return isset($res[$key]) ? $res[$key] : 'Valor inválido!';
}
?>