<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\FTP;
use Gmaps;
use Carbon\Carbon;

class MapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ftp = new FTP;
        $ftp->chdir('data');
        $list = $ftp->dir();
        return view('maps.index', ['list' => $list]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $ftp = new FTP;
        $ftp->chdir('data');
        $xml = $ftp->read($id);
        $dt = new Carbon((string) $xml->POSITION->GPS->DATE);
        $data = array(
            'ip' => (string) $xml->POSITION->attributes()->ipv4,
            'serial' => (string) $xml->POSITION->SERIAL,
            'data' => $dt->format('d/m/Y H:i:s'),
            'lat' => (string) $xml->POSITION->GPS->LATITUDE,
            'long' => (string) $xml->POSITION->GPS->LONGITUDE,
            'type' => (string) $xml->POSITION->FIRMWARE->PROTOCOL,
        );
        $config = array();
        $config['center'] = $data['lat'].','.$data['long'];
        $config['zoom'] = '16';
//        $config['map_height'] = '500px';
        Gmaps::initialize($config);

        $marker = array();
        $marker['position'] = $data['lat'].','.$data['long'];
        $marker['infowindow_content'] = 'Saporra Funciona!';
        Gmaps::add_marker($marker);

        $map = Gmaps::create_map();

        return view('maps.show', ['map' => $map, 'data' => $data]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
