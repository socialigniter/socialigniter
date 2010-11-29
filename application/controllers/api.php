<?php
class Api extends Public_Controller 
{ 
    function __construct() 
    {
        parent::__construct();
        
        $this->data['page_title'] = 'Api';
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
        $data['server']	= base_url().'api/';
        
        if($_POST)
        {
        	$this->load->library('rest', array('server' => $this->input->post('server')));
	        
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
		
        	$this->load->view(config_item('site_theme').'/api/testing', $data);
        }
        else
        {
        	$data['result'] = '';
        
        	$this->load->view(config_item('site_theme').'/api/testing', $data);
        }
    }
   
}