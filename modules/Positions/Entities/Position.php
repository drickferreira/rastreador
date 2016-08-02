<?php namespace Modules\Positions\Entities;
   
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model {

		protected $table = 'positions';
    protected $fillable = ['model', 'serial', 'memory_index', 'transmission_reason', 'date', 
    	'latitude', 'longitude', 'speed', 'hodometer', 'power_supply', 'temperature', 
    	'ignition', 'panic', 'battery_charging', 'battery_failure', 'device_id', 'vehicle_id',
			'lifetime', 'gps_signal', 'gps_antenna_failure'];

    protected $dates = ['date', 'created_at', 'deleted_at'];

    public function Vehicle()
    {
    	return $this->belongsTo('Modules\Vehicles\Entities\Vehicle');
    } 

    public function Device()
    {
    	return $this->belongsTo('Modules\Devices\Entities\Device');
    } 
		
}