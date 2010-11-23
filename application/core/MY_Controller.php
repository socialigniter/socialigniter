<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/* The MX_Controller class is autoloaded as required */

class MY_Controller extends MX_Controller
{
    protected $data = array();
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
    protected $modules_scan;
    protected $modules_navigation;

	function __construct()
	{
        parent::__construct();
                
        // Site Status or Error
        if (config_item('site_status') === FALSE)
        {
            show_error('Sorry the site is shut for now.');
        }        
        
        // Get Site		
		$site = $this->social_igniter->get_site();
		
		// Get Language		
 		$this->lang->load('activity_stream', 'english'); 	

        // For Mobile Detection
        $this->load->library('user_agent');
        
        // Disable IE7's constant caching
        $this->output->set_header('Expires: Sat, 01 Jan 2000 00:00:01 GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0, max-age=0');
        $this->output->set_header('Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
        $this->output->set_header('Pragma: no-cache');        
				
		// Site Values
		$this->config->set_item('site_url', $site->url);
		
		$this->data['site_title'] 			= $site->title;
		$this->data['site_tagline'] 		= $site->tagline;
		$this->data['site_url']				= $site->url;
		$this->data['page_title'] 			= NULL;
		$this->data['sub_title']			= NULL;
		$this->data['site_description'] 	= $site->description;
		$this->data['site_keywords'] 		= $site->keywords;
		
		// Create Settings
		$settings = $this->social_igniter->get_settings();

		foreach ($settings as $setting)
		{
			$this->data['settings'][$setting->module][$setting->setting] = $setting->value;
			
            $this->config->set_item($setting->module.'_'.$setting->setting, $setting->value);
		
			if (($setting->setting == 'social_login') && ($setting->value == 'TRUE')) $this->social_logins[] = $setting->module;
			if (($setting->setting == 'social_connection') && ($setting->value == 'TRUE')) $this->social_connections[] = $setting->module;
			if (($setting->setting == 'social_post') && ($setting->value == 'TRUE')) $this->social_post[] = $setting->module;
			if (($setting->setting == 'social_checkin') && ($setting->value == 'TRUE')) $this->social_checkin[] = $setting->module;
		}
		
		// Set Social Config Arrays
		$this->config->set_item('social_logins', $this->social_logins);
		$this->config->set_item('social_connections', $this->social_connections);
		$this->config->set_item('social_post', $this->social_post);
		$this->config->set_item('social_checkin', $this->social_checkin);
		
		// Themes
        if ($this->agent->is_mobile())
        {
            $this->config->set_item('site_theme', $this->data['settings']['theme']['mobile']);
			$this->config->set_item('dashboard_theme', $this->data['settings']['theme']['mobile']);
        }
        else
        {
			$this->config->set_item('site_theme', $this->data['settings']['theme']['site']);
			$this->config->set_item('dashboard_theme', $this->data['settings']['theme']['dashboard']);        
        }

		$this->config->set_item('mobile_theme', $this->data['settings']['theme']['mobile']);


		// Dashboard & Public values for logged
		if ($this->social_auth->logged_in())
		{
			$this->data['logged_user_id']		= $this->session->userdata('user_id');
			$this->data['profile_image'] 		= $this->social_igniter->profile_image($this->session->userdata('user_id'), $this->session->userdata('image'), $this->session->userdata('email'));
			$this->data['profile_name']			= $this->session->userdata('name');
			$this->data['link_home']			= base_url()."home";
			$this->data['link_profile']			= base_url()."profile/".$this->session->userdata('username');
			$this->data['link_settings']		= base_url()."settings/profile";
			$this->data['link_logout']			= base_url().'login/logout';
			
			// Action Paths
			$this->data['comments_post']		= base_url().'comments/actions/logged';
			
			// Site Forms	
			$this->data['comments_write_form']	= 'comments_logged_form';
			
			// Message
			$this->data['message']				= '';
		}
		else
		{
			$this->data['profile_image'] 		= base_url().config_item('profile_images').'normal_'.config_item('profile_nopicture');
			$this->data['profile_name']			= 'Your Name';

			// Action Paths
			$this->data['comments_post']		= base_url().'comments/actions/public';
			
			// Site Forms
			$this->data['comments_write_form']	= 'comments_public_form';
		}
		
		// Reusuable Site Paths
		$this->data['shared_images']		= base_url().'images/shared/';
		$this->data['views']				= base_url().'application/views/';
		$this->data['site_assets']			= base_url().'application/views/'.$this->data['settings']['theme']['site'].'/assets/';
		$this->data['dashboard_assets']		= base_url().'application/views/'.$this->data['settings']['theme']['dashboard'].'/assets/';	
		$this->data['mobile_assets']		= base_url().'application/views/'.$this->data['settings']['theme']['mobile'].'/assets/';
		$this->data['profiles']				= base_url().'profile/';
		
		// Set Previous Page
		if (isset($_SERVER['HTTP_REFERER']))
		{
			$this->session->set_userdata('previous_page', $_SERVER['HTTP_REFERER']); 
		}
		else
		{
			$this->session->set_userdata('previous_page', ''); 		
		}
		
        // Set the current controller and action name
        $this->controller_name 				= $this->router->fetch_directory().$this->router->fetch_class();
        $this->action_name     				= $this->router->fetch_method();

		// For rendering pages in a modeule 
      	$this->module_name     				= $this->router->fetch_module();
        $this->module_controller 			= $this->router->fetch_class();
        
        // For Debugging  
        $this->output->enable_profiler(TRUE);
	}
}