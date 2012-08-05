<?php
class Install extends Oauth_Controller
{
    function __construct() 
    {
        parent::__construct();

    	$this->load->library('installer');
    }
	
	function install_authd_get()
	{
		$this->installer->download();

		$message = array('status' => 'success', 'message' => 'great now <a href="'.base_url().'install/uncompress/'.$name.'">uncompress that sucker</a>');		

		$this->response($message, 200);
	}

	function custom_authd_get()
	{
		$this->installer->download_custom();
		
		$message = array('status' => 'success', 'message' => 'great now <a href="'.base_url().'install/uncompress/'.$name.'">uncompress that sucker</a>');
		
		$this->response($message, 200);
	}
	
	function uncompress_authd_get()
	{
		$this->installer->uncompress_app($app);

		$message = array('status' => 'success', 'message' => 'App uncompressed', 'data' => $extract);

		$this->response($message, 200);
	}

	function uninstall_authd_get()
	{	
		$settings	= $this->installer->uninstall_settings($this->get('app'));
		$files		= $this->installer->delete_app($this->get('app'));
	
		if ($settings == true AND $files == true)
		{		
            $message = array('status' => 'success', 'message' => 'The '.$this->get('app').' App was unistalled', 'data' => array($settings, $files));
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'Dang, the '.$this->get('app').' App could not be uninstalled', 'data' => array($settings, $files));
        }		
		
		$this->response($message, 200);	
	}
		
	function create_app_authd_post()
	{
		// Config
		$app_data = array(
			'app_name'		=> $this->input->post('app_name'),		
			'app_url'		=> $this->input->post('app_url'),
			'app_class'		=> $this->input->post('app_class')
		);

		$this->load->library('app_tools', $app_data);

		// Check If App Name Exists
		if (!check_app_exists($this->input->post('app_url')))
		{
			// Template
			$this->app_tools->create_app_template();

			// Configs
			$this->app_tools->create_app_configs();
			$this->app_tools->create_app_install($this->input->post('app_connections'), $this->input->post('app_database'), $this->input->post('app_widgets'));

			// Controllers
			$this->app_tools->create_app_controllers($this->input->post('app_database'), $this->input->post('app_api_methods'), $this->input->post('app_connections'));

			// Views
			$this->app_tools->create_app_views();

			// Helper
			$this->app_tools->create_helper($this->input->post('app_helper'));

			// Libraries
			$this->app_tools->create_libraries($this->input->post('app_library'), $this->input->post('app_oauth_provider'));

			// Model
			$this->app_tools->create_model($this->input->post('app_model'));

			// Widgets
			$this->app_tools->create_widgets($this->input->post('app_widgets'));

			$message = array('status' => 'success', 'message' => 'Yay, your App '.$this->input->post('app_name').' was created from the template');
		}
		else
		{
            $message = array('status' => 'error', 'message' => 'Dang, there is already an App named '.$this->input->post('app_name').' installed');			
		}

		$this->response($message, 200);
	}
	
	function migrate_current_authd_get()
	{
		$this->load->library('migration');

		// Update to current (as specified in /application/config/migration.php)
		if (!$this->migration->current())
		{
            $message = array('status' => 'error', 'message' => show_error($this->migration->error_string()));			
		}
		else
		{
			$message = array('status' => 'success', 'message' => 'Yay, your was database was sucessfully updated');
		}

		$this->response($message, 200);
	}

	function migrate_latest_authd_get()
	{
		$this->load->library('migration');

		// Update to latest file in /application/migrations/00X_migration.php
		if (!$this->migration->latest())
		{
            $message = array('status' => 'error', 'message' => show_error($this->migration->error_string()));			
		}
		else
		{
			$message = array('status' => 'success', 'message' => 'Yay, your was database was sucessfully updated');
		}

		$this->response($message, 200);
	}

}