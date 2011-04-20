<?php
class Install extends Dashboard_Controller
{ 
    function __construct() 
    {
        parent::__construct();
        
        $this->data['page_title'] = 'Settings';
    }
	
	function download()
	{	
		$name	= $this->uri->segment(3);
	    $path	= config_item('uploads_folder').'apps/'.$name.'.zip';
		$fp   	= fopen($path, 'w');

		// Get from Github requires PHP settings just right
		// Figure out better method later
		if ((ini_get('open_basedir') == '') && (ini_get('safe_mode') == 'Off' || !ini_get('safe_mode')))
		{
			$url 	 = 'https://github.com/socialigniter/'.$name.'/zipball/master';
			$options = array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_SSL_VERIFYHOST => 1,
				CURLOPT_FOLLOWLOCATION => 1,
			);
		
			$ch = curl_init($url);
			curl_setopt_array($ch, $options);
			$output 	= curl_exec($ch);
			$download	= curl_getinfo($ch);
	
			file_put_contents(config_item('uploads_folder').'apps/'.$name.'.zip', $output);				
			
			$message = 'yay downloaded wit cool curl<br>';
		}
		else
		{
		    $url  = 'http://social-igniter.com/uploads/apps/'.$name.'.zip';

			$options = array(
				CURLOPT_FILE => $fp
			);		
		
			$message ='downloaded with lame curl<br>';
		}	
		 
		// Do CURL, get file
	    $ch = curl_init($url);
		curl_setopt_array($ch, $options);
	    $data = curl_exec($ch);	 
	    curl_close($ch);
	    fclose($fp);
		
		echo $message;		
		echo 'great now <a href="'.base_url().'install/uncompress/'.$name.'">uncompress that sucker</a>';		
	}
	
	function uncompress()
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