<?php namespace Modules\Informations\Entities;
   
use Illuminate\Database\Eloquent\Model;

class Information extends Model {

		protected $table = 'informations';

    protected $fillable = ['transmission_reason', 'hodometer', 'power_supply', 'temperature', 
    	'panic', 'battery_charging', 'battery_failure', 'lifetime', 'gps_signal', 'gps_antenna_failure',
			'position_id'];
		public $timestamps = false;
		public $incrementing = false;

    public function Position()
    {
    	return $this->belongsTo('Modules\Positions\Entities\Position');
    } 

}