<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:			Social Igniter : Settings : Config
* Author: 		Brennan Novak
* 		  		contact@social-igniter.com
*         		@brennannovak
*          
* Created: 		Brennan Novak
*
* Project:		http://social-igniter.com/
* Source: 		http://github.com/socialigniter/
*
* Description: 	settings file for site settings printed out to form fields to be 
* as KEY => VALUE pairs in 'settings' table.
*/
/* Settings Sections */
$config['email_protocol'] = array(
	'smtp'		=> 'SMTP',
	'sendmail'	=> 'Sendmail',
	'mail'		=> 'PHP Mail'
);

$config['yes_or_no'] = array(
	'TRUE'	=> 'Yes',
	'FALSE'	=> 'No'
);

$config['enable_disable'] = array(
	'TRUE'	=> 'Enabled',
	'FALSE'	=> 'Disabled'
);

$config['date_style_types'] = array(
	'DIGITS' 				=> '1 / 1 / 10',
	'DIGITS_ZERO' 			=> '01 / 01 / 2010',
	'SLASHES' 				=> 'Jan / 1 / 2010',
	'SIMPLE'				=> 'Jan 1, 2010',
	'SIMPLE_TIME' 			=> 'Jan 1, 2010 1:00 AM',
	'SIMPLE_ABBR' 			=> 'Jan 1st, 2010',
	'SIMPLE_TIME_ABBR'		=> 'Jan 1st, 2010 1:00 AM',
	'MONTH_DAY' 	 		=> 'January 1st',
	'MONTH_DAY_YEAR' 		=> 'January 1st, 2010',
	'MONTH_DAY_YEAR_TIME' 	=> 'January 1st, 2010 1:00 AM',
	'ELAPSED'				=> '2 hours ago'
);

$config['time_style_types'] = array(
	'HOUR_MINUTE'	=> '2:15 PM',
	'HOUR' 			=> '2 PM',
	'MINUTE'		=> '15'
);

$config['days_of_week']	= array(
	'sunday'		=> 'Sunday',
	'monday'		=> 'Monday',
	'tuesday'		=> 'Tuesday',
	'wednesday'		=> 'Wednesday',
	'thursday'		=> 'Thursday',
	'friday'		=> 'Friday',
	'saturday'		=> 'Saturday'
);

$config['numbers_one_five']	= array(
	'1'		=> '1',
	'2'		=> '2',
	'3'		=> '3',
	'4'		=> '4',
	'5'		=> '5'
);
	
$config['amount_increments_all'] = array(
	'1'		=> '1',
	'2'		=> '2',
	'3'		=> '3',
	'4'		=> '4',
	'5'		=> '5',
	'6' 	=> '6',
	'7'		=> '7',
	'8' 	=> '8',
	'9' 	=> '9',
	'10'	=> '10',
	'15'	=> '15',
	'20'	=> '20',
	'25'	=> '25',
	'all'	=> 'All',
);

$config['amount_increments_five'] = array(
	'5' 	=> '5',
	'10' 	=> '10',
	'15'	=> '15',
	'20' 	=> '20',
	'25'	=> '25',
	'all'	=> 'All'
);

/* Misc Form Options */
$config['access'] = array(
	'E' => 'Everyone',
	'F' => 'Friends',
	'M' => 'Only Me'
);

$config['comments_allow'] = array(
	'Y'	=> 'Yes',
	'N'	=> 'No',
	'A' => 'Require Approval'
);

/* Tools */
$config['ratings_type']	= array(
	'thumbs' 		=> 'Thumbs Up / Down',
	'one_to_five'	=> 'One to Five'
);


/* Bit.ly Settings */
$config['bitly_domain'] = array(
	'bit.ly'	=> 'http://bit.ly',
	'j.mp'		=> 'http://j.mp'
);

/* Actions for Link */
$config['ratings_type']	= array(
	'thumbs' 		=> 'Thumbs Up / Down',
	'one_to_five'	=> 'One to Five'
);