<?php
class Testing extends Public_Controller 
{ 
    function __construct() 
    {
        parent::__construct();
                
        $this->data['page_title'] = 'API';
    }

    function index()
    {      
        $data = array();
    	
        $this->load->library('rest', array('server' => base_url().'api/'));
        
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

   
}