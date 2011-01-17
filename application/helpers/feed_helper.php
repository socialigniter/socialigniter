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

function item_title($title, $type)
{
	if ($title)
	{
		return $title;
	}
	else
	{
		return ucwords($type);
	}
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

// Alerts for 'activity' feed items
function item_alerts($item)
{
	$item_alerts = NULL;

	if ($item->viewed == 'N') 
	{
		$item_status .= '<span class="item_alert_new" id="item_alert_new_'.$item->activity_id.'">New</span>';
	}

	if ($item->approval == 'A') 
	{
		$item_status .= '<span class="item_approve item_alert_approve" rel="'.$item->type.'" id="item_alert_approve_'.$item->activity_id.'">Approve</span>';
	}

	return $item_alerts;
}

// Alerts for just comments
function comment_alerts($comment)
{
	$comment_alerts = NULL;

	if ($comment->viewed == 'N') 
	{
		$comment_alerts .= '<span class="item_alert_new" id="item_alert_new_'.$comment->comment_id.'">New</span>';
	}

	if ($comment->approval == 'A') 
	{
		$comment_alerts .= '<span class="item_approve item_alert_approve" rel="comments" id="item_alert_approve_'.$comment->comment_id.'">Approve</span>';
	}

	return $comment_alerts;
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


/* Manage Section */
function manage_comments_count($comments_count)
{
	if ($comments_count)
	{
		$count = $comments_count.' Comments';
	}
	else
	{
		$count = 'No Comments';
	}
	
	return $count;
	
}

function manage_published_date($created_at, $updated_at)
{
	if ($updated_at == $created_at)
	{
		$published = 'Created at '.format_datetime('SIMPLE_TIME_ABBR', $created_at);
	}
	else
	{
		$published = 'Updated at '.format_datetime('SIMPLE_TIME_ABBR', $updated_at);	
	}
	
	return $published;
}


