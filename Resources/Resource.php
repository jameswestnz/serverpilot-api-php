<?php

namespace ServerPilot\Resources;

use \ServerPilot\Transports\Transport as Transport;

class Resource
{
	public $path = '/';

	/**  Location for overloaded data.  */
    protected $data = array();
    
    static $resources = array();
    
	public function __construct()
	{
		$args = func_get_args();
		
		$this->transport = end($args); reset($args);
	}
	
	/** static getInstance */
    static function getInstance($name, $transport) {
    	if(!isset(self::$resources[$name])) {
	    	$path = __DIR__ . '/' . $name . '.php';
	    
	    	if(!file_exists($path)) throw new \Exception('Resource not found.');
	    
	   		require_once $path;
	   		$class = "ServerPilot\\Resources\\$name";
	   		
	   		$arguments = func_get_args();
	   		// don't need the name
	   		unset($arguments[0]);
	   		// transport needs to be the last
	   		unset($arguments[1]);
	   		
	   		$arguments[] = $transport;
	   		
	   		$reflector = new \ReflectionClass($class);
	   		self::$resources[$name] = $reflector->newInstanceArgs($arguments);
	   	}
	   	
	   	return self::$resources[$name];
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
    
    // method = Transport::SP_HTTP_METHOD_POST
    protected function request($relative_path='', $data=array(), $method=null) {
    	$path = $this->path . $relative_path;
    
    	$response = $this->transport->request($path, $data, $method);
    
	    return $response;
    }
    
    protected function loadResource($name, $arguments=array()) {
	   	$arguments = array_merge(array(
	   		$name,
	   		$this->transport
	   	), $arguments);
	   	
	   	return forward_static_call_array(array('ServerPilot\Resources\Resource', 'getInstance'), $arguments);
    }
}