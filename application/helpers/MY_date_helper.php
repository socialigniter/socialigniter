<?php
/**
 * Extended Date Helper
 *
 * @package		Extended Date Helper
 * @subpackage	Helper
 * @author		Brennan Novak
 * @link		http://brennannovak.com
 *
 * @access	public
 * @param	string
 * @return	string
 *
 * Takes an inputed date and converts it something more human and pretty
 *
 * Added SIMPLE, SIMPLE_TIME, and FANCY format options
 */

if ( ! function_exists('standard_date'))
{
	function standard_date($fmt = 'DATE_RFC822', $time = '')
	{
		$formats = array(
						'DATE_ATOM'		=>	'%Y-%m-%dT%H:%i:%s%Q',
						'DATE_COOKIE'	=>	'%l, %d-%M-%y %H:%i:%s UTC',
						'DATE_ISO8601'	=>	'%Y-%m-%dT%H:%i:%s%O',
						'DATE_RFC822'	=>	'%D, %d %M %y %H:%i:%s %O',
						'DATE_RFC850'	=>	'%l, %d-%M-%y %H:%m:%i UTC',
						'DATE_RFC1036'	=>	'%D, %d %M %y %H:%i:%s %O',
						'DATE_RFC1123'	=>	'%D, %d %M %Y %H:%i:%s %O',
						'DATE_RSS'		=>	'%D, %d %M %Y %H:%i:%s %O',
						'DATE_W3C'		=>	'%Y-%m-%dT%H:%i:%s%Q',
						);

		if ( ! isset($formats[$fmt]))
		{
			return FALSE;
		}
	
		return mdate($formats[$fmt], $time);
	}
}

function human_date($fmt = 'DIGITS', $time = '')
{
	$formats = array(
		'TIME'					=> '%g:%i %A',
		'DIGITS'				=> '%n / %j / %y',
		'DIGITS_ZERO'			=> '%m / %d / %Y',
		'SLASHES'				=> '%M / %j / %Y',
		'SIMPLE'				=> '%M %j%S, %Y',
		'SIMPLE_TIME'			=> '%M %j%S, %Y %g:%i %A',
		'SIMPLE_ABBR'			=> '%M %j%S, %Y',
		'SIMPLE_TIME_ABBR'		=> '%M %j%S, %Y %g:%i %A',
		'MONTH_DAY'				=> '%F %j%S',
		'MONTH_DAY_YEAR'		=> '%F %j%S, %Y',
		'MONTH_DAY_YEAR_TIME'	=> '%F %j%S, %Y %g:%i %A'
	);

	if ( ! isset($formats[$fmt]))
	{
		return FALSE;
	}

	return mdate($formats[$fmt], $time);
}

function human_time($fmt='HOUR_MINUTE', $time = '')
{
	$formats = array(
		'HOUR_MINUTE'	=>  '%g:%i %A',
		'HOUR'			=>  '%g %A',
		'MINUTE'		=>  '%i'
	);

	if ( ! isset($formats[$fmt]))
	{
		return FALSE;
	}

	return mdate($formats[$fmt], $time);
}


function elapsed_date($seconds='', $time='')
{
    if(!is_numeric($seconds) || empty($seconds)) return true;
    
    $CI =& get_instance();
    $CI->lang->load('date');    
    
    if(!is_numeric($time)) $time = date('U');
    
    $difference = abs($time-$seconds);
    $periods 	= array('date_second', 'date_minute', 'date_hour', 'date_day', 'date_week', 'date_month', 'date_year');
    $lengths 	= array('60','60','24','7','4.35','12','10');
    
    for($j=0; $difference >= $lengths[$j]; $j++)
    {
        if($j==count($lengths)-1) break;
        $difference /= $lengths[$j];
    }
    
    $difference = round($difference);
    
    if($difference == 0 && $j==0) $difference = 1;
    if($difference != 1) $periods[$j].= 's';
    
    return $difference.' '.strtolower($CI->lang->line($periods[$j]));
}

function format_datetime($format, $date_time)
{
	$date_time = mysql_to_unix($date_time);

	if ($format == 'ELAPSED')
	{
		$return = elapsed_date($date_time).' ago';
	}
	else
	{
		$return = human_date($format, $date_time);
	}
	
	return $return;
}


function format_time($format, $time)
{
	$time	= mysql_to_unix('0000-00-00 '.$time);
	$return = human_time($format, $time);
	
	return $return;
}

function unix_to_mysql($time = '')
{
	$mysql = '%Y-%m-%d %H:%i:%s';	
	return mdate($mysql, $time);
}

function friendly_to_mysql_date($date = '')
{
	$parts	= explode('/', $date);	
	$date	= $parts[2].'-'.$parts[0].'-'.$parts[1];
	return $date;
}

function friendly_to_mysql_time($time = '')
{
	$parts		= explode(':', $time);
	$hours		= sprintf('%02d', $parts[0]);
	$minutes	= $parts[1];

	$check_pm = preg_match('/(P|p)(M|m)/', $time);
	
	if ($check_pm)
	{
		$hours = $hours + 12;
	}

	$clean_minutes	= preg_replace(array('( )', '/(P|p)(M|m)/', '/(A|a)(M|m)/'), '', $minutes);
	$minutes		= sprintf('%02d', $clean_minutes);	
		
	return $hours.':'.$minutes.':00';
}

// RETURNS DATE VALUES AS INVIDUAL ELEMENTS OF MySQL DATE
function date_parser($fmt = 'WHOLE', $time = '')
{
	$formats = array(
					'WHOLE'		=>  '%M %d, %Y %g:%i %A',
					'YEAR'		=>  '%Y',
					'MONTH'		=>  '%m',
					'DAY'		=>  '%d'
					);

	if ( ! isset($formats[$fmt]))
	{
		return FALSE;
	}

	return mdate($formats[$fmt], $time);
}

function current_week()
{
	$time	= now();
	$mysql  = '%W';	
	return mdate($mysql, $time);
}

function ordinal_list($value)
{
	return date("j", mktime(0, 0, 0, 0, $value, 0)).date("S", mktime(0, 0, 0, 0, $value, 0));
}

