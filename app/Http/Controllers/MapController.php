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

    public function index()
    {
        $devices = Device::all();
        return view('maps.index', ['devices' => $devices]);
    }

    public function create()
    {
        $ftp = new Ftp;
        $ftp->chdir('data');
        $list = $ftp->dir();
        $count = 0;
        $filecount = 0;

        // $file = $list[0];
        // echo $file ."<br>";

        foreach ($list as $file) 
        {

            $xml = $ftp->read($file);
            $ftp->delete($file);
            $filecount++;
            //if ($filecount > 4) break;
            // dd($xml);

            foreach($xml->xpath('POSITION') as $pos){
                //dd($pos);
                $device = Device::where('serial', xmlGetVal($pos,'FIRMWARE/SERIAL'))
                                  ->where('model', xmlGetVal($pos,'FIRMWARE/PROTOCOL'))
                                  ->first();
                if ($device) {

                    $ip = strval($pos['ipv4']);
                    if($ip === '')
                    {
                        $ip = xmlGetVal($xml,'//MXT1XX_IP_DATA/IP');
                    }  
                    $position = array(
                        'ip' => $ip, 
                        'memory_index' => xmlGetVal($pos,'FIRMWARE/MEMORY_INDEX', 'int'),
                        'transmission_reason' => xmlGetVal($pos,'FIRMWARE/TRANSMISSION_REASON','int'), 
                        'date' => xmlGetVal($pos,'GPS/DATE','str'), 
                        'power_supply' => xmlGetVal($pos,'HARDWARE_MONITOR/POWER_SUPPLY','float'), 
                        'temperature' => xmlGetVal($pos,'HARDWARE_MONITOR/TEMPERATURE','int'), 
                        'ignition' => xmlGetVal($pos,'HARDWARE_MONITOR/INPUTS/IGNITION','int'), 
                        'panic' => xmlGetVal($pos,'HARDWARE_MONITOR/INPUTS/PANIC','int'), 
                        'battery_charging' => xmlGetVal($pos,'HARDWARE_MONITOR/FLAG_STATE/BATTERY_CHARGING','int'),
                        'battery_failure' => xmlGetVal($pos,'HARDWARE_MONITOR/FLAG_STATE/BATTERY_FAILURE','int'),
                        'latitude' => xmlGetVal($pos,'GPS/LATITUDE','float'),
                        'longitude' => xmlGetVal($pos,'GPS/LONGITUDE','float'),
                        'speed' => xmlGetVal($pos,'GPS/SPEED','float'),
                        'hodometer' => xmlGetVal($pos,'GPS/HODOMETER','int'),
                    );
                    // dd($position); 
                    $new = new Position($position);
                    $device->Positions()->save($new);
                    $count++;
                }
            }
        }
        return $count;
    }

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
