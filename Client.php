<?php

namespace ServerPilotAPI;

class Client
{
	/**  Location for overloaded data.  */
    private $data = array();
	
	public function __construct($client_id, $api_key, $transport = null)
	{
		if(is_null($transport)) {
			require_once __DIR__ . '/Transports/Curl.php';
			$transport = new \ServerPilotAPI\Transports\Curl($client_id, $api_key);
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
        if(!isset($this->$name)) {
       		require_once __DIR__ . '/Resources/' . $name . '.php';
       		$class = "ServerPilotAPI\\Resources\\$name";
       		$this->$name = new $class($arguments);
       		$this->$name->transport = $this->transport;
        }
        
        return $this->$name;    
    }
	
}