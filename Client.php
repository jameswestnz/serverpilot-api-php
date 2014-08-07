<?php

namespace ServerPilotAPI;

class Client
{
	/**  Location for overloaded data.  */
    private $data = array();
	
	public function __construct($client_id, $api_key, $transport = null)
	{
		if(is_null($transport)) {
			$transport = $this->loadTransport('Curl', $client_id, $api_key);
		}
		
		$this->transport = $transport;
	}
	
	public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }
    
    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }
	
	public function __call($name, $arguments)
    {
       	require_once __DIR__ . '/Resources/Resource.php';
       	$resource = new Resources\Resource($this->transport);
	   	return $resource->loadResource($name, $arguments); 
    }
    
    public function loadTransport($name, $client_id, $api_key) {
	    require_once __DIR__ . '/Transports/' . $name . '.php';
	   	$class = "ServerPilotAPI\\Transports\\$name";
		return new $class($client_id, $api_key);
    }
}