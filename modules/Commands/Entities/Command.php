<?php namespace Modules\Commands\Entities;
   
use App\Database\Eloquent\Model;

class Command extends Model {
	
		protected $table = 'commands';
    protected $fillable = ['id_command', 'type', 'device_id'];
		protected $dates = ['created_at'];
		protected $appends = array('laststatus');

    public function Device()
    {
    	return $this->belongsTo('Modules\Devices\Entities\Device');
    } 
		
		public function Parameters()
		{
			return $this->hasMany('Modules\CommandParameters\Entities\CommandParameter');
		}
		
		public function getLaststatusAttribute()
		{
			$status = $this->Responses()->orderBy('timestamp','desc')->first()->sts_id;
			return fieldValue("commands_response_status", $status, "Sem Resposta");
		}

		public function Responses()
		{
			return $this->hasMany('Modules\CommandsResponse\Entities\CommandsResponse');
		}

}