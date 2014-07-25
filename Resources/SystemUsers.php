<?php

namespace ServerPilotAPI\Resources;

// load main Transport class for extending
require_once 'Resource.php';

// now use it
use ServerPilotAPI\Resources\Resource;

class SystemUsers extends Resource
{
    protected function request($object_id=null, $data=array()) {
    	$path = '/sysusers';
    	
    	if(!is_null($object_id)) {
	    	$path .= '/' . $object_id;
    	}
    
	    return parent::request($path, $data);
    }
    
	public function listAll() {
		$results = $this->request();
		
		return $results->data;
	}
}