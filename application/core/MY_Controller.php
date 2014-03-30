<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/*
* Name:		MY_Controller Library
* 
* Author:	Brennan Novak
* 		  	contact@social-igniter.com
*         	@brennannovak
* 
* Location: http://github.com/socialigniter
* 
* Description: Library that is extended by all "Public" facing site controllers
*/

/* The MX_Controller class is autoloaded as required */
class MY_Controller extends MX_Controller
{
  protected $data = array();
  protected $site_widgets			= array();
  protected $social_logins		= array();
  protected $social_connections	= array();
	protected $social_post			= array();
	protected $social_checkin		= array();	
  protected $controller_name;
  protected $action_name; 
  protected $page_title;
  protected $module_name;
  protected $module_controller;
  protected $module;
  protected $modules_navigation;
  protected $site_theme;
  public $modules_scan 			= array();

	function __construct()
	{
    parent::__construct();

    // Site Status or Error
    if (config_item('site_status') === FALSE)
    {
        show_error('Sorry the site is shut for now.');
    }

    // Database
    $this->load->database();

		// Load Language
 		$this->lang->load('social_igniter', 'english');
 		$this->lang->load('activity_stream', 'english'); 	

    // Load Libraries
    $this->load->library('session');
    $this->load->library('user_agent');
    $this->load->library('social_auth');
    $this->load->library('social_igniter');
    $this->load->library('social_tools');

    // Disable IE7's constant caching
    $this->output->set_header('Expires: Sat, 01 Jan 2000 00:00:01 GMT');
    $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
    $this->output->set_header('Cache-Control: post-check=0, pre-check=0, max-age=0');
    $this->output->set_header('Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
    $this->output->set_header('Pragma: no-cache');

		// Create Settings
		foreach ($this->social_igniter->get_settings() as $setting)
		{
			$this->data['settings'][$setting->module][$setting->setting] = $setting->value;

      $this->config->set_item($setting->module.'_'.$setting->setting, $setting->value);

			// Make Widgets Array
			if ($setting->module  == 'widgets') $this->site_widgets[] = $setting;
			
			// Make Social Arrays
			if (($setting->setting == 'social_login') 		&& ($setting->value == 'TRUE')) $this->social_logins[] 		= $setting->module;
			if (($setting->setting == 'social_connection')	&& ($setting->value == 'TRUE')) $this->social_connections[] = $setting->module;
			if (($setting->setting == 'social_post') 		&& ($setting->value == 'TRUE')) $this->social_post[] 		= $setting->module;
			if (($setting->setting == 'social_checkin') 	&& ($setting->value == 'TRUE')) $this->social_checkin[] 	= $setting->module;
		}

		// Makes Base URL
		$this->config->set_item('base_url', config_item('site_url'));
		
		// Site Values
		$this->data['site_url']				  = config_item('site_url');
		$this->data['site_title'] 			= config_item('site_title');
		$this->data['page_title'] 			= NULL;
		$this->data['sub_title']			  = NULL;
		$this->data['encoding']		      = 'utf-8'; 
		$this->data['language'] 	      = 'en-en';		
		$this->data['site_description'] = config_item('site_description');
		$this->data['site_keywords'] 		= config_item('site_keywords');
		$this->data['site_tagline'] 		= config_item('site_tagline');
		$this->data['site_admin']	      = config_item('site_admin_email').' ('.config_item('site_title').')';

		// Set Social Arrays
		$this->config->set_item('social_logins', $this->social_logins);
		$this->config->set_item('social_connections', $this->social_connections);
		$this->config->set_item('social_post', $this->social_post);
		$this->config->set_item('social_checkin', $this->social_checkin);

		// Set Themes
		// Is Mobile Browser
    if ($this->agent->is_mobile())
    {
      // Parse JSON Mobile Theme
      $this->config->set_item('site_theme', $this->data['settings']['themes']['mobile_theme']);        	
      $this->site_theme = json_decode(file_get_contents(APPPATH.'views/'.config_item('site_theme').'/theme_schema.json'));
			$this->config->set_item('dashboard_theme', $this->data['settings']['themes']['mobile_theme']);
        
      // Set Source
      $this->data['user_source'] = 'mobile';
    }
    else
    {   
    	// Parse JSON Site Theme
      $this->config->set_item('site_theme', $this->data['settings']['themes']['site_theme']);
    	$this->site_theme = json_decode(file_get_contents(APPPATH.'views/'.config_item('site_theme').'/theme_schema.json'));
			$this->config->set_item('dashboard_theme', $this->data['settings']['themes']['dashboard_theme']);
  
    	// Set Source
    	$this->data['user_source'] = 'web';
    }

		$this->config->set_item('mobile_theme', $this->data['settings']['themes']['mobile_theme']);	
		
		// Google Analytics
		if (config_item('services_google_analytics'))
		{
			$this->data['google_analytics']	= $this->load->view(config_item('dashboard_theme').'/partials/google_analytics', $this->data, true);
		}
		else
		{
			$this->data['google_analytics'] = '';
		}

		
    	// Sets Previous Page
		if (isset($_SERVER['HTTP_REFERER']))
		{
			$this->session->set_userdata('previous_page', $_SERVER['HTTP_REFERER']);
		}
		else
		{
			$this->session->set_userdata('previous_page', '');
		}	
		
		// Set Message
		if ($this->session->flashdata('message') != '')
		{
			$this->data['message']			= $this->session->flashdata('message');
		}
		else
		{
			$this->data['message']			= '';
		}

		// Site Paths
		$this->data['previous_page']		= $this->session->userdata('previous_page');
		$this->data['shared_images']		= base_url().'images/shared/';
		$this->data['site_images']			= base_url().'uploads/sites/'.config_item('site_id').'/';
		$this->data['views']				= base_url().'application/views/';
		$this->data['site_assets']			= base_url().'application/views/'.config_item('site_theme').'/assets/';
		$this->data['dashboard_assets']		= base_url().'application/views/'.config_item('dashboard_theme').'/assets/';	
		$this->data['mobile_assets']		= base_url().'application/views/'.config_item('mobile_theme').'/assets/';
		$this->data['profiles']				= base_url().'people/';

    // Set the current controller and action name
    $this->controller_name 				= $this->router->fetch_directory().$this->router->fetch_class();
    $this->action_name     				= $this->router->fetch_method();

		// For rendering pages in a modeule
  	$this->module_name     				= $this->router->fetch_module();
    $this->module_controller 			= $this->router->fetch_class();
    
		// Scan Modules
		$this->modules_scan = $this->social_igniter->scan_modules();        

    // For Debugging
    $this->output->enable_profiler(config_item('enable_profiler'));
	}
}