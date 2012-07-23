<?php
/**
* Social Igniter Helper
*
* @package		Social Igniter
* @subpackage	Social Igniter Helper
* @author		Brennan Novak
* @link			http://social-igniter.com
*
*/

function convert_comment_settings($value)
{
	if ($value == 'TRUE')
	{
		return 'Y';
	}
	else
	{
		return 'N';
	}
}

function connection_has_auth($connection)
{
	if (!$connection) return FALSE;

	if (($connection->auth_one) && ($connection->auth_two))
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

function login_redirect($redirect=FALSE)
{
    $ci =& get_instance();    
	
	if ($redirect)
	{
		$redirect = base_url().$redirect;
	}
	elseif ($ci->config->item('home_view_redirect') != '' AND $ci->config->item('home_view_redirect') != 'home')
	{
		$redirect = base_url().config_item('home_view_redirect');
	}
	else
	{
		$redirect = base_url().'home';
	}
	
	return $redirect;
}

function connections_redirect($redirect=FALSE)
{
    $ci =& get_instance();    
	
	if ($redirect)
	{
		$redirect = base_url().$redirect;
	}
	else
	{
		$redirect = base_url().'settings/connections';
	}
	
	return $redirect;
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

function check_app_installed($app_url)
{	
    $ci =& get_instance();    
	
	if ((check_app_exists($app_url)) AND ($ci->config->item($app_url.'_enabled') == 'ENABLED'))
	{
		return TRUE;
	}

	return FALSE;
}

