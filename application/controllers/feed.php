<?php
class Feed extends MY_Controller 
{ 
    function __construct() 
    {
        parent::__construct();
        
        $this->load->helper('xml');        
    }
    
    function index()
    {
    	redirect('feed/rss');
    }
 
 	function rss()
 	{
 	
 	 	$this->data['site_url']		= base_url();
		$this->data['encoding']		= 'utf-8'; // the encoding  
		$this->data['language'] 	= 'en-en'; // the language
		$this->data['site_admin']	= config_item('admin_email').' ('.config_item('site_title').')';
		
		$this->data['contents'] 	= $this->social_igniter->get_content_recent(10);  

	    $this->output->set_header('Content-type:application/rss+xml');
		echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
        echo $this->load->view('feeds/rss', $this->data, true);  
 
  	}
 	
}