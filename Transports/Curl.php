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
	public function request($path, $data=array(), $method=Transport::SP_HTTP_METHOD_GET) {
		$url = Transport::SP_API_ENDPOINT . $path;
		
		$ch = curl_init();
		$options = array(
			// general
			CURLOPT_URL => $url,
			CURLOPT_TIMEOUT => $this->requestTimeout,
			CURLOPT_USERAGENT => Transport::SP_USERAGENT,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_ENCODING => 'gzip',
			
			// ssl
			CURLOPT_SSL_VERIFYPEER => FALSE,
			CURLOPT_SSL_VERIFYHOST => FALSE,
			
			// auth
			CURLOPT_USERPWD => "$this->client_id:$this->api_key",
			CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
			
			// request
			CURLOPT_CUSTOMREQUEST => $method
		);
		
		// send the data
		if(!empty($data)) {
			switch($method) {
				case Transport::SP_HTTP_METHOD_GET:
					$options[CURLOPT_URL] = $url . '?' . implode('&', $data);
				break;
				case Transport::SP_HTTP_METHOD_POST: 
					$data = json_encode($data);
					
					$options[CURLOPT_POST] = TRUE;
					$options[CURLOPT_POSTFIELDS] = $data;
					
					$options[CURLOPT_HTTPHEADER] = array(                                                                          
					    'Content-Type: application/json',                                                                                
					    'Content-Length: ' . strlen($data)                                                                      
					);
				break;
			}
		}
		
		// set the options
		curl_setopt_array($ch, $options);
		
		// response
        $response = curl_exec($ch);
		//$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //$info = curl_getinfo($ch);
		
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
        // testing for 200 only is dangerous - what about the other success responses?
        /*if($status_code !== 200) {
	        throw new \Exception('HTTP Error ' . $status_code);
        }*/
        
        return $response;
	}
}