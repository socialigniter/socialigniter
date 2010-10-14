<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* bit.ly REST API v3 library for CodeIgniter
*
* @license Creative Commons Attribution 3.0 <http://creativecommons.org/licenses/by/3.0/>
* @version 1.0
* @author Patrick Popowicz <http://patrickpopowicz.com>
* @copyright Copyright (c) 2010, Patrick Popowicz <http://patrickpopowicz.com>
*/
class Bitly {
	
	private $CI;									// CodeIgniter instance

	// bit.ly API parameters
	private $bitly_login;							// bit.ly Login name
	private $bitly_apiKey;							// bit.ly API Key
	private $bitly_x_login;							// End-user's login when make requests on behalf of another bit.ly user.
	private $bitly_x_apiKey;						// End-user's apiKey when make requests on behalf of another bit.ly user.
	private $bitly_format;							// Requested response format. Supported formats: json (default), xml, txt.
	private $bitly_domain;							// Refers to a preferred output domain; either "bit.ly" (default) or "j.mp".

	// bit.ly API variables
	private $api 		= "http://api.bit.ly/v3/";	// bit.ly API target URL
	private $response;								// bit.ly response
   
	function __construct($params = array())
	{
		$this->CI =& get_instance();
		
		log_message('debug', 'bit.ly Class Initialized');

		$this->initialize($params);
	}
	
	// Initializes the library parameters
	public function initialize($params = array())
	{
		$this->response	= '';
		
		// Set API preferences from the config file if they are not passed in the $params array
		foreach (array('bitly_login','bitly_apiKey','bitly_x_login','bitly_x_apiKey','bitly_format','bitly_domain') as $key)
		{
			$this->$key = (isset($params[$key])) ? $params[$key] : $this->CI->config->item($key);
		}
	}
	
	/**
	* Shortens a URL
	*
	* @param string $longUrl Target url to be shortened
	* @param bool $verbose Output flag
	* @return mixed (!verbose) string | (verbose) array
	* @author Patrick Popowicz
	*/
	public function shorten($longUrl, $verbose = FALSE)
	{
		// Make sure all of the required parameters are set and longUrl is a URL
		if (isset($longUrl) AND filter_var($longUrl, FILTER_VALIDATE_URL))
		{
			$params = array(
							'longUrl'	=>	trim($longUrl),
							'format'	=>	$this->bitly_format,
							'domain'	=>	$this->bitly_domain
							);
							
			if ($this->bitly_x_login &&	$this->bitly_x_apiKey)
			{
				$params['x_login']	= $this->bitly_x_login;
				$params['x_apiKey']	= $this->bitly_x_apiKey;
			}
		
			if ($this->_execute('shorten', $params))
			{
				return ($verbose || $this->bitly_format != 'json') ? $this->response : $this->response['data']['url'];
			}
			else { return FALSE; }
		}
		else { return FALSE; }
	}
	
	/**
	* Expands a bit.ly shortUrl or hash
	*
	* @param array $targets Target shortUrls or hashes to expand in numerically indexed array
	* @param bool $verbose Output flag
	* @return mixed (format != json) string | (format == json) array
	* @author Patrick Popowicz
	*/
	public function expand($targets = array(), $verbose = FALSE)
	{
		// Check the targets and build the params array
		if (count($targets))
		{
			foreach ($targets as $key => $value)
			{
				$url = parse_url($value);
				if (!isset($url['host']) && !preg_match('/.ly|.mp/i',$url['path']))
				{
					// Target is a hash
					$params['hash'][] = $value;
				}
				else
				{
					// Target is a shortUrl, make sure we have a full url
					$params['shortUrl'][] = (isset($url['scheme'])) ? $value : 'http://'.$value;
				}
			}
		
			$params['format']	= $this->bitly_format;
		
			if ($this->_execute('expand', $params))
			{
				// Determine what to return
				return ($verbose) ? $this->response : ((count($targets) == 1 && $this->bitly_format == 'json') ? $this->response['data']['expand'][0]['long_url'] : $this->response);
			}
			else { return FALSE; }
		}
		else { return FALSE; }
	}
	
