<?php
/**
* MY_Input
*
* Allows CodeIgniter to be passed Query Strings
* NOTE! You must add the question mark '?' to the allowed URI chars, and set the URI protocol to PATH_INFO
* NOTE2: It doesn't appear to be necessary to add the question mark
*
* @package Flame
* @subpackage Hacks
* @copyright 2009, Jamie Rumbelow
* @author Jamie Rumbelow <http://www.jamierumbelow.net>
* @license GPLv3
* @version 1.0.0
*/
class MY_Input extends CI_Input 
{
	function __construct()
	{
        parent::__construct();  
    }

	function _sanitize_globals()
	{
		$this->_allow_get_array = TRUE;
		parent::_sanitize_globals();
	} 	
}