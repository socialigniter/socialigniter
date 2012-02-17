<?php
/**
 * Extended Text Helper
 *
 * @package		Extended Text Helper
 * @subpackage	Helper
 * @author		Brennan Novak
 * @link		http://social-igniter.com
 *
 */

if ( ! function_exists('real_character_limiter'))
{
	function real_character_limiter($str, $n = 500, $end_char = '&#8230;')
	{
		if (strlen($str) < $n)
		{
			return $str;
		}

		$str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

		if (strlen($str) <= $n)
		{
			return $str;
		}

		$out = substr($str, 0, $n);
		return $out.$end_char;
	}
 }