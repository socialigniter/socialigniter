<?php
/**
* View Helper
*
* @package		Social Igniter
* @subpackage	View Helper
* @author		Brennan Novak
* @link			http://social-igniter.com
*
*/

function is_empty($value)
{
	if ($value)
	{
		return $value;
	}
}

function is_uri_value($uri_segment, $value_array)
{
	foreach ($value_array as $value)
	{
		if ($uri_segment == $value)
		{
			return TRUE;
		}
	}
	
	return FALSE;
}

function navigation_list_btn($link, $word)
{
	$link = base_url().$link;

	if (current_url() == $link)
	{
		$link = '<li class="button_basic_on"><span>'.$word.'</span></li>';
	}
	else
	{
		$link = '<li><a href="'.$link.'">'.$word.'</a></li>';
	}
	return $link;
}

function display_value($tag=false, $id=false, $class=false, $value=false)
{
	$tag_start	= '';
	$tag_close 	= '';
	$tag_end 	= '';
	
	if ($value) 
	{
		if ($tag) 
		{
			$tag_start	= '<'.$tag;
			$tag_close 	= '>';
			$tag_end 	= "</".$tag.">";

			if ($id != false) $id = ' id="'.$id.'"';
			if ($class != false) $class = ' class="'.$class.'"';
		}
	
		$result = $tag_start.$id.$class.$tag_close.$value.$tag_end."\n"; 
	}
	else 
	{
		$result = '';	
	}
		
	return $result;
}

function display_link($id=false, $class=false, $link=false, $value=false, $target=false)
{
	if ($link) 
	{
		if ($id != false) 		$id		= ' id="'.$id.'" ';
		if ($class != false)	$class	= " class='".$class."' ";
		if ($target) 			$target = 'target="'.$target.'"';
		if (!$value)			$value	= $link;
		
		$result = '<a '.$id.$class.' href="'.$link.'" '.$target.'>'.$value.'</a>'."\n";
	}
	else
	{
		$result = "";
	}
		
	return $result;
}


// Works similar to display_value() except it's suited for images specify full path for $image_pre, $image_null 
function display_image($id=false, $class=false, $image_pre, $image, $image_null=false, $alt=false)
{
	
	if ($image) 
	{
		$image = "<img ".$id.$class." src='".$image_pre.$image."' alt='".$alt."' border='0' />\n"; 
	}
	elseif (empty($image)) 
	{
		$image = "<img ".$id.$class." src='".$image_null."' alt='".$alt."' border='0' />\n";	
	}	
		
	return $image;
}
