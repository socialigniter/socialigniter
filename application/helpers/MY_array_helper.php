<?php
/**
 * Extended Array Helper
 *
 * @package		Extended Array Helper
 * @subpackage	Helper
 * @author		Brennan Novak
 * @link		http://brennannovak.com
 *
 * @access	public
 * @param	string
 * @return	string
 *
 */
 
function element_remove($element, $array)
{
	$key			= array_search($element, $array);
	$array[$key]	= NULL;
	$array			= array_filter($array); 
 
	return $array;
}