<?php
/**
* MY Text Helper
*
* @package		Social Igniter
* @subpackage	Form Helper
* @author		Brennan Novak
* @link			http://social-igniter.com
*
* @access	public
* @param	string
* @return	boolean
*
* Offers more file & folder options than the normal File Helper
*/

function form_submit_publish($publish, $save)
{
    if ($publish == "Publish")
    {
    	$status = "P"; 
    }
    elseif ($save == "Save Draft") 	
    {
    	$status = "S";		
	}
	else
	{
		$status = "S";	        
	}

	return $status;
}


function form_title_url($title, $title_url, $existing_url=NULL)
{
	if (($title_url != '') && ($title_url != $existing_url))
	{
		return $title_url;
	}
	else
	{
		return url_username($title, 'dash', TRUE);
	}
}


function form_content_viewed($site_id)
{
	if ($site_id == config_item('site_id'))
	{
		return 'Y';
	}
	else
	{
		return 'N';
	}
}