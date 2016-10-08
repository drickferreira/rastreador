<?php
return array (

	'GET_DEVICE_INFORMATION' => array( 
		'TYPE' => 80,
		'PARAMETERS' => array (
		),
	),
	'SET_REQUEST_SETUP' => array( 
		'TYPE' => 50,
		'PARAMETERS' => array (
			0 => array(
				'ID' => 'CONFIGURATION',
				'LABEL' => 'Requisitar Setup',
				'VALUE' => 1,
			),
		),
	),
	'SET_TIME_ZONE' => array( 
		'TYPE' => 51,
		'PARAMETERS' => array (
			0 => array(
				'ID' => 'SET_TIME_ZONE',
				'LABEL' => 'Alterar Fuso Horário',
				'VALUE' => 1,
			),
			1 => array(
				'ID' => 'TIME ZONE',
				'LABEL' => 'Fuso horário',
				'VALUE' => array(
					'0' => '-12',
					'1' => '-11',
					'2' => '-10',
					'3' => '-9',
					'4' => '-8',
					'5' => '-7',
					'6' => '-6',
					'7' => '-5',
					'8' => '-4',
					'9' => '-3',
					'10' => '-2',
					'11' => '-1',
					'12' => '+1',
					'13' => '+2',
					'14' => '+3',
					'15' => '+4',
					'16' => '+5',
					'17' => '+6',
					'18' => '+7',
					'19' => '+8',
					'20' => '+9',
					'21' => '+10',
					'22' => '+11',
					'23' => '+12',
					'24' => '+13',
					'25' => '+14'
				)
			),
		),
	),
	'SET_REPORT_TIME_MOVING' => array( 
		'TYPE' => 51,
		'PARAMETERS' => array (
			0 => array(
				'ID' => 'SET_REPORT_TIME_MOVING',
				'LABEL' => 'Ajustar Tempo de Transmissão em Movimento',
				'VALUE' => 1,
			),
			1 => array(
				'ID' => 'REPORT TIME MOVING',
				'LABEL' => 'Tempo de Transmissão em Movimento',
				'VALUE' => '',
			),
		),
	),
	'SET_REPORT_TIME_IGNITION_OFF' => array( 
		'TYPE' => 51,
		'PARAMETERS' => array (
			0 => array(
				'ID' => 'SET_REPORT_TIME_IGNITION_OFF',
				'LABEL' => 'Ajustar Tempo de Transmissão Parado',
				'VALUE' => 1,
			),
			1 => array(
				'ID' => 'REPORT TIME IGNITION OFF',
				'LABEL' => 'Tempo de Transmissão Parado',
				'VALUE' => '',
			),
		),
	),
	'SET_REPORT_TIME_PANIC' => array( 
		'TYPE' => 51,
		'PARAMETERS' => array (
			0 => array(
				'ID' => 'SET_REPORT_TIME_PANIC',
				'LABEL' => 'Ajustar Tempo de Transmissão em Pânico',
				'VALUE' => 1,
			),
			1 => array(
				'ID' => 'REPORT TIME PANIC',
				'LABEL' => 'Tempo de Transmissão em Pânico',
				'VALUE' => '',
			),
		),
	),
	'SET_PANIC_MODE' => array( 
		'TYPE' => 51,
		'PARAMETERS' => array (
			0 => array(
				'ID' => 'SET_PANIC_MODE',
				'LABEL' => 'Ativar Modo Pânico',
				'VALUE' => 1,
			),
			1 => array(
				'ID' => 'PANIC MODE',
				'LABEL' => 'Modo Pânico',
				'VALUE' => '',
			),
		),
	),
);

?>