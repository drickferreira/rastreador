<?php namespace Modules\CommandsResponse\Entities;
   
use Illuminate\Database\Eloquent\Model;

class CommandsResponse extends Model {

    protected $fillable = ['command_id', 'fragment_number', 'fragment_count', 'attempt', 'sts_id', 'desc', 'timestamp'];
		protected $table = 'commands_response';
		public $timestamps = false;

    public function Command()
    {
    	return $this->belongsTo('Modules\Commands\Entities\Command');
    }

}