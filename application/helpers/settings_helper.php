<?php
/**
* Settings Helper
*
* @package		Social Igniter
* @subpackage	Settings Helper
* @author		Brennan Novak
* @link			http://social-igniter.com
*
* Converts various settings that need converting
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