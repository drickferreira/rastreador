<?php namespace Modules\Companies\Entities;
   
use Illuminate\Database\Eloquent\Model;

class Company extends Model {

	protected $table = 'Companies';
    protected $fillable = ['name', 'cnpj', 'insc', 'phone1', 'phone2', 'primary_email' 
    	'primary_address_street', 'primary_address_number', 'primary_address_comp', 
    	'primary_address_quarter', 'primary_address_city', 'primary_address_state',
    	'primary_address_country', 'primary_address_postalcode', 'billing_email',
    	'billing_address_street', 'billing_address_number', 'billing_address_comp', 
    	'billing_address_quarter', 'billing_address_city', 'billing_address_state',
    	'billing_address_country', 'billing_address_postalcode'];
    protected $dates = ['deleted_at'];

}
