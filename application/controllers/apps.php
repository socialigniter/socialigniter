<?php
class Apps extends Dashboard_Controller
{ 
    function __construct() 
    {
        parent::__construct();
     
		if ($this->session->userdata('user_level_id') != 1) redirect(base_url().config_item('home_view_redirect'), 'refresh');     
        
        $this->data['page_title'] = 'Apps';
        
        $this->load->model('image_model');
    } 
 
 	function index()
 	{
		$this->data['sub_title']		= 'Apps';
		$this->data['core_modules']		= config_item('core_modules');
		$this->data['ignore_modules']	= config_item('ignore_modules');
		$this->data['modules']			= $this->social_igniter->scan_modules();
		$this->data['shared_ajax'] 	   .= $this->load->view(config_item('dashboard_theme').'/partials/settings_modules_ajax.php', $this->data, true);
		$this->render('dashboard_wide');
	}
	
	function inactive()
	{
		$this->data['sub_title']		= 'Inactive';
		$this->data['ignore_modules']	= config_item('ignore_modules');
		$this->data['modules']			= $this->social_igniter->scan_modules();

		$this->render('dashboard_wide');
	}
	
	function find()
	{
		$this->data['sub_title']		= 'Find';	
		$this->render('dashboard_wide');
	}
	
	function create()
	{
        if (file_exists(APPPATH.'modules/app-template'))
        {
			$this->data['has_app_template']	= TRUE;
		}
		else
		{
			$this->data['has_app_template'] = FALSE;
		}	
	
		$this->data['sub_title']		= 'Create';
		
		$this->render('dashboard_wide');
	}



}