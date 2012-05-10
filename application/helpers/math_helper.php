<?php
/**
* View Helper
*
* @package		Social Igniter
* @subpackage	Math Helper
* @author		Brennan Novak
* @link			http://social-igniter.com
*
* @access	public
* @param	string
* @return	string
*
* Has some basic math helpers tools 
*/

function percent($num_amount, $num_total, $format=0)
{
	$count1 = $num_amount / $num_total;
	$count2 = $count1 * 100;
	$count3	= number_format($count2, $format);
	return $count3;
}