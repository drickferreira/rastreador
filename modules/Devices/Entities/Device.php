<?php 

namespace Modules\Devices\Entities;
   
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes as SoftDeletes;

class Device extends Model {

	use SoftDeletes;

    protected $table = 'devices';

    protected $fillable = ['name', 'model', 'serial'];

    protected $dates = ['deleted_at'];

    public function Positions()
    {
    	return $this->hasMany('Modules\Positions\Entities\Position');
    }

}