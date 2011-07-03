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
		
		// Load Models
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
	
	// Installs app data into the 'settings' table
	function install_settings($app)
	{
		if (config_item($app.'_settings'))
		{
			$current_settings 	= $this->ci->social_igniter->get_settings_module($app);
			$config_settings	= config_item($app.'_settings');
			$add_settings		= array();
			$current_count		= count($current_settings);
			$config_count		= count(config_item($app.'_settings'));
		
			// Clean Current
			if ($this->uri->segment(3) == 'reinstall')
			{				
				foreach ($current_settings as $setting)
				{
					$this->ci->social_igniter->delete_setting($setting->settings_id);
				}
			
				$current_count = 0;			
			}		
			
			// Maybe Install or Update
			if ($current_count != $config_count)
			{
				foreach ($config_settings as $key => $setting)
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
				
				// Properly Handled
				$now_settings = count($add_settings) + $current_count;
				
				if ($now_settings == $config_count)
				{
					$message = array('status' => 'success', 'message' => 'Settings have been added');
				}
				else
				{
					$message = array('status' => 'error', 'message' => 'Shucks the settings were not properly added');				
				}
			}
			else
			{
				$message = array('status' => 'error', 'message' => 'Settings are currently up to date');			
			}			
		}
		else
		{
			$message = array('status' => 'error', 'message' => 'There are no settings to install');
		}	
	
		return $message;
	}
	
	// Installs app data into the 'sites' table
	function install_site()
	{
		
	}

	function create_folders()
	{
	
	}
	
}