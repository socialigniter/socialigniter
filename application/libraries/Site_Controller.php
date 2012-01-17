<?php  if  ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
* Name:		Site_Controller Library
* 
* Author:	Brennan Novak
* 		  	contact@social-igniter.com
*         	@brennannovak
* 
* Location: http://github.com/socialigniter
* 
* Created:  06-01-2010
* 
* Description: Library that is extended by all Site or "Public" facing controllers
*/
class Site_Controller extends MY_Controller
{
    function __construct()
    {
        parent::__construct();

		// Global Required Quries
		$this->data['navigation_menu']	= $this->social_igniter->get_menu();

        // Load Views
        $this->data['head']			= $this->load->view(config_item('site_theme').'/partials/head_site', $this->data, true);
        $this->data['logged']		= $this->load->view(config_item('site_theme').'/partials/logged', $this->data, true);
        $this->data['navigation']	= $this->load->view(config_item('site_theme').'/partials/navigation_site', $this->data, true);
        $this->data['site_image']	= base_url().config_item('uploads_folder').'sites/'.config_item('site_id').'/large_logo.png';
        $this->data['content']		= '';
        
 		// Widget Regions	 		
 		foreach ($this->site_theme->layouts as $key => $site_layout)
 		{ 		
 			if ($key == 'sidebar')
 			{
 				foreach ($site_layout as $region)
 				{
					$this->data[$region] = '';			
 				} 			
 			}
 		}

        $this->data['shared_ajax']			= '';        
		$this->data['footer']				= $this->load->view(config_item('site_theme').'/partials/footer', $this->data, true);
		$this->data['comments_view'] 		= '';

		// If Modules Exist		
		if ($this->modules_scan)
		{
			foreach ($this->modules_scan as $module)
			{
				if (config_item($module.'_enabled') == 'TRUE')
				{			
					$module_header 		= '/modules/'.$module.'/views/partials/head_site';
					$module_navigation	= '/modules/'.$module.'/views/partials/navigation_site';
					$module_sidebar 	= '/modules/'.$module.'/views/partials/sidebar_site';
					$module_footer 		= '/modules/'.$module.'/views/partials/footer';
		
					// Set Module Asset Path
					$this->data['this_module_assets'] 	= base_url().'application/modules/'.$module.'/assets/';
				
				    if (file_exists(APPPATH.$module_header))
				    {
				    	$this->data['head'] 		.= $this->load->view('..'.$module_header, $this->data, true);
				    }
				    if (file_exists(APPPATH.$module_navigation))
				    {
				    	$this->data['navigation'] 	.= $this->load->view('..'.$module_navigation, $this->data, true);
				    }
				    if (file_exists(APPPATH.$module_sidebar))
				    {
				    	$this->data['sidebar'] 		.= $this->load->view('..'.$module_sidebar, $this->data, true);
				    }
				    if (file_exists(APPPATH.$module_footer))
				    {
				    	$this->data['footer'] 		.= $this->load->view('..'.$module_footer, $this->data, true);
					}
				}
			}
		}
		
		// This Module Assets
		if ($this->module_name)
		{
			$this->data['this_module_assets'] = base_url().'application/modules/'.$this->module_name.'/assets/';
		}
    }

    function render($layout=NULL, $content=NULL)
    {
    	// Default Layout
    	if (!$layout)	$layout	 = config_item('default_layout');
    	if (!$content)	$content = $this->action_name;

 		// Get Widgets
		foreach ($this->site_theme->layouts->$layout as $region)
		{
			$this->data[$region] = $this->render_widgets($region, $layout);	
		}

      	// Is Module
       	if ($this->module_name)
    	{
    	    $content_path = '../modules/'.$this->module_name.'/views/'.$this->module_controller.'/'.$content;    	    
		}
		else
		{
        	$content_path = config_item('site_theme').'/'.$this->controller_name.'/'.$content;
		}

    	// Content file exists
        if (file_exists(APPPATH.'views/'.$content_path.'.php'))
        {
            $this->data['content'] .= $this->load->view($content_path, $this->data, true);
        }
        else
        {
        	$this->data['content'] .= 'Oops that content file is mising';
        }
 		 		
		// Render View
        $this->load->view(config_item('site_theme').'/layouts/'.$layout, $this->data);
    }
    
    function render_widgets($region, $layout=NULL)
    {
    	$widgets = '';
    	$site_widgets = $this->social_igniter->make_widgets_order($this->site_widgets, $layout);
    	    	   
   		// Loop Through All Widgets
    	foreach ($site_widgets as $site_widget)
    	{
    		$widget = json_decode($site_widget->value);
    		
    		// If Region & Layout Are Correct
    		if ($site_widget->setting == $region AND $widget->layout == $layout)
    		{	
    			if ($widget->method == 'view')
 				{
					$this->data['widget_title']		= $widget->title;
 					$this->data['widget_content'] 	= $widget->content;
 					
    				$widgets .= $this->load->view(config_item('site_theme').'/widgets/'.$widget->path, $this->data, true);
    			}
    			elseif ($widget->method == 'run')
    			{
					$this->data['widget_title']		= $widget->title;
 					$this->data['widget_content'] 	= $widget->content;

    				$widgets .= modules::run($widget->module.'/'.$widget->path);
    			}
				else
				{
					$widgets .= '';
				}
    		}
    	}

		return $widgets;
    }
}