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