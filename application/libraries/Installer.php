<?php  if  ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
Installer Library

@package		Social Igniter
@subpackage		Installer Library
@author			Brennan Novak
@link			http://social-igniter.com

This class contains all the basic install functions for core and app installs
*/
 
class Installer
{
	protected $ci;

	function __construct()
	{
		$this->ci =& get_instance();
		
		// Load Things
  		$this->ci->load->helper('file');		
		$this->ci->load->model('settings_model');
		$this->ci->load->model('sites_model');
	}	
	
	// Downloads app from Github repo
	function download_github($app_owner, $app_name)
	{
		$repo_url	= 'https://github.com/'.$app_owner.'/'.$app_name.'/zipball/master';
	    $path		= config_item('uploads_folder').'apps/'.$app_name.'.zip';
		$fp   		= fopen($path, 'w');

		// Get from Github requires PHP settings just right. Figure out better method later
		if ((ini_get('open_basedir') == '') && (ini_get('safe_mode') == 'Off' || !ini_get('safe_mode')))
		{
			$options = array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_SSL_VERIFYHOST => 1,
				CURLOPT_FOLLOWLOCATION => 1,
			);
		
			$ch = curl_init($repo_url);
			curl_setopt_array($ch, $options);
			$output 	= curl_exec($ch);
			$download	= curl_getinfo($ch);
	   		curl_close($ch);
	    	fclose($fp);
	
			file_put_contents(config_item('uploads_folder').'apps/'.$app_name.'.zip', $output);				
			
			$message = array('status' => 'success', 'message' => 'Yay Github repo was downloaded');
		}
		else
		{
			$message = array('status' => 'error', 'message' => 'Sorry, your server does support downloading from Github');
		}
		
		return $message;	
	}
	
	// Downloads app from custom URL
	function download_custom($app_name, $app_url)
	{
	    $path	= config_item('uploads_folder').'apps/'.$app_name.'.zip';
		$fp   	= fopen($path, 'w');

		// Get from Github requires PHP settings just right. Figure out better method later
		if ((ini_get('open_basedir') == '') && (ini_get('safe_mode') == 'Off' || !ini_get('safe_mode')))
		{
			$options = array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_SSL_VERIFYHOST => 1,
				CURLOPT_FOLLOWLOCATION => 1,
			);
		
			$ch = curl_init($app_url);
			curl_setopt_array($ch, $options);
			$output 	= curl_exec($ch);
			$download	= curl_getinfo($ch);
	
			file_put_contents(config_item('uploads_folder').'apps/'.$app_name.'.zip', $output);				
			
			$message = 'yay downloaded wit cool curl';
		}
		else
		{
			$options = array(
				CURLOPT_FILE => $fp
			);		
		
			$message ='downloaded with lame curl';
		}	
		 
		// Do CURL, get file
	    $ch = curl_init($app_url);
		curl_setopt_array($ch, $options);
	    $data = curl_exec($ch);	 
	    curl_close($ch);
	    fclose($fp);
	    
	    return $message;
	}
	
	function uncompress_app($app)
	{
		$this->ci->load->library('unzip');

		$save_file		= APPPATH.'modules/'.$app;		
		$extract		= $this->unzip->extract('./uploads/apps/'.$app.'.zip', APPPATH.'modules');
		$single_file	= explode("/", $extract[0]);

		rename(APPPATH.'modules/'.$single_file[2], $save_file);
		recursive_chmod($save_file, 0644, 0755);

		return $extract;
	}
	
	
	// Deletes App Files from /application/modules
	function delete_app($app)
	{
		delete_files(APPPATH.'modules/'.$app);
	
		return TRUE;
	}
		
	
	function create_folders($app_folders)
	{	
		foreach ($app_folders as $folder)
		{
			make_folder(config_item('uploads_folder').$folder);
		}
		
		return TRUE;
	}	
	

	// Installs data into 'content' table
	function install_content()
	{
	
	
	}
	
	
	// Installs app data into the 'settings' table
	function install_settings($app, $app_settings, $reinstall=FALSE)
	{
		$current_settings 	= $this->ci->social_igniter->get_settings_module($app);
		$add_settings		= array();
		$current_count		= count($current_settings);
		$config_count		= count($app_settings);
	
		// Delete Current
		if ($reinstall)
		{				
			foreach ($current_settings as $setting)
			{
				$this->ci->social_igniter->delete_setting($setting->settings_id);
			}
		
			$current_count = 0;			
		}		
		
		// Install Settings
		foreach ($app_settings as $key => $setting)
		{
			$setting_data = array(
				'site_id'	=> config_item('site_id'),
				'module'	=> $app,
				'setting'	=> $key,
				'value'		=> $setting
			);
			
			if (!$this->ci->social_igniter->check_setting_exists($setting_data))
			{
				$add_settings[] = $this->ci->social_igniter->add_setting($setting_data);
			}
		}
		
		// Properly Added
		$now_settings = count($add_settings) + $current_count;
		
		if ($now_settings == $config_count)
		{
			$result = TRUE;
		}
		else
		{
			$result = FALSE;				
		}

		return $result;
	}
	
	function uninstall_settings($app)
	{
		$current_settings	= $this->ci->social_igniter->get_settings_module($app);
		$delete_count		= array();
					
		foreach ($current_settings as $setting)
		{
			$delete_count[$setting->settings_id] = $this->ci->social_igniter->delete_setting($setting->settings_id);
		}
				
		if (count($current_settings) == count($delete_count))
		{
			return array($current_settings, $delete_count);	
		}
		else
		{
			return array($current_settings, $delete_count);
		}
	}
	
	// Installs app data into the 'sites' table
	function install_sites($app_sites)
	{
		foreach ($app_sites as $site)
		{
			$this->ci->social_igniter->add_site($site);	
		}
		
		return TRUE;
	}

	
}