<?php namespace Modules\Vehicles\Entities;
   
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model {

		use SoftDeletes;
		
    protected $table = 'vehicles';

    protected $fillable = ['plate', 'brand', 'model', 'year', 'color', 'active', 'account_id'];

		protected $appends = array('fullname');

    protected $dates = ['deleted_at'];

    public function Device()
    {
    	return $this->belongsToMany('Modules\Devices\Entities\Device');
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

}