<?php
class Api extends Public_Controller 
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
    

    function testing()
    {      
        $data 			= array();

		$params = array(
        	'server' 	=> base_url().'api/',
        	'http_auth' => 'digest',
        	'http_user' => 'admin',
        	'http_pass' => '1234'
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

			if(in_array($method, array('put', 'post', 'get', 'delete')))
			{
				$data['result'] = $this->rest->{$method}($uri, $params);
			}
			
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
   
}