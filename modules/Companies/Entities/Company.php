<?php namespace Modules\Companies\Entities;
   
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes as SoftDeletes;
use OwenIt\Auditing\AuditingTrait;


class Company extends Model {
	
	use SoftDeletes, AuditingTrait;

	protected $table = 'companies';
	protected $fillable = ['name', 'cnpj', 'insc', 'phone1', 'phone2', 'email', 'address', 'number', 'comp', 'quarter', 'city', 'state',	'country', 'postalcode'];
	protected $dates = ['deleted_at'];
	
	public function Users()
	{
			return $this->hasMany('App\User');
	}

	public function Devices()
	{
			return $this->hasMany('Modules\Devices\Entities\Device');
	}

	public function Accounts()
	{
			return $this->hasMany('Modules\Accounts\Entities\Account');
	}
	
	public function Vehicles()
	{
			return $this->hasManyThrough('Modules\Vehicles\Entities\Vehicle', 'Modules\Accounts\Entities\Account');
	}


}
