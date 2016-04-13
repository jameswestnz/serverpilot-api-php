<?php

namespace ServerPilot\Transports;

class Transport
{
	// variables
	public $cacheTime = 3600;
	public $requestTimeout = 30;
	
	static $transports = array();
	
	// constants
	const SP_API_ENDPOINT		= 'https://api.serverpilot.io/v1';
	const SP_USERAGENT			= 'serverpilot-api-php';
	const SP_HTTP_METHOD_POST	= 'POST';
	const SP_HTTP_METHOD_GET	= 'GET';
	const SP_HTTP_METHOD_DELETE	= 'DELETE';
	
	/**  Location for overloaded data.  */
    protected $data = array();
    
	public function __construct($client_id, $api_key)
	{
		$this->setCredentials($client_id, $api_key);
	}
	
	/** static getInstance */
    static function getInstance($name, $client_id, $api_key) {
    	if(!isset(self::$transports[$name])) {
	    	$path = __DIR__ . '/' . $name . '.php';
	    
	    	if(!file_exists($path)) throw new \Exception('Transport not found.');
	    
	   		require_once $path;
	   		$class = "ServerPilot\\Transports\\$name";
	   		
	   		$arguments = func_get_args();
	   		// don't need the name
	   		unset($arguments[0]);
	   		
	   		$reflector = new \ReflectionClass($class);
	   		self::$transports[$name] = $reflector->newInstanceArgs($arguments);
	   	}
	   	
	   	return self::$transports[$name];
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
    
    public function setCredentials($client_id, $api_key) {
		$this->client_id = $client_id;
		$this->api_key = $api_key;
    }
	
	public function getRemoteURL() {
		return $this->apiURL;
	}
	
	public function setCacheTime($time) {
		$this->cacheTime = $time;
	}
	
	public function getCacheTime() {
		return $this->cacheTime;
	}
	
	public function setRequestTimeout($seconds) {
		$this->requestTimeout = $seconds;
	}
	
	public function getRequestTimeout() {
		return $this->requestTimeout;
	}
}