<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * @author Philip Sturgeon
 * @created 04/06/2009
 */

class Rest
{
    private $ci; // CodeIgniter instance

    private $rest_server;

    private $supported_formats = array(
		'xml' 				=> 'application/xml',
		'json' 				=> 'application/json',
		'serialize' 		=> 'application/vnd.php.serialized',
		'php' 				=> 'text/plain',
	);

    private $auto_detect_formats = array(
		'application/xml' 	=> 'xml',
		'text/xml' 			=> 'xml',
		'application/json' 	=> 'json',
		'text/json' 		=> 'json',
    	'application/vnd.php.serialized' => 'serialize'
	);

	private $format;
	private $mime_type;
    private $response_string;

    function __construct($config = array())
    {
        $this->ci =& get_instance();
        log_message('debug', 'REST Class Initialized');

		$this->ci->load->library('curl');

		// If a URL was passed to the library
		if(!empty($config))
		{
			$this->initialize($config);
		}
    }

    public function initialize($config)
    {
		$this->rest_server = @$config['server'];

		if(substr($this->rest_server, -1, 1) != '/')
		{
			$this->rest_server .= '/';
		}

		$this->http_auth = isset($config['http_auth']) ? $config['http_auth'] : '';
		$this->http_user = isset($config['http_user']) ? $config['http_user'] : '';
		$this->http_pass = isset($config['http_pass']) ? $config['http_pass'] : '';
    }


    public function get($uri, $params = array(), $format = NULL)
    {
        if($params)
        {
        	$uri .= '?'.(is_array($params) ? http_build_query($params) : $params);
        }

    	return $this->_call('get', $uri, NULL, $format);
    }


    public function post($uri, $params = array(), $format = NULL)
    {
        return $this->_call('post', $uri, $params, $format);
    }


    public function put($uri, $params = array(), $format = NULL)
    {
        return $this->_call('put', $uri, $params, $format);
    }


    public function delete($uri, $params = array(), $format = NULL)
    {
        return $this->_call('delete', $uri, $params, $format);
    }

    public function api_key($key, $name = 'X-API-KEY')
	{
		$this->ci->curl->http_header($name, $key);
	}

    public function language($lang)
	{
		if(is_array($lang))
		{
			$lang = implode(', ', $lang);
		}

		$this->ci->curl->http_header('Accept-Language', $lang);
	}

    private function _call($method, $uri, $params = array(), $format = NULL)
    {
    	if($format !== NULL)
		{
			$this->format($format);
		}

		$this->_set_headers();

        // Initialize cURL session
        $this->ci->curl->create($this->rest_server.$uri);

        // If authentication is enabled use it
        if($this->http_auth != '' && $this->http_user != '')
        {
        	$this->ci->curl->http_login($this->http_user, $this->http_pass, $this->http_auth);
        }

        // We still want the response even if there is an error code over 400
        $this->ci->curl->option('failonerror', FALSE);

        // Call the correct method with parameters
        $this->ci->curl->{$method}($params);

        // Execute and return the response from the REST server
        $response = $this->ci->curl->execute();

        // Format and return
        return $this->_format_response($response);
    }


    // If a type is passed in that is not supported, use it as a mime type
    public function format($format)
	{
		if(array_key_exists($format, $this->supported_formats))
		{
			$this->format = $format;
			$this->mime_type = $this->supported_formats[$format];
		}

		else
		{
			$this->mime_type = $format;
		}

		return $this;
	}

	public function debug()
	{
		$request = $this->ci->curl->debug_request();
		
		$data['request_url']		= $request['url'];
		$data['response_string']	= $this->response_string;
		$data['error_string']		= $this->ci->curl->error_string;
		$data['error_code']			= $this->ci->curl->error_code;
		$data['info'] 				= $this->ci->curl->info;

		return $this->ci->load->view(config_item('site_theme').'/api/sandbox_debug', $data, true);
	}

	private function _set_headers()
	{
		$this->ci->curl->http_header('Accept: '.$this->mime_type);
	}

	private function _format_response($response)
	{
		$this->response_string =& $response;

		// It is a supported format, so just run its formatting method
		if(array_key_exists($this->format, $this->supported_formats))
		{
			return $this->{"_".$this->format}($response);
		}

		// Find out what format the data was returned in
		$returned_mime = @$this->ci->curl->info['content_type'];

		// If they sent through more than just mime, stip it off
		if(strpos($returned_mime, ';'))
		{
			list($returned_mime)=explode(';', $returned_mime);
		}

		$returned_mime = trim($returned_mime);

		if(array_key_exists($returned_mime, $this->auto_detect_formats))
		{
			return $this->{'_'.$this->auto_detect_formats[$returned_mime]}($response);
		}

		return $response;
	}


    // Format XML for output
    private function _xml($string)
    {
    	return (array) simplexml_load_string($string);
    }

    // Encode as JSON
    private function _json($string)
    {
    	return json_decode(trim($string));
    }

    // Encode as Serialized array
    private function _serialize($string)
    {
    	return unserialize(trim($string));
    }

    // Encode raw PHP
    private function _php($string)
    {
    	$string = trim($string);
    	$populated = array();
    	eval("\$populated = \"$string\";");
    	return $populated;
    }

}
// END REST Class

/* End of file REST.php */
/* Location: ./application/libraries/REST.php */