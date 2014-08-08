<?php

namespace ServerPilot\Resources;

// load main Transport class for extending
require_once 'Resource.php';

// now use it
use ServerPilot\Resources\Resource;

class Apps extends Resource
{
    protected function request($object_id=null, $data=array()) {
    	$path = '/apps';
    	
    	if(!is_null($object_id)) {
	    	$path .= '/' . $object_id;
    	}
    
	    return parent::request($path, $data);
    }
    
	public function listAll($server_id=null) {
		$results = $this->request();
		
		if(!is_null($server_id)) {
			foreach($results->data as $key => $result) {
				if($result->serverid != $server_id) unset($results->data[$key]);
			}
		}
		
		return $results->data;
	}
	
	public function create($name, $sysuser_id, $runtime='php5.4', $domains=array()) {
		$name = strtolower(preg_replace("/[^A-Za-z0-9 ]/", '', $name));
	
		$data = array(
			'name'		=>	$name,
			'sysuserid'	=>	$sysuser_id,
			'runtime'	=>	$runtime,
			'domains'	=>	$domains
		);
	
		$results = $this->request(null, $data);
		
		return $results;
	}
}