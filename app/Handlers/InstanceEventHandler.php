<?php 
namespace App\Handlers;

use Webpatser\Uuid\Uuid;

class InstanceEventHandler {

	public function setUuid($instance)
	{
		$instance->{$instance->getKeyName()} = Uuid::generate(4);

		return true;
	}
}