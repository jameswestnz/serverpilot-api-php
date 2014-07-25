<?php
namespace ServerPilotAPI\Transports;

// load main Transport class for extending
require_once 'Transport.php';

// now use it
use ServerPilotAPI\Transports\Transport;

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
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        	'Accept: application/json'
        ));
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
        
        // need to check headers/response and ensure no errors
        // should probably throw an exception if not a 200
        
        $response = json_decode($response);
        
        return $response;
	}
}