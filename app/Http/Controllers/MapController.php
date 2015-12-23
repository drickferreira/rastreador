<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Modules\Devices\Entities\Device;
use Modules\Positions\Entities\Position;
use App\FTP;
use App\Helpers;

class MapController extends Controller
{

    public function index()
    {
        $devices = Device::all();
        return view('maps.index', ['devices' => $devices]);
    }

    public function create()
    {
        $ftp = new FTP;
        $ftp->chdir('data');
        $list = $ftp->dir();
        $count = 0;

        foreach ($list as $file) {

            $xml = $ftp->read($file);
            //dd($xml);

            foreach($xml->xpath('POSITION') as $pos){
                //dd($pos);
                $device = Device::where('serial', xmlGetVal($pos,'FIRMWARE/SERIAL'))
                                  ->where('model', xmlGetVal($pos,'FIRMWARE/PROTOCOL'))
                                  ->first();
                //dd($device);
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
                    'ignition' => xmlGetVal($pos,'HARDWARE_MONITOR/INPUTS/IGNITION','bool'), 
                    'panic' => xmlGetVal($pos,'HARDWARE_MONITOR/INPUTS/PANIC','bool'), 
                    'battery_charging' => xmlGetVal($pos,'HARDWARE_MONITOR/FLAG_STATE/BATTERY_CHARGING','bool'),
                    'battery_failure' => xmlGetVal($pos,'HARDWARE_MONITOR/FLAG_STATE/BATTERY_FAILURE','bool'),
                    'latitude' => xmlGetVal($pos,'GPS/LATITUDE','float'),
                    'longitude' => xmlGetVal($pos,'GPS/LONGITUDE','float'),
                    'direction' => xmlGetVal($pos,'GPS/COURSE','int'),
                    'speed' => xmlGetVal($pos,'GPS/SPEED','float'),
                    'hodometer' => xmlGetVal($pos,'GPS/HODOMETER','int'),
                );
                //dd($position); 
                $new = new Position($position);
                $device->Positions()->save($new);
                $count++;
            }
        }
        return $count;
    }

}
