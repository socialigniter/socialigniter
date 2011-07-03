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

		echo 'great now <a href="'.base_url().'install/uncompress/'.$name.'">uncompress that sucker</a>';		
	}

	function custom_get()
	{
		$this->installer->download_custom();
		
		echo $message;
		echo 'great now <a href="'.base_url().'install/uncompress/'.$name.'">uncompress that sucker</a>';
	}
	
	function uncompress_get()
	{
		$this->load->library('unzip');

		$name 			= $this->uri->segment(3);
		$save_file		= APPPATH.'modules/'.$name;		
		$extract		= $this->unzip->extract('./uploads/apps/'.$name.'.zip', APPPATH.'modules');
		$single_file	= explode("/", $extract[0]);

		rename(APPPATH.'modules/'.$single_file[2], $save_file);

		print_r($extract);

		recursive_chmod($save_file, 0644, 0755);
	}

}