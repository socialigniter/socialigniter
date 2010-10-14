<?php
/**
* View Helper
*
* @package		Social Igniter
* @subpackage	Url Helper
* @author		Brennan Novak
* @link			http://brennannovak.com
*
* @access	public
* @param	string
* @return	string
*
* Extends url_title by allowing adding the seperator type 'none' which removes up to 2 spaces, 4 dahses, and 4 underscores 
*/
function url_username($str, $separator = 'dash', $lowercase = FALSE)
{

	if ($separator == 'dash')
	{
		$search		= '_';
		$replace	= '-';
	}
	else
	{
		$search		= '-';
		$replace	= '_';
	}

	$trans = array(
					'&\#\d+?;'				=> '',
					'&\S+?;'				=> '',
					'\s+'					=> $replace,
					'[^a-z0-9\-\_]'		=> '',
					$replace.'+'			=> $replace,
					$replace.'$'			=> $replace,
					'^'.$replace			=> $replace,
					'\+$'					=> ''
				  );

	$str = strip_tags($str);

	foreach ($trans as $key => $val)
	{
		$str = preg_replace("#".$key."#i", $val, $str);
	}

	if ($lowercase === TRUE)
	{
		$str = strtolower($str);
	}	

	if ($separator == 'none')
	{
		$search = array(' ','  ','-','--','----','_','_','____'); 
		$replace = array('','','','','','','','','',''); 
		$str = str_replace($search, $replace, $str); 
	}
	
	return trim(stripslashes($str));
		
}
