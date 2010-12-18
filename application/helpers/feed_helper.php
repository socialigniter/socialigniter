<?php
/**
* Feed Helper
*
* @package		Social Igniter
* @subpackage	Feed Helper
* @author		Brennan Novak
* @link			http://social-igniter.com
*
* Takes an inputed value and check to see if it is NULL or exists
* Then attaches specified HTML tag, id, class, link, target, text, close tag
*/

function item_verb($verb_array, $verb)
{
	foreach ($verb_array as $verb_type => $verb_display)
	{
		if ($verb_type == $verb)
		{
			return $verb_display;
		}	
	}	
}

function item_type($type_array, $type)
{
	foreach ($type_array as $object_type => $type_display)
	{
		if ($object_type == $type)
		{
			return $type_display;
		}	
	}	
}

function item_type_class($type)
{
	if ($type != 'status')
	{
		return ' type_'.$type;
	}
	
	return NULL;
}

function item_linkify($text)
{
	// Links
	$text = preg_replace('/(https?:\/\/\S+)/', '<a href="\1" target="_blank">\1</a>', $text);
	
	// Users
	$text = preg_replace('/(^|\s)@(\w+)/', '\1@<a href="'.config_item('site_url').'/profile/\2" target="_blank">\2</a>', $text);
	
	// Hashtags
	$text = preg_replace('/(^|\s)#(\w+)/', '\1#<a href="'.config_item('site_url').'/search/\2" target="_blank">\2</a>', $text);

	return $text;
}

function who_is_contributor($name, $session_name, $username=NULL)
{
	if ($name == $session_name)
	{
		$display = 'You';
	}
	else
	{	
		$display = $name;
	}

	if ($username)
	{
		$display = '<a href="'.base_url().'profile/'.$username.'">'.$display.'</a>';
	}
	
	return $display;
}

function item_viewed($viewed)
{
	$status = 'item';

	if ($viewed == 'N')
	{
		$status = 'item_new';
	}
	
	return $status;
}

function item_alerts($item_id, $viewed, $approval=NULL)
{
	$item_status = NULL;

	if ($viewed == 'N') 
	{
		$item_status .= '<span class="item_alert_new" id="item_alert_new_'.$item_id.'">New</span>';
	}

	if ($approval == 'A') 
	{
		$item_status .= '<span class="item_approve item_alert_approve" id="item_alert_approve_'.$item_id.'">Approve</span>';
	}

	return $item_status;
}


/* Event Item Types */
function events_location($object, $allowed)
{
	$result 		= NULL;
	$last_value		= end($object);
	$last_allowed	= end($allowed);
	
	foreach ($object as $aspect => $value)
	{	
		if (($value != '') && (in_array($aspect, $allowed)))
		{
			if (($aspect != $last_allowed) && ($value != $last_value))
			{
				$result .= $value.', ';
			}
			else
			{
				$result .= $value;
			}
		}
	}
	
	return $result;
}

function comments_count($count)
{
	if ($count)
	{
		return $count;
	}

	return 'none';
}

