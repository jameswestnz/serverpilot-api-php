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
	public function request($path, $data=null, $method=Transport::SP_HTTP_METHOD_GET) {

        $header = FALSE;
        $debug = TRUE;

		$url = Transport::SP_API_ENDPOINT . $path;

		$ch = curl_init();
		$options = array(
			// general
			CURLOPT_URL => $url,
			CURLOPT_TIMEOUT => $this->requestTimeout,
			CURLOPT_USERAGENT => Transport::SP_USERAGENT,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_ENCODING => 'gzip',

            // debug
            CURLOPT_HEADER => $header,
            CURLOPT_VERBOSE => $debug,

			// ssl
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_SSL_VERIFYHOST => 0,

			// auth
			CURLOPT_USERPWD => "$this->client_id:$this->api_key",
			CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
		);

		// handle the data
		switch($method) {
			case Transport::SP_HTTP_METHOD_GET:
				if($data !== null && !empty($data)) $options[CURLOPT_URL] = $url . '?' . implode('&', $data);
			break;
			case Transport::SP_HTTP_METHOD_POST:
				if($data === null || empty($data)) throw new Exception('ServerPilot\Transports\Curl::request() - parameter 2 is required for method Transport::SP_HTTP_METHOD_POST');

				$data = json_encode($data);

				$options[CURLOPT_CUSTOMREQUEST] = Transport::SP_HTTP_METHOD_POST;
				$options[CURLOPT_POST] = TRUE;
				$options[CURLOPT_POSTFIELDS] = $data;

				$options[CURLOPT_HTTPHEADER] = array(
				    'Content-Type: application/json',
				    'Content-Length: ' . strlen($data)
				);
			break;
			case Transport::SP_HTTP_METHOD_DELETE:
				$options[CURLOPT_CUSTOMREQUEST] = Transport::SP_HTTP_METHOD_DELETE;
			break;
		}

		// set the options
		curl_setopt_array($ch, $options);

		// response
        $response = curl_exec($ch);
        $status = !empty($response);

        $output = array(
            'url'       => $url,
            'params'    => $data,
            'status'    => $status,
            'error'     => '',
            'error_no'  => (curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200) ? curl_getinfo($ch, CURLINFO_HTTP_CODE) : '',
            'http_code' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
            'debug'     => $debug ? curl_getinfo($ch) : '',
        );

		// close connection
        curl_close($ch);


        // remove some weird headers HTTP/1.1 100 Continue or HTTP/1.1 200 OK
        $response = preg_replace('#HTTP/[\d.]+\s+\d+\s+\w+[\r\n]+#si', '', $response);
        $response = trim($response);
        $response = json_decode($response, true);

        if($output['http_code'] != 200) {
            $output['error_no'] = $output['http_code'];
            $output['error'] = $response['error']['message'];
        } else {
            $output['data'] = $response['data'];
        }

        return $output;
	}
}

/**
 * ServerPilot Exceptions
 */
class Exception extends \Exception {
    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, Exception $previous = null) {
        $message = '[ServerPilot]: ' . $message;

        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }
}