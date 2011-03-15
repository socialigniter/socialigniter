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
* Created:  06-01-2010
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
    public $modules_scan 			= array();

	function __construct()
	{
        parent::__construct();

        // Site Status or Error
        if (config_item('site_status') === FALSE)
        {
            show_error('Sorry the site is shut for now.');
        }

		// Load Language
 		$this->lang->load('social_igniter', 'english');
 		$this->lang->load('activity_stream', 'english'); 	

        // Load Libraries
        $this->load->library('user_agent');

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
		
		// Site Values
		$this->data['site_url']				= config_item('site_url');
		$this->data['site_title'] 			= config_item('site_title');
		$this->data['page_title'] 			= NULL;
		$this->data['sub_title']			= NULL;
		$this->data['site_description'] 	= config_item('site_description');
		$this->data['site_keywords'] 		= config_item('site_keywords');
		$this->data['site_tagline'] 		= config_item('site_tagline');

		// Set Social Arrays
		$this->config->set_item('social_logins', $this->social_logins);
		$this->config->set_item('social_connections', $this->social_connections);
		$this->config->set_item('social_post', $this->social_post);
		$this->config->set_item('social_checkin', $this->social_checkin);

		// Themes
        if ($this->agent->is_mobile())
        {
            $this->config->set_item('site_theme', $this->data['settings']['themes']['mobile_theme']);
			$this->config->set_item('dashboard_theme', $this->data['settings']['themes']['mobile_theme']);
        }
        else
        {
			$this->config->set_item('site_theme', $this->data['settings']['themes']['site_theme']);
			$this->config->set_item('dashboard_theme', $this->data['settings']['themes']['dashboard_theme']);
        }

		$this->config->set_item('mobile_theme', $this->data['settings']['themes']['mobile_theme']);

		// Dashboard & Public values for logged
		if ($this->social_auth->logged_in())
		{
			// OAuth Tokens
			$this->data['oauth_consumer_key'] 	= $this->session->userdata('consumer_key');
			$this->data['oauth_consumer_secret']= $this->session->userdata('consumer_secret');
			$this->data['oauth_token'] 			= $this->session->userdata('token');
			$this->data['oauth_token_secret'] 	= $this->session->userdata('token_secret');

			// Logged Values
			$this->data['logged_user_id']		= $this->session->userdata('user_id');
			$this->data['logged_user_level_id']	= $this->session->userdata('user_level_id');
			$this->data['logged_username']		= $this->session->userdata('username');
			$this->data['logged_name']			= $this->session->userdata('name');
			$this->data['logged_image'] 		= $this->social_igniter->profile_image($this->session->userdata('user_id'), $this->session->userdata('image'), $this->session->userdata('gravatar'));
			$this->data['logged_location']		= $this->session->userdata('location');
			$this->data['logged_geo_enabled']	= $this->session->userdata('geo_enabled');
			$this->data['logged_privacy']		= $this->session->userdata('privacy');

			// Various Links
			$this->data['link_home']			= base_url()."home";
			$this->data['link_profile']			= base_url()."profile/".$this->session->userdata('username');
			$this->data['link_settings']		= base_url()."settings/profile";
			$this->data['link_logout']			= base_url().'logout';

			// Action Paths
			$this->data['comments_post']		= base_url().'api/comments/create/id';

			// Site Forms
			$this->data['comments_write_form']	= 'comments_logged_form';
		}
		else
		{
			// OAuth Tokens
			$this->data['oauth_consumer_key'] 	= '';
			$this->data['oauth_consumer_secret']= '';
			$this->data['oauth_token'] 			= '';
			$this->data['oauth_token_secret'] 	= '';

			// Logged Values	
			$this->data['logged_user_id']		= '';	
			$this->data['logged_user_level_id']	= '';
			$this->data['logged_username']		= '';
			$this->data['logged_image'] 		= base_url().config_item('users_images_folder').'medium_'.config_item('profile_nopicture');
			$this->data['logged_name']			= 'Your Name';
			$this->data['logged_location']		= '';
			$this->data['logged_geo_enabled']	= '';
			$this->data['logged_privacy']		= '';

			// Action Paths
			$this->data['comments_post']		= base_url().'comments/public';

			// Site Forms
			$this->data['comments_write_form']	= 'comments_public_form';
		}

		// Site Paths
		$this->data['shared_images']		= base_url().'images/shared/';
		$this->data['site_images']			= base_url().'uploads/sites/'.config_item('site_id').'/';
		$this->data['views']				= base_url().'application/views/';
		$this->data['site_assets']			= base_url().'application/views/'.$this->data['settings']['themes']['site_theme'].'/assets/';
		$this->data['dashboard_assets']		= base_url().'application/views/'.$this->data['settings']['themes']['dashboard_theme'].'/assets/';	
		$this->data['mobile_assets']		= base_url().'application/views/'.$this->data['settings']['themes']['mobile_theme'].'/assets/';
		$this->data['profiles']				= base_url().'profile/';

        // Set the current controller and action name
        $this->controller_name 				= $this->router->fetch_directory().$this->router->fetch_class();
        $this->action_name     				= $this->router->fetch_method();

		// For rendering pages in a modeule
      	$this->module_name     				= $this->router->fetch_module();
        $this->module_controller 			= $this->router->fetch_class();
        
		// Scann Modules directory
		$this->modules_scan = $this->social_igniter->scan_modules();        

        // For Debugging
        $this->output->enable_profiler(config_item('enable_profiler'));
	}
}