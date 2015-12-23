<?php namespace Modules\Positions\Entities;
   
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model {

	use SoftDeletes;

	protected $table = 'positions';
    protected $fillable = ['ip','memory_index', 'transmission_reason', 'date', 
    	'latitude', 'longitude', 'direction', 'speed', 'hodometer', 'power_supply', 'temperature', 
    	'ignition', 'panic', 'battery_charging', 'battery_failure', 'device_id'];

    protected $dates = ['date', 'created_at', 'updated_at', 'deleted_at'];

    public $timestamps = false;

    public function Device()
    {
    	return $this->belongsTo('Modules\Devices\Entities\Device');
    } 

}