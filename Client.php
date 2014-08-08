<?php

namespace ServerPilot;

class Client
{
	/**  Location for overloaded data.  */
    private $data = array();
	
	public function __construct($client_id, $api_key, $transport = null)
	{
		if(is_null($transport)) {
			$transport = self::getTransport('Curl', $client_id, $api_key);
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
	   	
	   	$arguments = array_merge(array(
	   		$name,
	   		$this->transport
	   	), $arguments);
	   	
	   	return forward_static_call_array(array('ServerPilot\Client', 'getResource'), $arguments);
    }
    
    static function getTransport($name, $client_id, $api_key) {
       	require_once __DIR__ . '/Transports/Transport.php';
	   	
	   	$arguments = func_get_args();
	   	
	   	return forward_static_call_array(array('ServerPilot\Transports\Transport', 'getInstance'), $arguments);
    }
    
    static function getResource($name, $transport) {
       	require_once __DIR__ . '/Resources/Resource.php';
	   	
	   	$arguments = func_get_args();
	   	
	   	return forward_static_call_array(array('ServerPilot\Resources\Resource', 'getInstance'), $arguments);
    }
}