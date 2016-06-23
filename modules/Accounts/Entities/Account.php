<?php namespace Modules\Accounts\Entities;
   
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\AuditingTrait;

class Account extends Model {
	
	use AuditingTrait;

	protected $table = 'accounts';
  protected $fillable = ['name', 'cpf_cnpj', 'phone1', 'phone2', 'description'];
	protected $dates = ['deleted_at'];

	public function Vehicles()
	{
			return $this->hasMany('Modules\Vehicles\Entities\Vehicle');
	}

  public function Company()
	{
		return $this->belongsTo('Modules\Companies\Entities\Company');
	}
	
	public function scopeFreesearch($query, $value)
	{
		return $query->where('name','ilike','%'.$value.'%')
			->orWhere('cpf_cnpj','like','%'.$value.'%');
	}

	public function scopeHasvehicle($query, $value)
	{
		if ($value == 0){
			return $query;
		} elseif ($value == 1){
			return $query->has('Vehicles');
		} elseif ($value == 2){
			return $query->doesntHave('Vehicles');
		}
	}

}