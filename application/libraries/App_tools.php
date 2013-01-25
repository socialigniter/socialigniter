<?php  if  ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * App Tools Library
 * 
 * This class contains all the basic install functions for core and app installs
 * 
 * @package	Social Igniter\Libraries
 * @author	Brennan Novak
 * @link	http://social-igniter.com
 */
class App_tools
{
	protected $ci;
	protected $template_path;
	protected $install_path;
	protected $app_name;
	protected $app_url;
	protected $app_class;

	function __construct($config)
	{
		$this->ci =& get_instance();
		
		// Load Things
  		$this->ci->load->helper('file');		
		$this->ci->load->model('settings_model');
		$this->ci->load->model('sites_model');
		
		$this->template_path	= './application/modules/app-template/';
		$this->install_path		= './application/modules/'.$config['app_url'].'/';
		$this->app_name			= $config['app_name'];
		$this->app_url			= $config['app_url'];
		$this->app_class  		= $config['app_class'];
	}
	
	/**
	 * Replace Tags
	 * 
	 * Given a template, replaces tags of the form {TAG_NAME} with ivars and config items.
	 * 
	 * ## Stuff which gets replaced
	 * 
	 * * `{APP_NAME}` with $this -> app_name
	 * * `{APP_URL}` with $this -> app_url
	 * * `{APP_CLASS}` with $this -> app_class
	 * * `{SITE_NAME}` with config_item('site_title')
	 * * `{SITE_ADMIN}` with config_item('site_admin_email')
	 * 
	 * A single custom tag and value can be specified in $replace_tag and _value.
	 * 
	 * @param string $template The path to the template to process OR the string of the template
	 * @param string $replace_tag A single custom tagname (defaults to null)
	 * @param string $replace_value The value to replace instances of $replace_tag with
	 * @param bool $load_template Whether or not $template is a path to be loaded
	 * @return string The data in $template with all tags substituted
	 * @todo Allow more custom tag flexibility by using an assoc. array of custom tags
	 */
	function replace_tags($template, $replace_tag=NULL, $replace_value=NULL, $load_template=TRUE)
	{
		if ($load_template)
		{
			$template_data = file_get_contents($template, FILE_USE_INCLUDE_PATH);
		}
		else
		{
			$template_data = $template;
		}

		$template_data	= str_replace('{APP_NAME}', $this->app_name, $template_data);
		$template_data	= str_replace('{APP_URL}', $this->app_url, $template_data);
		$template_data	= str_replace('{APP_CLASS}', $this->app_class, $template_data);
		$template_data	= str_replace('{SITE_NAME}', config_item('site_title'), $template_data);
		$template_data	= str_replace('{SITE_ADMIN}', config_item('site_admin_email'), $template_data);	
		$template_data	= str_replace($replace_tag, $replace_value, $template_data);
		return $template_data;
	}

	/**
	 * Makes 'app-template' into a custom named App
	 * @return bool Always returns true
	 * @todo Make this return more meaningful data
	 * @todo document properly
	 */
	function create_app_template()
	{
		// Install Path
		$this->app_url		= strtolower($this->app_url);
		$this->app_class 	= strtolower($this->app_class);
		$folders			= array('assets', 'config', 'controllers', 'views');

		make_folder($this->install_path);

		foreach ($folders as $folder)
		{
			make_folder($this->install_path.$folder.'/');
		}

		// Assets
		$asset1_current	= file_get_contents($this->template_path.'assets/app-template_24.png', FILE_USE_INCLUDE_PATH);
		file_put_contents($this->install_path.'assets/'.$this->app_url.'_24.png', $asset1_current);
		$asset2_current	= file_get_contents($this->template_path.'assets/app-template_32.png', FILE_USE_INCLUDE_PATH);
		file_put_contents($this->install_path.'assets/'.$this->app_url.'_32.png', $asset2_current);		

		return TRUE;
	}
	
