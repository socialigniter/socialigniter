<?php
class Site extends Dashboard_Controller
{

	function __construct()
	{
		parent::__construct();	
		
		// If not Super redirect
		if ($this->data['logged_user_level_id'] > 1) redirect('home');		

		$this->data['page_title'] = 'Site';
	}	

	function index()
	{
		$this->render();
	}


	function settings()
	{
		$this->data['sub_title'] = 'Settings';
		$this->render();	
	}

	function themes()
	{	
		$this->data['site']					= $this->social_igniter->get_site();
		$this->data['site_themes']			= $this->social_igniter->get_themes('site');
		$this->data['dashboard_themes']		= $this->social_igniter->get_themes('dashboard');
		$this->data['mobile_themes']		= $this->social_igniter->get_themes('mobile');
		$this->data['sub_title'] 			= 'Themes';		
		
		$this->render();
	}

	function widgets()
	{
		$this->data['sub_title'] = 'Widgets';
		$this->render();
	}

	function services()
	{
		$this->data['sub_title'] = 'Services';
		$this->render();	
	}
	

	function create()
	{
		$this->data['sub_title'] = 'Create Site';
		$this->render();	
	}	
	
}
