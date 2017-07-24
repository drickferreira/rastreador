<?php namespace Modules\CommandParameters\Entities;
   
use App\Database\Eloquent\Model;

class CommandParameter extends Model {

    protected $fillable = ['parameter_id', 'value', 'command_id'];
		public $timestamps = false;
		public $incrementing = false;

    public function Command()
    {
    	return $this->belongsTo('Modules\Commands\Entities\Command');
    }
		

}