	/**
	 * Create App Configs
	 * @return bool Always returns true
	 * @todo Make this return more meaningful data
	 * @todo document properly
	 */
	function create_app_configs()
	{
		// Install Path
		$configs = array('routes', 'widgets');

		// Config
		$config_template	= $this->template_path.'config/app_template.php';
		$config_data		= $this->replace_tags($config_template);
		file_put_contents($this->install_path.'config/'.$this->app_class.'.php', $config_data);

		// Config Files
		foreach ($configs as $config)
		{
			// Install
			$config_template	= $this->template_path.'config/'.$config.'.php';
			$config_data 		= $this->replace_tags($config_template);
			file_put_contents($this->install_path.'config/'.$config.'.php', $config_data);
		}

		return TRUE;
	}
	
	/**
	 * Create App Install
	 * @return bool Always returns true
	 * @todo Make this return more meaningful data
	 * @todo document properly
	 */
	function create_app_install($connections, $database, $widgets)
	{
		$install_template	= $this->template_path.'config/install.php';
		$install_data		= file_get_contents($install_template, FILE_USE_INCLUDE_PATH);

		if ($connections == 'oauth')
		{
			$install_connections= file_get_contents($this->template_path.'views/code/install_connections_oauth.code', FILE_USE_INCLUDE_PATH);
			$install_sites 		= file_get_contents($this->template_path.'views/code/install_sites.code', FILE_USE_INCLUDE_PATH);
		}
		elseif ($connections == 'oauth2')
		{
			$install_connections= file_get_contents($this->template_path.'views/code/install_connections_oauth2.code', FILE_USE_INCLUDE_PATH);
			$install_sites 		= file_get_contents($this->template_path.'views/code/install_sites.code', FILE_USE_INCLUDE_PATH);			
		}
		else
		{
			$install_connections 	= '';
			$install_sites			= '';
		}
		
		if ($database == 'TRUE')
		{
			$install_database 	= file_get_contents($this->template_path.'views/code/install_database.code', FILE_USE_INCLUDE_PATH);
		}
		else
		{
			$install_database	= '';
		}

		$install_data = str_replace('{APP_INSTALL_CONNECTIONS}', $install_connections, $install_data);
		$install_data = str_replace('{APP_INSTALL_SITES}', $install_sites, $install_data);
		$install_data = str_replace('{APP_INSTALL_DATABASE}', $install_database, $install_data);
		$install_data = str_replace('{APP_INSTALL_WIDGETS}', $widgets, $install_data);
		$install_data = $this->replace_tags($install_data, '', '', FALSE);
		file_put_contents($this->install_path.'config/install.php', $install_data);

		return TRUE;		
	}
	
	/**
	 * Create App Controllers
	 * @return bool Always returns true
	 * @todo Make this return more meaningful data
	 * @todo document properly
	 */
	function create_app_controllers($api_database, $api_methods, $connections)
	{
		// Main Controller
		$controller_template	= $this->template_path.'controllers/app_template.php';
		$controller_data 		= $this->replace_tags($controller_template, '{APP_CLASS_TITLE}', ucwords($this->app_class));
		file_put_contents($this->install_path.'controllers/'.$this->app_class.'.php', $controller_data);

		// API
		$api_template = file_get_contents($this->template_path.'controllers/api.php', FILE_USE_INCLUDE_PATH);

		if ($api_database == 'TRUE')
		{
			$api_database	= file_get_contents($this->template_path.'views/code/api_database.code', FILE_USE_INCLUDE_PATH);
			$api_template	= str_replace('{APP_API_DATABASE}', $api_database, $api_template);
		}
		else
		{
			$api_template	= str_replace('{APP_API_DATABASE}', '', $api_template);			
		}

		if ($api_methods == 'TRUE')
		{
			$api_methods	= file_get_contents($this->template_path.'views/code/api_methods.code', FILE_USE_INCLUDE_PATH);
			$api_template	= str_replace('{APP_API_METHODS}', $api_methods, $api_template);
		}
		else
		{
			$api_template	= str_replace('{APP_API_METHODS}', '', $api_template);			
		}

		$api_data = $this->replace_tags($api_template, '', '', FALSE);
		file_put_contents($this->install_path.'controllers/api.php', $api_data);			

		// Connections
		if ($connections != 'FALSE')
		{
			$connections_template	= $this->template_path.'controllers/connections_'.$connections.'.php';
			$connections_data 		= $this->replace_tags($connections_template);
			file_put_contents($this->install_path.'controllers/connections.php', $connections_data);			
		}

		// Home & Settings
		foreach (array('home', 'settings') as $controller)
		{
			$controller_template	= $this->template_path.'controllers/'.$controller.'.php';
			$controller_data 		= $this->replace_tags($controller_template);
			file_put_contents($this->install_path.'controllers/'.$controller.'.php', $controller_data);				
		}

		return TRUE;
	}
	
