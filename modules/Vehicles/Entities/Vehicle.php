<?php namespace Modules\Vehicles\Entities;
   
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model {

		use SoftDeletes;
		
    protected $table = 'vehicles';

    protected $fillable = ['plate', 'brand', 'model', 'year', 'color', 'active', 'account_id'];

		protected $appends = array('fullname', 'assigneddevice');

    protected $dates = ['deleted_at'];

    public function Device()
    {
    	return $this->belongsToMany('Modules\Devices\Entities\Device')->withPivot('install_date', 'remove_date', 'description');
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

		public function getAssigneddeviceAttribute($value)
    {
			$device = $this->belongsToMany('Modules\Devices\Entities\Device')->wherePivot('remove_date', null)->first();
			if ($device){ 
	    	return $device->serial;
			} else {
				return null;
			}
    }

		public function scopeHasdevice($query, $value)
    {
			if ($value == 0){
        return $query;
			} elseif ($value == 1){
        return $query->whereHas('Device', function ($q) use ($value) {
					$q->where('remove_date', null);
				});
			} elseif ($value == 2){
        return $query->whereHas('Device', function ($q) use ($value) {
					$q->where('remove_date', null);
				}, '<');
			}
    }



}