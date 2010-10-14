<?php
/**
* View Helper
*
* @package		Social Igniter
* @subpackage	File Helper
* @author		Brennan Novak
* @link			http://brennannovak.com
*
* @access	public
* @param	string
* @return	boolean
*
* Offers more file & folder options than the normal File Helper
*/
function make_folder($folder_name)
{

	if(!is_dir($folder_name))
	{

		mkdir($folder_name, 0777);
		chmod($folder_name, 0777);
	
	}
	
}