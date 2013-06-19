<?php

/**
 * Feeds
 * 
 * A controller for presenting feeds
 * 
 * @package Social Igniter\Controllers
 * @see MY_Controller
 */
class Feeds extends MY_Controller 
{ 
    function __construct() 
    {
        parent::__construct();
        
        $this->load->helper('xml');        
    }
    
 	function index()
 	{
 	 	$this->data['site_url']		= base_url();
 	 	$this->data['site_feed']	= base_url().'feed';
		$this->data['encoding']		= 'utf-8'; 
		$this->data['language'] 	= 'en-en';
		$this->data['site_admin']	= config_item('site_admin_email').' ('.config_item('site_title').')';
		$this->data['contents'] 	= $this->social_igniter->get_content_recent('all', 20);  

	    $this->output->set_header('Content-type:application/rss+xml');
        echo $this->load->view('feeds/rss', $this->data, true);
  	}


  	function comments()
  	{
 	 	$this->data['site_url']		= base_url();
 	 	$this->data['site_feed']	= base_url().'feed';
		$this->data['encoding']		= 'utf-8';  
		$this->data['language'] 	= 'en-en';
		$this->data['site_admin']	= config_item('admin_email').' ('.config_item('site_title').')';
		$this->data['comments'] 	= $this->social_tools->get_comments_recent('all', 20);  

	   	$this->output->set_header('Content-type:application/rss+xml');
        echo $this->load->view('feed/comments', $this->data, true);
  	}
 	 	
 	
}