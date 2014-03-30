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
	'DIGITS' 						=> '1 / 1 / 10',
	'DIGITS_ZEROS' 					=> '01 / 01 / 2010',
	'SLASHES' 						=> 'Jan / 1 / 2010',
	'MONTH_DAY_ABBR'				=> 'Jan 1',
	'MONTH_DAY_YEAR_ABBR'			=> 'Jan 1, 2010',
	'MONTH_DAY_TIME_ABBR' 			=> 'Jan 1, 1:00 pm',
	'MONTH_DAY_YEAR_TIME_ABBR' 		=> 'Jan 1, 2010 1:00 pm',
	'MONTH_DAY_SUFF_ABBR'			=> 'Jan 1st',
	'MONTH_DAY_YEAR_SUFF_ABBR'		=> 'Jan 1st, 2010',
	'MONTH_DAY_TIME_SUFF_ABBR'		=> 'Jan 1st, 1:00 pm',
	'MONTH_DAY_YEAR_TIME_SUFF_ABBR'	=> 'Jan 1st, 2010 1:00 pm',
	'MONTH_DAY' 		 			=> 'January 1',
	'MONTH_DAY_YEAR' 				=> 'January 1, 2010',
	'MONTH_DAY_TIME'				=> 'January 1, 1:00 pm',
	'MONTH_DAY_YEAR_TIME'			=> 'January 1, 2010 1:00 pm',
	'MONTH_DAY_SUFF' 	 			=> 'January 1st',
	'MONTH_DAY_YEAR_SUFF' 			=> 'January 1st, 2010',
	'MONTH_DAY_TIME_SUFF'			=> 'January 1st, 1:00 pm',
	'MONTH_DAY_YEAR_TIME_SUFF'		=> 'January 1st, 2010 1:00 pm',
	'ELAPSED'						=> '2 hours ago'
);

$config['time_style_types'] = array(
	'HOUR_MINUTE'	=> '2:15 pm',
	'HOUR' 			=> '2 pm',
	'MINUTE'		=> '15 mins'
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

$config['numbers_one_ten'] = array(
	'1'		=> '1',
	'2'		=> '2',
	'3'		=> '3',
	'4'		=> '4',
	'5'		=> '5',
	'6'		=> '6',
	'7'		=> '7',
	'8'		=> '8',
	'9'		=> '9',
	'10'	=> '10'
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

$config['time_increments_hours_twelve'] = array(
	'01'	=> '1',
	'02' 	=> '2',
	'03' 	=> '3',
	'04'	=> '4',
	'05'	=> '5',
	'06'	=> '6',
	'07'	=> '7',
	'08'	=> '8',
	'09'	=> '9',
	'10'	=> '10',
	'11'	=> '11',
	'12'	=> '12'
);

$config['time_increments_minutes_fifteen'] = array(
	'00'	=> '00',
	'15' 	=> '15',
	'30' 	=> '30',
	'45'	=> '45'
);

$config['time_increments_minutes_five'] = array(
	'00'	=> '00',
	'05'	=> '05',
	'10' 	=> '10',
	'15' 	=> '15',
	'25' 	=> '25',
	'30' 	=> '30',
	'35' 	=> '35',
	'40' 	=> '40',
	'45'	=> '45',
	'50'	=> '50',
	'55'	=> '55'
);

$config['time_increments_hour_minutes_thirty'] = array(
	'01:00'	=> '1:00',
	'01:30'	=> '1:30',
	'02:00' => '2:00',
	'02:30'	=> '2:30',
	'03:00' => '3:00',
	'03:30' => '3:30',
	'04:00'	=> '4:00',
	'04:30'	=> '4:30',
	'05:00'	=> '5:00',
	'05:30'	=> '5:30',
	'06:00'	=> '6:00',
	'06:30' => '6:30',
	'07:00'	=> '7:00',
	'07:30' => '7:30',
	'08:00'	=> '8:00',
	'08:30' => '8:30',
	'09:00'	=> '9:00',
	'09:30' => '9:30',
	'10:00'	=> '10:00',
	'10:30' => '10:30',
	'11:00'	=> '11:00',
	'11:30' => '11:30',
	'12:00'	=> '12:00',
	'12:30' => '12:30'
);

$config['time_meridian'] = array(
	'AM'	=> 'AM',
	'PM'	=> 'PM'
);

/* Misc Form Options */
$config['access'] = array(
	'E' => 'Everyone',
	'U' => 'Users',
	'M' => 'A Specific App',
	'O' => 'Only Me'
);

$config['access_simple'] = array(
	'E' => 'Everyone',
	'O' => 'Only Me'
);

$config['comments_allow'] = array(
	'Y'	=> 'Yes',
	'N'	=> 'No',
	'A' => 'Require Approval'
);

$config['status'] = array(
	'P'	=> 'Published',
	'S'	=> 'Saved',
	'D'	=> 'Deleted'
);


/* Bit.ly Settings */
$config['bitly_domain'] = array(
	'bit.ly'	=> 'http://bit.ly',
	'j.mp'		=> 'http://j.mp'
);

/* Link Actions */
$config['link_actions']	= array(
	'link' 		=> 'Link',
	'slideshow'	=> 'Slideshow',
	'lightbox'	=> 'Lightbox'
);

/* Design */
$config['css_background_repeat'] = array(
	'none'		=> 'None',
	'repeat-x'	=> 'Horizontal Repeat',
	'repeat-y'	=> 'Vertical Repeat',
	'repeat'	=> 'Repeat'
);

$config['css_background_position'] = array(
	'center center'		=> 'Center Center',
	'center top'		=> 'Center Top',
	'center bottom'		=> 'Center Bottom',
	'left top'			=> 'Left Top',
	'left center'		=> 'Left Center',
	'left bottom'		=> 'Left Bottom',
	'right top'			=> 'Right Top',
	'right center'		=> 'Right Center',
	'right bottom'		=> 'Right Bottom'
);

$config['css_font_sizes'] = array(
	'10px'		=> '10px',
	'12px'		=> '12px',
	'14px'		=> '14px',
	'16px'		=> '16px',
	'18px'		=> '18px',
	'21px'		=> '21px',
	'24px'		=> '24px',
	'30px'		=> '30px',
	'36px'		=> '36px',
	'48px'		=> '48px',
	'60px'		=> '60px',
	'72px'		=> '72px'
);

$config['css_font_family'] = array(
	'Arial'					=> 'Arial',
	"'Arial Black'"			=> 'Arial Black',
	"'Courier New'"			=> 'Courier New',
	'Georgia'				=> 'Georgia',
	'Impact'				=> 'Impact',
	"'Lucida Console'"		=> 'Lucida Console',
	"'Palatino Linotype'"	=> 'Palatino Linotype',
	'Tahoma'				=> 'Tahoma',
	"'Times New Roman'"		=> 'Times New Roman',
	"'Trebuchet MS'"		=> 'Trebuchet MS',
	'Verdana'				=> 'Verdana'
);

