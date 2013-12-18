<?php

/**
 * Feed
 * 
 * A controller for presenting feeds
 * 
 * @package Social Igniter\Controllers
 * @see MY_Controller
 */
class Feed extends MY_Controller 
{ 
  function __construct() 
  {
    parent::__construct();    
    $this->load->helper('xml');        
  }

 	function index()
 	{
 	 	$this->data['site_url']		   = base_url();
 	 	$this->data['site_feed']	   = base_url().'feed';
		$this->data['encoding']		   = 'utf-8'; 
		$this->data['language'] 	   = 'en-en';
		$this->data['site_admin']	   = config_item('site_admin_email').' ('.config_item('site_title').')';
    $this->data['item_base_url'] = base_url();
    $this->data['contents'] 	   = $this->social_igniter->get_content_recent('post', 25);
    $this->output->set_header('Content-type:application/rss+xml');
    echo $this->load->view('feeds/rss', $this->data, true);
  }
 	
}