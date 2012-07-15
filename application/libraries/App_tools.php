<?php  if  ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
App Tools Library

@package		Social Igniter
@subpackage		App Tools Library
@author			Brennan Novak
@link			http://social-igniter.com

This class contains all the basic install functions for core and app installs
*/
 
class App_tools
{
	protected $ci;
	protected $template_path;

	function __construct()
	{
		$this->ci =& get_instance();
		
		// Load Things
  		$this->ci->load->helper('file');		
		$this->ci->load->model('settings_model');
		$this->ci->load->model('sites_model');
		
		$this->template_path = './application/modules/app-template/';
	}	
	
	function check_app_exists($app_url)
	{
    	if (file_exists(APPPATH.'modules/'.$app_url))
        {
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	// Makes 'app-template' into a custom named App
	function create_app_template($app_name, $app_url, $app_class)
	{	
		// Install Path
		$app_url		= strtolower($app_url);
		$app_class 		= strtolower($app_class);
		$install_path	= "./application/modules/".$app_url."/";
		$folders		= array('assets', 'config', 'controllers', 'views');
		
		make_folder($install_path);
		
		foreach ($folders as $folder)
		{
			make_folder($install_path.$folder.'/');
		}

		// Assets
		$asset1_current	= file_get_contents($this->template_path."assets/app-template_24.png", FILE_USE_INCLUDE_PATH);
		file_put_contents($install_path."assets/".$app_url."_24.png", $asset1_current);
		$asset2_current	= file_get_contents($this->template_path."assets/app-template_32.png", FILE_USE_INCLUDE_PATH);
		file_put_contents($install_path."assets/".$app_url."_32.png", $asset2_current);

		// Configs
		$this->create_app_configs($app_name, $app_url, $app_class);
		
		// Controllers
		$this->create_app_controllers($app_name, $app_url, $app_class);
		
		// Views		
		$this->create_app_views($app_name, $app_url, $app_class);
		
		
		
	}
	
	function create_app_configs($app_name, $app_url, $app_class)
	{
		// Install Path
		$install_path	= "./application/modules/".$app_url."/";
		$configs		= array('install', 'routes', 'widgets');

		// Config
		$config_current	= file_get_contents($this->template_path."config/app_template.php", FILE_USE_INCLUDE_PATH);
		$config_current	= str_replace("{APP_NAME}", $app_name, $config_current);
		$config_current	= str_replace("{APP_URL}", $app_url, $config_current);
		$config_current	= str_replace("{APP_CLASS}", $app_class, $config_current);
		$config_current	= str_replace("{SITE_NAME}", config_item('site_title'), $config_current);
		$config_current	= str_replace("{SITE_ADMIN}", config_item('site_admin_email'), $config_current);
		file_put_contents($install_path."config/".$app_class.".php", $config_current);

		// Config Files
		foreach ($configs as $config)
		{
			// Install
			$config_current	= file_get_contents($this->template_path."config/".$config.".php", FILE_USE_INCLUDE_PATH);
			$config_current	= str_replace("{APP_NAME}", $app_name, $config_current);
			$config_current	= str_replace("{APP_URL}", $app_url, $config_current);
			$config_current	= str_replace("{APP_CLASS}", $app_class, $config_current);
			$config_current	= str_replace("{SITE_NAME}", config_item('site_title'), $config_current);
			$config_current	= str_replace("{SITE_ADMIN}", config_item('site_admin_email'), $config_current);
			file_put_contents($install_path."config/".$config.".php", $config_current);				
		}		
	}

	function create_app_controllers($app_name, $app_url, $app_class)
	{
		// Install Path
		$install_path	= "./application/modules/".$app_url."/";
		$controllers	= array('api', 'home', 'settings');

		// Main Controllers
		$config_current	= file_get_contents($this->template_path."controllers/app_template.php", FILE_USE_INCLUDE_PATH);
		$config_current	= str_replace("{APP_NAME}", $app_name, $config_current);
		$config_current	= str_replace("{APP_URL}", $app_url, $config_current);
		$config_current	= str_replace("{APP_CLASS}", ucwords($app_class), $config_current);
		$config_current	= str_replace("{SITE_NAME}", config_item('site_title'), $config_current);
		$config_current	= str_replace("{SITE_ADMIN}", config_item('site_admin_email'), $config_current);
		file_put_contents($install_path."controllers/".$app_class.".php", $config_current);

		// Controllers
		foreach ($controllers as $controller)
		{
			// Install
			$config_current	= file_get_contents($this->template_path."controllers/".$controller.".php", FILE_USE_INCLUDE_PATH);
			$config_current	= str_replace("{APP_NAME}", $app_name, $config_current);
			$config_current	= str_replace("{APP_URL}", $app_url, $config_current);
			$config_current	= str_replace("{APP_CLASS}", $app_class, $config_current);
			$config_current	= str_replace("{SITE_NAME}", config_item('site_title'), $config_current);
			$config_current	= str_replace("{SITE_ADMIN}", config_item('site_admin_email'), $config_current);
			file_put_contents($install_path."controllers/".$controller.".php", $config_current);				
		}		
	}

	function create_app_views($app_name, $app_url, $app_class)
	{
		// Install Path
		$install_path = "./application/modules/".$app_url."/";
		$view_folders = array($app_class, 'home', 'partials', 'settings');
		$views	  	  = array('home/custom', 'partials/head_dashboard', 'partials/head_site', 'partials/navigation_home', 'partials/sidebar_tools', 'settings/index', 'settings/widgets');

		// Views
		foreach ($view_folders as $folder)
		{
			make_folder($install_path.'views/'.$folder);
		}

		// App Index
		$config_current	= file_get_contents($this->template_path."views/app_template/index.php", FILE_USE_INCLUDE_PATH);
		$config_current	= str_replace("{APP_NAME}", $app_name, $config_current);
		$config_current	= str_replace("{APP_URL}", $app_url, $config_current);
		$config_current	= str_replace("{APP_CLASS}", $app_class, $config_current);
		file_put_contents($install_path."views/".$app_class."/index.php", $config_current);
		
		// Partials & Settings
		foreach ($views as $view)
		{
			$config_current	= file_get_contents($this->template_path."views/".$view.".php", FILE_USE_INCLUDE_PATH);
			$config_current	= str_replace("{APP_NAME}", $app_name, $config_current);
			$config_current	= str_replace("{APP_URL}", $app_url, $config_current);
			$config_current	= str_replace("{APP_CLASS}", $app_class, $config_current);
			file_put_contents($install_path."views/".$view.".php", $config_current);
		}
	}

}