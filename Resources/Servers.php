<?php

namespace ServerPilot\Resources;

// load main Transport class for extending
require_once 'Resource.php';

// now use it
use ServerPilot\Resources\Resource;

class Servers extends Resource
{
    protected function request($object_id=null, $data=array()) {
    	$path = '/servers';
    	
    	if(!is_null($object_id)) {
	    	$path .= '/' . $object_id;
    	}
    
	    return parent::request($path, $data);
    }
    
	public function listAll() {
		$results = $this->request();
		
		return $results;
	}
	
	public function read($server_id) {
		$results = $this->request($server_id);
		
		return $results;
	}
}