<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Modules\Devices\Entities\Device;
use Modules\Positions\Entities\Position;
use App\Ftp;
use App\Helpers;
use Mail;
use Config;

class MapController extends Controller
{
    public function sendmail(){
			
        $result = Mail::send('emails.message', [], function($message){
            $message->from('drickferreira@afinet.com.br', 'Teste do Laravel');
            $message->to('drickferreira@afinet.com.br')->subject('Teste de Mensagem do Laravel');
        });

        // Laravel tells us exactly what email addresses failed, let's send back the first
        $fail = Mail::failures();
        if(!empty($fail)) throw new \Exception('Could not send message to '.$fail[0]);

        if(empty($result)) throw new \Exception('Email could not be sent.');

        return "Email enviado!";

    }

}
