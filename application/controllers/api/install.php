<?php
class Install extends Oauth_Controller
{
    function __construct() 
    {
        parent::__construct();

    	$this->load->library('installer');
    }
	
	function download_get()
	{
		$this->installer->download();

		$message = array('status' => 'success', 'message' => 'great now <a href="'.base_url().'install/uncompress/'.$name.'">uncompress that sucker</a>');		

		$this->response($message, 200);
	}

	function custom_get()
	{
		$this->installer->download_custom();
		
		$message = array('status' => 'success', 'message' => 'great now <a href="'.base_url().'install/uncompress/'.$name.'">uncompress that sucker</a>');
		
		$this->response($message, 200);
	}
	
	function uncompress_get()
	{
		$this->load->library('unzip');

		$name 			= $this->uri->segment(3);
		$save_file		= APPPATH.'modules/'.$name;		
		$extract		= $this->unzip->extract('./uploads/apps/'.$name.'.zip', APPPATH.'modules');
		$single_file	= explode("/", $extract[0]);

		rename(APPPATH.'modules/'.$single_file[2], $save_file);
		recursive_chmod($save_file, 0644, 0755);

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

}