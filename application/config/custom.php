<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:			Social Igniter : Custom : Config
* Author: 		Brennan Novak
* 		  		contact@social-igniter.com
*          
*
* Project:		http://social-igniter.com/
* Source: 		http://github.com/socialigniter/
*
* Description: 	For custom config settings that don't get committed back to core 
*/
$config['regions'] = array(
	'earth'		=> 'Earth',
	'portland'	=> 'Portland',
	'bay-area'	=> 'Bay Area'
);

$config['category_date_sort'] = array(
	'week',
	'season'
);

$config['season_date_ranges'] = array(
	'winter'	=> '12-01 00:00:00',
	'spring'	=> '03-01 00:00:00',
	'summer'	=> '06-01 00:00:00',
	'fall'		=> '09-01 00:00:00'
);

$config['age_ranges'] = array(
	'4-5' 	=> '4-5',
	'6'		=> '6',
	'7-8'	=> '7-8',
	'9-10'	=> '9-10',
	'9-13'	=> '9-13',
	'10-14'	=> '10-14'
);

$config['time_sessions'] = array(
	'morning'	=> 'Morning',
	'afternoon'	=> 'Afternoon'
);

$config['trackers_guilds'] = array(
	'artisans',
	'rangers',
	'wilders',
	'mariners'
);