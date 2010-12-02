<?php
class Api extends Public_Controller 
{ 
    function __construct() 
    {
        parent::__construct();
        
		if (!$this->social_auth->logged_in()) redirect(base_url());        
        
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
    

    function testing()
    {      
        $data = array();

		$params = array(
        	'server' 	=> base_url().'api/',
        	'http_auth' => 'digest',
        	'http_user' => 'site',
        	'http_pass' => 'ff91fd16832111f1a5ffdb0f37e1a756'
        );    
    	
        $this->load->library('rest', $params);        
        
        if($_POST)
        {
	        $method		= trim($this->input->post('method', TRUE));
	        $uri		= trim($this->input->post('uri', TRUE));
	        $format 	= trim($this->input->post('format', TRUE));
			$params 	= $this->input->post('params', TRUE);

	        $this->rest->format($format);
	        $this->rest->api_key('foo');
	        $this->rest->language('en-GB, pr');

			$data['result'] = $this->rest->{$method}($uri, $params);			
			$data['debug'] = $this->rest->debug();
		
        	$this->load->view(config_item('site_theme').'/api/testing', $data);
        }
        else
        {
        	$data['result'] = '';
        	$data['debug']	= '';
        
        	$this->load->view(config_item('site_theme').'/api/testing', $data);
        }
    }
    
    function call()
    {
		if ($this->social_auth->logged_in())
		{
		
			$rest_params = array(
	        	'server' 	=> base_url().'api/',
	        	'http_auth' => 'digest',
	        	'http_user' => 'site',
	        	'http_pass' => 'ff91fd16832111f1a5ffdb0f37e1a756'
	        );    
	    	
	        $this->load->library('rest', $rest_params);

			echo = $this->rest->{$method}($uri, $params);			
			
        
        }
    
    }
   
}