<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Public_Controller 
{ 
    function __construct() 
    {
        parent::__construct();
                
        $this->data['page_title'] = 'API';
    }
    
    function index()
    {
	    $this->data['modules_apis'] = NULL;

		$modules_scan = $this->social_igniter->scan_modules();
		
		foreach ($modules_scan as $module):
		
			$module_api = '/modules/'.$module.'/views/api/index.php';
		
		    if (file_exists(APPPATH.$module_api))
		    {
		    	$this->data['modules_apis'] .= $this->load->view('..'.$module_api, $this->data, true);
		    }		    
			
		endforeach;
		    		
    	$this->render('site_wide');
    }
    
    function sandbox()
    {   
       	$this->load->view(config_item('site_theme').'/api/sandbox');    
    }

	function sandbox_results()
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

			$this->load->view(config_item('site_theme').'/api/sandbox_results', $this->data);
        }
		else
		{
			echo 'Opps, something went wrong with your tonka truck. Better stay in the sandbox';			
		}
	}
       
}