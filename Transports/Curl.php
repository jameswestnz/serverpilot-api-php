<?php
namespace ServerPilot\Transports;

// load main Transport class for extending
require_once 'Transport.php';

// now use it
use ServerPilot\Transports\Transport;

class Curl extends Transport
{
	/**
	 * core request function
	 *
	 * used as the main communication layer between API and local code
	 *
	 * @param string $method defines the method for the request
	 *
	 * @return void
	 */
	public function request($path, $data=array()) {
		$path = $this->apiURL . $path;
		
		$ch = curl_init($path);
		// general
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->requestTimeout);
        curl_setopt($ch, CURLOPT_USERAGENT, 'ServerPilot PHP Wrapper');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        
		// ssl
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		//curl_setopt($ch, CURLOPT_CAINFO, 'api.serverpilot.io.crt');
        
        // auth
		curl_setopt($ch, CURLOPT_USERPWD, "$this->client_id:$this->api_key");
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		
		// POST
		if(!empty($data)) {
			$data = json_encode($data);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); // needs to RESTful
	        curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			    'Content-Type: application/json',                                                                                
			    'Content-Length: ' . strlen($data)                                                                      
			));
		}
		
		// response
        $response = curl_exec($ch);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $info = curl_getinfo($ch);
		
        curl_close($ch);
        
        // if we get here, assume we have a JSON string - decode
        if($response = json_decode($response)) {
	        // check for any SP specific errors
	        if(!empty($response->error)) {
		        throw new \Exception('ServerPilot Error: ' . $response->error);
	        }
        }
        
        // need to check headers/response and ensure no errors
        // last fallback
        if($status_code !== 200) {
	        throw new \Exception('HTTP Error ' . $status_code);
        }
        
        return $response;
	}
}