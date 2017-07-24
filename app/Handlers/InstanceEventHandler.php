<?php 
namespace App\Handlers;

use Webpatser\Uuid\Uuid;

class InstanceEventHandler {

	public function setUuid($instance)
	{
		if (isset($instance->incrementing) && $instance->incrementing == false)
			$instance->{$instance->getKeyName()} = Uuid::generate(4);

		return true;
	}
}