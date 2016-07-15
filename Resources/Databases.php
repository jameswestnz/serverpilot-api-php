<?php

namespace ServerPilot\Resources;

use \ServerPilot\Transports\Transport as Transport;

// load main Transport class for extending
require_once 'Resource.php';

// now use it
use ServerPilot\Resources\Resource;

class Databases extends Resource
{
	public $path = '/dbs';
    
	public function listAll($server_id=null, $app_id=null) {
		$results = $this->request();
		
		if(!is_null($server_id) || !is_null($app_id)) {
			foreach($results->data as $key => $result) {
				if((!is_null($server_id) && $result->serverid != $server_id) || (!is_null($app_id) && $result->appid != $app_id)) unset($results->data[$key]);
			}
		}
		
		return $results->data;
	}
	
	public function create($app_id, $name, $username, $password) {
		$name = strtolower(preg_replace("/[^A-Za-z0-9 ]/", '', $name));
		$username = strtolower(preg_replace("/[^A-Za-z0-9 ]/", '', $username));
	
		$data = array(
			'appid'		=>	$app_id,
			'name'		=>	$name,
			'user'		=>	array(
				'name' => $username,
				'password' => $password
			)
		);
	
		$results = $this->request(null, $data, Transport::SP_HTTP_METHOD_POST);
		
		return $results;
	}

    public function retrieve($databaseid) {
        $results = $this->request('/' . $databaseid);

        return $results;
    }

    public function delete($databaseid) {
        $results = $this->request('/' . $databaseid, null, Transport::SP_HTTP_METHOD_DELETE);

        return $results;
    }
}