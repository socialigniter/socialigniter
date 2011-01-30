<?php
/**
* Custom Helper
*
* @package		Social Igniter
* @subpackage	Feed Helper
* @link			http://social-igniter.com
*
* Description: 	For custom helpers for your application that don't get committed back to core 
*/
function site_region_link($regions, $url_1, $url_2)
{
	foreach ($regions as $region)
	{
		if ($region == $url_1)
		{
			$url = $url_1;
			break;
		}
		elseif ($region == $url_2)
		{
			$url = $url_2;
			break;
		}
		else
		{
			$url = '';
		}
	}
	
	return $url;
}

// Returns Region for style sheets, images, etc...
function site_region_style($regions, $url_1, $url_2)
{
	foreach ($regions as $region)
	{
		if ($region == $url_1)
		{
			$style = $url_1;
			break;
		}
		elseif ($region == $url_2)
		{
			$style = $url_2;
			break;
		}
		else
		{
			$style = 'earth';
		}
	}
	
	return $style;
}


function make_week_period($time, $increment)
{

	$date 		= date_parser('WEEK', mysql_to_unix($time));
	$date 		= $date - $increment;
	
	return $date;
	
}


function display_age_range($age_range)
{
	$result = NULL;

	if ($age_range != '')
	{
		$result = '<b>Ages '.$age_range.'</b>';
	}
	
	return $result;
}

function display_product_date($date_start, $date_end, $time=NULL)
{

	$date  		= mdate('%M %j%S, %Y', mysql_to_unix($date_start));
	$time_start	= mdate('%g:%i %A', mysql_to_unix($date_start));
	$time_end	= mdate('%g:%i %A', mysql_to_unix($date_end));
	
	$result	= $date.' '.$time.' '.$time_start.' - '.$time_end;
	
	return $result;
}

function display_product_address($location=NULL)
{
	if ($location)
	{
		$location = $location->address.', '.$location->city.', '.$location->state;
	}
	
	return $location;
}