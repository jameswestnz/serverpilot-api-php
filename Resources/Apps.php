<?php

namespace ServerPilot\Resources;

use \ServerPilot\Transports\Transport as Transport;

// load main Transport class for extending
require_once 'Resource.php';

// now use it
use ServerPilot\Resources\Resource;

class Apps extends Resource
{
	public $path = '/apps';
    
	public function listAll($server_id=null) {
		$results = $this->request();
		
		if(!is_null($server_id)) {
			foreach($results->data as $key => $result) {
				if($result->serverid != $server_id) unset($results->data[$key]);
			}
		}
		
		return $results->data;
	}
	
	public function create($name, $sysuser_id, $runtime='php5.6', $domains=array(), $wordpress=null) {
		$name = strtolower(preg_replace("/[^A-Za-z0-9 ]/", '', $name));
	
		$data = array(
			'name'		=>	$name,
			'sysuserid'	=>	$sysuser_id,
			'runtime'	=>	$runtime,
            'domains'	=>	$domains,
            'wordpress'	=>	$wordpress
		);
	
		$results = $this->request(null, $data, Transport::SP_HTTP_METHOD_POST);
		
		return $results;
	}
	
	public function delete($id) {
		$results = $this->request('/' . $id, null, Transport::SP_HTTP_METHOD_DELETE);
		
		return $results;
	}
	
	public function addSSL($app_id, $key, $cert, $cacerts=null) {
		$data = array(
			'key'		=>	$key,
			'cert'		=>	$cert,
			'cacerts'	=>	$cacerts
		);
	
		$results = $this->request('/' . $app_id . '/ssl', $data, Transport::SP_HTTP_METHOD_POST);
		
		return $results;
	}
	
	public function deleteSSL($app_id) {
		$results = $this->request('/' . $app_id . '/ssl', null, Transport::SP_HTTP_METHOD_DELETE);
		
		return $results;
	}
}