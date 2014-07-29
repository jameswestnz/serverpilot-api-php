<?php

namespace ServerPilotAPI\Resources;

class Resource
{
	/**  Location for overloaded data.  */
    protected $data = array();
    
    static $resources = array();
    
	public function __construct()
	{
		$args = func_get_args();
		
		$this->transport = end($args); reset($args);
	}
	
	/**  Local Setter  */
	public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }
    
    /**  Local Getter  */
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
    
    protected function request($path, $data=array()) {
    	$response = $this->transport->request($path, $data);
    
	    return $response;
    }
    
    public function loadResource($name, $arguments=array()) {
    	if(!isset(self::$resources[$name])) {
	    	$path = __DIR__ . '/' . $name . '.php';
	    
	    	if(!file_exists($path)) throw new \Exception('Resource not found.');
	    
	   		require_once $path;
	   		$class = "ServerPilotAPI\\Resources\\$name";
	   		
	   		$arguments[] = $this->transport;
	   		
	   		$reflector = new \ReflectionClass($class);
	   		self::$resources[$name] = $reflector->newInstanceArgs($arguments);
	   	}
	   	
	   	return self::$resources[$name];
    }
}