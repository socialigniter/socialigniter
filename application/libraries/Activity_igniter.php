<?php  if  ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Activity Library
 * *
 * @package	Activity\Libraries
 * @author Brennan Novak @brennannovak
 * @link http://social-igniter.com
 * @todo Document further
 */
class Activity_igniter
{
	protected $ci;
	protected $widgets;

	function __construct()
	{
		$this->ci =& get_instance();

		// Configs
		$this->ci->load->config('activity_stream');

		// Models
 		$this->ci->load->model('activity_model');
	}


	/* Timeline Items */

    /**
     * Generate Item
     *
     * @param	object	$activity	The activity to render
     * @return	string	The $activity, rendered as html
     */
    function render_item($activity)
    {
	    // Data
    	$data 		= json_decode($activity->data);

    	// Person
    	$person 	= '<a href="' . $activity->username . '">'. $activity->name . '</a>';
    
    
    	$has_url	= property_exists($data, 'url');
    	$has_title	= property_exists($data, 'title');   
    	$has_new	= property_exists($data, 'new');
    	$has_status = property_exists($data, 'status');
				
		// Has Status
		$verb		= item_verb($this->ci->lang->line('verbs'), $activity->verb);
		$article	= item_type($this->ci->lang->line('object_articles'), $activity->type);
		$type		= item_type($this->ci->lang->line('object_types'), $activity->type);

		// Has Title
		if ($activity->type == 'status')
		{
			$title_link = '<a href="'.base_url().$activity->module.'/view/'.$activity->content_id.'">'.real_character_limiter($activity->content, 15).'</a>';
		}
		elseif (($has_title) && ($data->title))
		{
    		if ($has_url)	$title_link = $type.' <a href="'.$data->url.'">'.real_character_limiter($data->title, 15).'</a>';
    		else			$title_link = real_character_limiter($data->title, 15);
		}
		else
		{
    		if ($has_url)	$title_link = ' <a href="'.$data->url.'">'.$type.'</a>';
    		else			$title_link = $type;
		}
		
		return $person.' <span class="item_verb">'.$verb.' '.$article.' '.$title_link.'</span>';
    }

    /**
     * Generate Content
     * 
     * 
     */
    function render_item_content($type, $object)
    {
        $has_thumb	  = property_exists($object, 'thumb');
    
		$render_function = 'render_item_'.$type;
		$callable_method = array($this, $render_function);
		   
		// Custom Render Exists    		    		
		if (is_callable($callable_method, false, $callable_function))
		{
			$content = $this->$render_function($object, $has_thumb);
		}
		else
		{
			$content = $this->render_item_default($object, $has_thumb);
		}
    	
    	return '<span class="item_content">'.$content.'</span>';
    }

}