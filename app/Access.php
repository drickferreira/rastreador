<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    protected $table = 'access';
    protected $fillable = ['user_id', 'accessed_at', 'ip'];
		public $timestamps = false;

    public function User()
    {
    	return $this->belongsTo('App\User');
    }
		
}
