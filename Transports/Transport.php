<?php

namespace ServerPilotAPI\Transports;

class Transport
{
	public $cacheTime = 3600;
	public $requestTimeout = 30;
	public $apiURL = 'https://api.serverpilot.io/v1';
	
	/**  Location for overloaded data.  */
    protected $data = array();
    
	public function __construct($client_id, $api_key)
	{
		$this->setCredentials($client_id, $api_key);
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