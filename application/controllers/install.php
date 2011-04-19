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
		$url	= 'https://github.com/socialigniter/'.$name.'/zipball/master';
		
		$options = array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_SSL_VERIFYHOST => 1,
			CURLOPT_FOLLOWLOCATION => 1,
		);
		
		$ch = curl_init($url);
		curl_setopt_array($ch, $options);
		$output = curl_exec($ch);
		file_put_contents('./uploads/apps/'.$name.'.zip', $output);
		
		echo 'great now <a href="'.base_url().'install/uncompress/'.$name.'">uncompress that sucker</a>';
	
	}
	
	function uncompress()
	{
		$this->load->library('unzip');

		$name = $this->uri->segment(3);	
		$extract		= $this->unzip->extract('./uploads/apps/'.$name.'.zip', APPPATH.'modules');
		$single_file	= explode("/", $extract[0]);

		rename(APPPATH.'modules/'.$single_file[2], APPPATH.'modules/'.$name);		
	}

}