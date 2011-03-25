<?php
/**
* MY Text Helper
*
* @package		Social Igniter
* @subpackage	Form Helper
* @author		Brennan Novak
* @link			http://social-igniter.com
*
* @access	public
* @param	string
* @return	boolean
*
* Offers more file & folder options than the normal File Helper
*/

function form_submit_publish($status)
{
    if ($status == "publish")
    {
    	$status = "P"; 
    }
    elseif ($status == "save") 	
    {
    	$status = "S";		
	}
	else
	{
		$status = "S";	        
	}

	return $status;
}


function form_title_url($title, $title_url, $existing_url=NULL)
{
	if (($title_url != '') && ($title_url != $existing_url))
	{
		return $title_url;
	}
	else
	{
		return url_username($title, 'dash', TRUE);
	}
}


function form_content_viewed($site_id)
{
	if ($site_id == config_item('site_id'))
	{
		return 'Y';
	}
	else
	{
		return 'N';
	}
}

function country_dropdown($name="country", $top_countries=array(), $selection=NULL, $show_all=TRUE)
{
    // You may want to pull this from an array within the helper
    $countries 	= config_item('country_list');
    $html 		= "<select name='{$name}'>";
    $selected 	= NULL;
    
    if(in_array($selection,$top_countries))
    {
        $top_selection = $selection;
    	$all_selection = NULL;
    }
    else
    {
        $top_selection = NULL;
        $all_selection = $selection;
    }

    if (!empty($top_countries))
    {
    	foreach ($top_countries as $value)
    	{
            if (array_key_exists($value, $countries))
            {
                if ($value === $top_selection)
                {
                    $selected = "SELECTED";
                }
                
                $html .= "<option value='{$value}' {$selected}>{$countries[$value]}</option>";
                $selected = NULL;
            }
        }
        
        $html .= "<option>----------</option>";
	}

    if ($show_all)
    {
        foreach ($countries as $key => $country)
        {
            if ($key === $all_selection)
            {
                $selected = "SELECTED";
            }
            
            $html 		.= "<option value='{$key}' {$selected}>{$country}</option>";
            $selected 	 = NULL;
        }
    }

    return $html."</select>";
}