	/**
	 * Create App Views
	 * @return bool Always returns true
	 * @todo Make this return more meaningful data
	 * @todo document properly
	 */
	function create_app_views()
	{
		// Install Path
		$view_folders = array($this->app_class, 'home', 'partials', 'settings');
		$views	  	  = array('home/custom', 'partials/head_dashboard', 'partials/head_site', 'partials/navigation_home', 'partials/sidebar_tools', 'settings/index', 'settings/widgets');

		// Views
		foreach ($view_folders as $folder)
		{
			make_folder($this->install_path.'views/'.$folder);
		}

		// App Index
		$config_template	= $this->template_path.'views/app_template/index.php';
		$config_data 		= $this->replace_tags($config_template);
		file_put_contents($this->install_path.'views/'.$this->app_class.'/index.php', $config_data);
		
		// Partials & Settings
		foreach ($views as $view)
		{
			$config_template	= $this->template_path.'views/'.$view.'.php';
			$config_data 		= $this->replace_tags($config_template);
			file_put_contents($this->install_path.'views/'.$view.'.php', $config_data);
		}

		return TRUE;
	}
	
	/**
	 * Create Helper
	 * @return bool Always returns true
	 * @todo Make this return more meaningful data
	 * @todo document properly
	 */
	function create_helper($helper)
	{
		make_folder($this->install_path.'helpers/');

		// Helper
		$config_template	= $this->template_path.'helpers/app_template_helper.php';
		$config_data 		= $this->replace_tags($config_template);
		file_put_contents($this->install_path.'helpers/'.$this->app_class.'_helper.php', $config_data);

		return TRUE;
	}
	
	/**
	 * Create Libraries
	 * @return bool Always returns true
	 * @todo Make this return more meaningful data
	 * @todo document properly
	 */
	function create_libraries($library, $oauth)
	{
		make_folder($this->install_path.'libraries/');

		// Library
		if ($library != 'FALSE')
		{
			$library_template	= $this->template_path.'libraries/app_template_libary.php';
			$library_data 		= $this->replace_tags($library_template, '{APP_CLASS_TITLE}', ucwords($this->app_class));
			file_put_contents($this->install_path.'libraries/'.ucwords($this->app_class).'_library.php', $library_data);
		}

		// OAuth Provider
		if ($oauth != 'FALSE')
		{
			$library_template	= $this->template_path.'libraries/'.$oauth.'_provider.php';
			$library_data 		= $this->replace_tags($library_template, '{APP_CLASS_TITLE}', ucwords($this->app_class));
			file_put_contents($this->install_path.'libraries/'.$oauth.'_provider.php', $library_data);
		}

		return TRUE;
	}
	
	/**
	 * Create Model
	 * @return bool Always returns true
	 * @todo Make this return more meaningful data
	 * @todo document properly
	 */
	function create_model($model)
	{
		if ($model == 'TRUE')
		{
			make_folder($this->install_path.'models/');

			// Make Model
			$model_template	= file_get_contents($this->template_path.'models/data_model.php', FILE_USE_INCLUDE_PATH);
			file_put_contents($this->install_path.'models/data_model.php', $model_template);
		}

		return TRUE;
	}
	
	/**
	 * Create Widgets
	 * @return bool Always returns true
	 * @todo Make this return more meaningful data
	 * @todo document properly
	 */
	function create_widgets($widgets)
	{
		if ($widgets == 'TRUE')
		{
			// Widgets Config
			$config_template	= $this->template_path.'config/widgets.php';
			$config_data		= $this->replace_tags($config_template);
			file_put_contents($this->install_path.'config/widgets.php', $config_data);

			// Widget Template
			make_folder($this->install_path.'views/widgets/');
			$widget_template	= $this->template_path.'views/widgets/recent_data.php';
			$widget_data		= $this->replace_tags($widget_template);
			file_put_contents($this->install_path.'views/widgets/recent_data.php', $widget_data);
		}

		return TRUE;
	}

}

// EOF App_tools.php