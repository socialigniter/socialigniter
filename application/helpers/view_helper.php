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

function display_value($tag=false, $id=false, $class=false, $name=false, $value, $link=false, $target=false)
{
	$tag_mid 	= "";
	$tag_end 	= "";
	$link_start = "";
	$link_end	= "";
	
	if ($value) 
	{
		if ($tag) 
		{
			$tag_start	= "<".$tag;
			$tag_mid 	= ">";
			$tag_end 	= "</".$tag.">";

			if ($id != false) $id = " id='".$id."'";
			if ($class != false) $class = " class='".$class."'";
		}
		
		if ($link) 
		{
			if ($target) $target = $target;

			$link_start	= "<a href='".$value."' target='".$target."'>";
			$link_end	= "</a>";
		}
	
		$value = $tag_start.$id.$class.$tag_mid.$name.$link_start.$value.$link_end.$tag_end."\n"; 
	}
	else 
	{
		$value = "";	
	}	
		
	return $value;
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
