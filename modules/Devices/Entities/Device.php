<?php 

namespace Modules\Devices\Entities;
   
use App\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes as SoftDeletes;
use OwenIt\Auditing\AuditingTrait;

class Device extends Model {

	
		use SoftDeletes, AuditingTrait;
		
		protected $auditableTypes = ['created', 'saved', 'deleted'];
    protected $table = 'devices';
    protected $fillable = ['name', 'model', 'serial', 'company_id', 'vehicle_id', 'install_date', 'description'];
		protected $appends = array('assignedvehicle');
    protected $dates = ['deleted_at'];

    public function Positions()
    {
    	return $this->hasMany('Modules\Positions\Entities\Position');
    }

    public function Vehicle()
    {
    	return $this->belongsTo('Modules\Vehicles\Entities\Vehicle');
    }

    public function Company()
    {
    	return $this->belongsTo('Modules\Companies\Entities\Company');
    }
		
		public function Commands()
		{
			return $this->hasMany('Modules\Commands\Entities\Command');
		}

		public function getAssignedvehicleAttribute($value)
    {
			return $this->Vehicle()->plate;
    }
		
		public function scopeHasvehicle($query, $value)
    {
			if ($value == 0){
        return $query;
			} elseif ($value == 1){
        return $query->has('Vehicle');
			} elseif ($value == 2){
        return $query->doesntHave('Vehicle');
			}
    }
}