	/**
	* Validates a 3rd party login and apiKey pair
	*
	* @param array $params 3rd party login and apiKey
	* @return mixed (format != json || txt) string | (format == json || txt) bool
	* @author Patrick Popowicz
	*/
	public function validate($params = array(), $verbose = FALSE)
	{
		// Check the targets and build the params array
		if (count($params))
		{		
			$params['format']	= $this->bitly_format;
		
			if ($this->_execute('validate', $params))
			{
				// Determine what to return
				return ($verbose) ? $this->response : (($this->bitly_format == 'json') ? $this->response['data']['valid'] : $this->response);
			}
			else { return FALSE; }
		}
		else { return FALSE; }
	}
	
	/**
	* Returns click information for one or more shortUrls or hashes
	*
	* @param array $targets Target shortUrls or hashes to expand in numerically indexed array
	* @param string $type type of click returned, either user (passed url or hash) or global
	* @param bool $verbose Output flag
	* @return mixed (format != json) string | (format == json) array
	* @author Patrick Popowicz
	*/
	public function clicks($targets = array(), $type = 'user', $verbose = FALSE)
	{
		// Check the targets and build the params array
		if (count($targets))
		{
			foreach ($targets as $key => $value)
			{
				$url = parse_url($value);
				if (!isset($url['host']) && !preg_match('/.ly|.mp/i',$url['path']))
				{
					// Target is a hash
					$params['hash'][] = $value;
				}
				else
				{
					// Target is a shortUrl, make sure we have a full url
					$params['shortUrl'][] = (isset($url['scheme'])) ? $value : 'http://'.$value;
				}
			}
		
			$params['format']	= $this->bitly_format;
		
			if ($this->_execute('clicks', $params))
			{
				// Determine what to return
				return ($verbose) ? $this->response : ((count($targets) == 1 && $this->bitly_format == 'json') ? $this->response['data']['clicks'][0][$type.'_clicks'] : $this->response);
			}
			else { return FALSE; }
		}
		else { return FALSE; }
	}

	/**
	* Validates a Pro Domain
	*
	* @param string bit.ly pro domain
	* @return mixed (format != json) string | (format == json) bool
	* @author Patrick Popowicz
	*/
	public function pro_domain($domain = '', $verbose = FALSE)
	{
		// Check the targets and build the params array
		$params['domain']	= $domain;
		$params['format']	= $this->bitly_format;
		
		if ($this->_execute('bitly_pro_domain', $params))
		{
			// Determine what to return
			return ($verbose) ? $this->response : (($this->bitly_format == 'json') ? $this->response['data']['bitly_pro_domain'] : $this->response);
		}
		else { return FALSE; }
	}

	/**
	* Executes the API request using cURL
	*
	* @param string $method API method being used
	* @param bool $verbose Output flag
	* @return bool
	* @author Patrick Popowicz
	*/
	private function _execute($method, $params)
	{
		// Add in the primary login and apiKey
		$params = array_merge(array('login' => $this->bitly_login, 'apiKey' => $this->bitly_apiKey), $params);
		
		// Create the argument string
		$target = $this->api . $method . '?';
		
		foreach ($params as $key => $value)
		{
			if (!is_array($value))
			{
				$target .= http_build_query(array($key => $value)) . '&';
			}
			else
			{
				foreach ($value as $sub)
				{
					$target .= http_build_query(array($key => $sub)) . '&';
				}
			}
		}
		
		// Use cURL to fetch
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($curl, CURLOPT_URL, $target);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		
		if ($response = curl_exec($curl))
		{
			$this->response = ($this->bitly_format == 'json') ? json_decode($response, TRUE) : $response;
			return TRUE;
		}
		else { return FALSE; }
	}
	
	/**
	* Returns the response value
	*
	* @return mixed
	* @author Patrick Popowicz
	*/
	public function response()
	{
		return $this->response;
	}
}

/* End of file Bitly.php */
/* Location: ./application/libraries/Bitly.php */