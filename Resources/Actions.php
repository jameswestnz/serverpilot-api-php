<?php

namespace ServerPilotAPI\Resources;

// load main Transport class for extending
require_once 'Resource.php';

// now use it
use ServerPilotAPI\Resources\Resource;

class Actions extends Resource
{
    protected function request($object_id=null, $data=array()) {
    	$path = '/actions';
    	
    	if(!is_null($object_id)) {
	    	$path .= '/' . $object_id;
    	}
    
	    return parent::request($path, $data);
    }
    
	public function getStatus($action_id) {
		$results = $this->request($action_id);
		
		return $results;
	}
}