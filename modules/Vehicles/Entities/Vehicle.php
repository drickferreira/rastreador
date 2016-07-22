<?php namespace Modules\Vehicles\Entities;
   
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\AuditingTrait;

class Vehicle extends Model {

		use SoftDeletes, AuditingTrait;
		
    protected $table = 'vehicles';

    protected $fillable = ['plate', 'brand', 'model', 'year', 'color', 'active', 'account_id'];

		protected $appends = array('fullname');

    protected $dates = ['deleted_at'];

    public function Device()
    {
    	return $this->hasOne('Modules\Devices\Entities\Device');
    }
		
		public function Account()
		{
			return $this->belongsTo('Modules\Accounts\Entities\Account');
		}
		
	  public function Company()
		{
			return $this->Account->Company();
		}
		
		public function Positions()
		{
			return $this->hasMany('Modules\Positions\Entities\Position');
		}

		public function getFullnameAttribute($value)
		{
			$keys = ['plate', 'brand', 'model', 'year', 'color'];
			$name = '';
			foreach ($keys as $key){
				if ($this->$key != ""){
					if ($name != "") $name .= " ";
					$name .= $this->$key; 
				}
			}
			return $name;
		}

/*		public function getAssigneddeviceAttribute($value)
    {
			$device = $this->Device;
			if ($device)
			return $device->serial;
			else
			return null;
    }
*/
		public function scopeHasdevice($query, $value)
    {
			if ($value == 0){
        return $query;
			} elseif ($value == 1){
        return $query->has('Device');
			} elseif ($value == 2){
        return $query->doesntHave('Device');
			}
    }

		public function Users()
    {
			return $this->belongsToMany('App\User');
    }
		
		public function lastPosition()
		{
			return $this->hasOne('Modules\Positions\Entities\Position')
						->getQuery()
						->orderBy('memory_index', 'desc');
		}

}