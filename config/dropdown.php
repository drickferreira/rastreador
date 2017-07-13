<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Dropdown 
    |--------------------------------------------------------------------------
    |
    | Arquivo com dropdowns em forma de array
    | 
    | 
    |
    */

    'devices' => array
    (
        166   =>  'MXT140',
        163   =>  'MXT151 / MXT151+',
        162   =>  'MXT150 / MXT150+',
				200		=>	'E3',
				300		=>	'CRX1',
    ),

    'directions-class' => array
    (
        0 => 'up',
        1 => 'up rotate',
        2 => 'right',
        3 => 'right rotate',
        4 => 'down',
        5 => 'down rotate',
        6 => 'left',
        7 => 'left rotate',
    ),

    'directions' => array
    (
        0 => 'Norte',
        1 => 'Nordeste',
        2 => 'Leste',
        3 => 'Sudeste',
        4 => 'Sul',
        5 => 'Sudoeste',
        6 => 'Oeste',
        7 => 'Noroeste',
    ),

    'transmission_reason' => array
    (
        1 => 'Dispositivo Ligado',
        2 => 'Primeira conexão GPRS ou reconexão',
        3 => 'Intervalo de Transmissão parado',
        4 => 'Intervalo de Transmissão em movimento',
        5 => 'Intervalo de Transmissão em pânico',
        6 => 'Configurações modificadas',
        7 => 'Solicitação do Servidor',
        8 => 'Soliticação de GPS válido após intervalo de transmissão',
        9 => 'Ignição ligada',
        10 => 'Ignição desligada',
        11 => 'Pânico ativado',
        12 => 'Panico desativado',
        13 => 'Entrada 1 ativada',
        14 => 'Entrada 1 aberta',
        15 => 'Entrada 2 ativada',
        16 => 'Entrada 2 aberta',
        17 => 'Entrada 3 ativada',
        18 => 'Entrada 3 aberta',
        19 => 'Entrada 4 ativada',
        20 => 'Entrada 4 aberta',
        21 => 'Detecção de Movimento',
        22 => 'Parou detecção de Movimento',
        23 => 'Anti=>roubo alarmado',
        24 => 'Situação Crítica de um dos Acessórios',
        25 => 'Alimentação externa falhou',
        26 => 'Alimentação externa OK (após falha)',
        27 => 'Antena GPS falhou',
        28 => 'Antena GPS OK',
        29 => 'Pacote recebido 2.4Ghz',
        30 => 'Entrando em modo Sleep',
        31 => 'Saída 1 ativada',
        32 => 'Saída 1 desativada',
        33 => 'Saída 2 ativada',
        34 => 'Saída 2 desativada',
        35 => 'Saída 3 ativado',
        36 => 'Saída 3 desativada',
        37 => 'Velocidade máxima excedida',
        38 => 'Velocidade máxima OK (após excesso)',
        39 => 'Entrando waypoint',
        40 => 'Deixando waypoint',
        41 => 'Bateria de Backup falhou',
        42 => 'Bateria de Backup OK (após falha)',
        43 => 'Falha na entrega',
        44 => 'Requisição por SMS',
        45 => 'Sensor de Adulteração ligado',
        46 => 'Limite do G=>sensor em movimento alcançado',
        47 => 'Limite do G=>sensor lateral alcançado',
        48 => 'Limite de choque do G=>sensor alcançado',
        49 => 'Direção GPS alterada',
        50 => 'Intervalo de SMS (sem conexão GPRS)',
        51 => 'Desligado',
        52 => 'Anti=>roubo entrar normal após alarme',
        53 => 'GSM Jamming muda de NÃO para SIM',
        54 => 'GSM Jamming muda de SIM para NÃO',
        55 => 'RPM excessiva',
        56 => 'RPM excessiva no Neutro',
        57 => 'Excesso de velocidade em ponto morto',
        58 => 'Falha GPS',
        59 => 'Distância anexado',
        60 => 'Falha de Energia e de GPS',
        61 => 'AGPS requer, neste caso, de 8 ~ 16 bits para indicar a quantos minutos não recebe dados do GPS.',
        62 => 'Status de Acessórios TAG mudou de 1 para 0',
        63 => 'Status de Bateria de Acessórios TAG mudou',
        64 => 'Link Quebrado',
        65 => 'Entrada Expandida mudou',
        66 => 'Status de Acessórios TAG mudou de 0 para 1',
        67 => 'Resta 30% de carga na bateria',
        68 => 'Resta 20% de carga na bateria',
        69 => 'Status - Mantenha parado com a ignição ligada',
        70 => 'Movimentação incorreta',
        71 => 'Câmera cega',
        72 => 'Câmera cega recuperar',
        73 => 'Câmera de vídeo perdida',
        74 => 'Câmera de vídeo OK',
        75 => 'Entrada de dados RS232',
        76 => 'Calibragem de tensão de ignição terminou',
        77 => 'Antes de entrar em Sleep profundo',
        78 => 'Excesso de velocidade com chuva',
        79 => 'Velocidade normalizada depois de excesso com chuva',
        80 => 'Aceleração excedida',
        81 => 'Aceleração normalizada depois de excesso',
        82 => 'Desaceleração excedida',
        83 => 'Desaceleração normalizada depois de excesso',
        84 => 'Login de Motorista RFID,',
        85 => 'Logout de Motorista RFID',
        86 => 'Login de Passageiro RFID,',
        87 => 'Excesso de velocidade máxima Genérico',
        88 => 'Velocidade Genérica normalizada após excesso',
        89 => 'Falhou ao tentar senha do dispositivo mais de 3 vezes',
        90 => 'Recebido comando de Ativar Bloqueio de Motor',
        91 => 'Bloqueio do motor ativado',
        92 => 'Bloqueio do motor desativado',
        93 => 'Bloqueio do motor ativado por relé',
        94 => 'Bloqueio do motor desativado relé',
        95 => 'Bloqueio do motor ativado por input1',
        96 => 'Bloqueio do motor desativado input1',
        97 => 'Resposta de sondagem de rede',
        98 => 'Histograma da velocidade',
        99 => 'Delta de Journey',
        100 => 'Eventos de Telemetria',
        101 => 'Reconstrução do Evento (reservado)',
        102 => 'Reconstrução da Rota'
    ),
		
		'all_roles' => array 
		(
			10 => 'Super Admin',
			20 => 'Admin - Empresa',
			30 => 'Usuário Comum',
			40 => 'Cliente',
		),

		'roles' => array 
		(
			10 => 'Super Admin',
			20 => 'Admin - Empresa',
			30 => 'Usuário Comum',
		),

		'company_roles' => array 
		(
			20 => 'Admin - Empresa',
			30 => 'Usuário Comum',
			40 => 'Cliente',
		),
		
		'commands_response_status' => array
		(
			'0' => '',
			'1' => 'Comando Enviado',
			'2' => 'Número máximo de tentativas excedido',
			'3' => 'Comando expirou por tempo',
			'4' => 'Comando Cancelado',
			'5' => 'Comando Confirmado',
			'6' => 'Arquivo XML inválido',
		),
		
		'devices_status' => array
		(
			'0' => 'Ativo',
			'1' => 'Indisponível',
			'2' => 'Disponível',
			'3' => 'Em Manutenção',
			'4' => 'Em RMA',
			'5' => 'Inativo',
		)

];