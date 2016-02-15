<?php 

namespace Modules\Devices\Entities;
   
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes as SoftDeletes;

class Device extends Model {

	use SoftDeletes;

    protected $table = 'devices';

    protected $fillable = ['name', 'model', 'serial', 'company_id'];
		
		protected $appends = array('assignedvehicle');
		
    protected $dates = ['deleted_at'];

    public function Positions()
    {
    	return $this->hasMany('Modules\Positions\Entities\Position');
    }

    public function Vehicle()
    {
    	return $this->belongsToMany('Modules\Vehicles\Entities\Vehicle')->withPivot('install_date', 'remove_date', 'description');
    }

    public function Company()
    {
    	return $this->belongsTo('Modules\Companies\Entities\Company');
    }

		public function getAssignedvehicleAttribute($value)
    {
			$vehicle = $this->belongsToMany('Modules\Vehicles\Entities\Vehicle')->wherePivot('remove_date', null)->first();
			if ($vehicle){ 
	    	return $vehicle->plate;
			} else {
				return null;
			}
    }
		
		public function scopeHasvehicle($query, $value)
    {
			if ($value == 0){
        return $query;
			} elseif ($value == 1){
        return $query->whereHas('Vehicle', function ($q) use ($value) {
					$q->where('remove_date', null);
				});
			} elseif ($value == 2){
        return $query->whereHas('Vehicle', function ($q) use ($value) {
					$q->where('remove_date', null);
				}, '<');
			}
    }

}