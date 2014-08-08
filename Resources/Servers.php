<?php

namespace ServerPilot\Resources;

use \ServerPilot\Transports\Transport as Transport;

// load main Transport class for extending
require_once 'Resource.php';

// now use it
use ServerPilot\Resources\Resource;

class Servers extends Resource
{
	public $path = '/servers';
    
	public function listAll() {
		$results = $this->request();
		
		return $results;
	}
	
	public function read($server_id) {
		$results = $this->request('/' . $server_id);
		
		return $results;
	}
}