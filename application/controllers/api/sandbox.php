<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * API Sandbox : Social-Igniter
 *
 * Displays API calls for core and includes documentation from modules
 */
class Sandbox extends MY_Controller 
{
    function __construct() 
    {
        parent::__construct();
                
        $this->data['page_title'] = 'API Sandbox';
    }
    
 
	function index()
	{
        $this->load->library('rest', array('server' => base_url().'api/')); 
        
        $this->data['request_url']		= base_url().'api/'.$this->input->post('uri', TRUE);
        $this->data['response_string']	= '';
        $this->data['error_string']		= '';
        $this->data['info']				= '';
        
        if($_POST)
        {
	        $method		= trim($this->input->post('method', TRUE));
	        $uri		= trim($this->input->post('uri', TRUE));
	        $format 	= trim($this->input->post('format', TRUE));
			$params 	= $this->input->post('params', TRUE);

	        $this->rest->format($format);
	        $this->rest->api_key('foo');
	        $this->rest->language('en-GB, pr');
	        

			$this->data['result']	= $this->rest->{$method}($uri, $params);
			$this->data['debug'] 	= $this->rest->debug();	        

			$this->load->view('api/sandbox', $this->data);
        }
		else
		{
			echo 'Opps, something went wrong with your tonka truck. Better stay in the sandbox';			
		}
	}

}