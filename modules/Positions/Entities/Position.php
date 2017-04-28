<?php namespace Modules\Positions\Entities;
   
use Illuminate\Database\Eloquent\Model;

class Position extends Model {

		protected $table = 'positions';
    protected $fillable = ['model', 'serial', 'date', 'latitude', 'longitude', 'speed', 
    	'ignition', 'device_id', 'vehicle_id'];

    protected $dates = ['date'];
		public $timestamps = false;

    public function Vehicle()
    {
    	return $this->belongsTo('Modules\Vehicles\Entities\Vehicle');
    } 

    public function Device()
    {
    	return $this->belongsTo('Modules\Devices\Entities\Device');
    }
		 
    public function Info()
    {
    	return $this->hasOne('Modules\Informations\Entities\Information');
    }
		
}