<?php

namespace ServerPilot\Resources;

use \ServerPilot\Transports\Transport as Transport;

// load main Transport class for extending
require_once 'Resource.php';

// now use it
use ServerPilot\Resources\Resource;

class Actions extends Resource
{
	public $path = '/actions';
    
	public function getStatus($action_id) {
		$results = $this->request('/' . $action_id);
		
		return $results;
	}
}