<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Oauth_Controller extends Rest_Controller
{
	public $oauth_user_id;
  
    function __construct($config = array())
    {
        parent::__construct();        
    }

    function string_begins_with($string, $search)
    {
        return (strncmp($string, $search, strlen($search)) == 0);
    }
    
    function rest_method_exists($method)
    {
        return in_array($method.'_'.$this->request->method, get_class_methods(get_class($this)));
    }
    
    function _remap($method)
    {
        // if CI gives us "foo", we need to check if "foo_authd_<request_method>" exists. 
        // if so, ensure authentication and pass "foo_authd" to REST controller otherwise
        // pass the method name unchanged to REST controller for normal processing (including 404)
        $authd_method = $method."_authd";
        
        if ($this->rest_method_exists($authd_method))
        {
            if (!$this->social_auth->request_is_signed())
            {
                return $this->response(array('status' => 'error', 'message' => 'Request is not signed.'), 200);
            }

            if (!$this->oauth_user_id = $this->social_auth->get_oauth_user_id())
            {
                return $this->response(array('status' => 'error', 'message' => 'Invalid OAuth signature!'), 200);
            }
            
            $method = $authd_method;
        }

        parent::_remap($method);
    }
}