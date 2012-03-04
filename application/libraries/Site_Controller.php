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
* Description: Library that is extended by all Site or Public facing Controllers
*/
class Site_Controller extends MY_Controller
{
    function __construct()
    {
        parent::__construct();

		// Global Required Queries
		$this->data['navigation_menu']	= $this->social_igniter->get_menu();

        // Load Basic Views
        // This Should probably be abstracted away to widgets
        $head_view			= config_item('site_theme').'/partials/head_site';
        $logged_view		= config_item('site_theme').'/partials/logged';
		$navigation_view	= config_item('site_theme').'/partials/navigation_site';
		$footer_view		= config_item('site_theme').'/partials/footer_site';

        if (file_exists(APPPATH.'views/'.$head_view.'.php'))
        {
        	$this->data['head']		= $this->load->view($head_view, $this->data, true);
        }
        else
        {
        	$this->data['head']		= '';
        }

        if (file_exists(APPPATH.'views/'.$logged_view.'.php'))
        {
        	$this->data['logged']	= $this->load->view($logged_view, $this->data, true);
        }
        else
        {
        	$this->data['logged']	= '';
        }

        if (file_exists(APPPATH.'views/'.$navigation_view.'.php'))
        {
        	$this->data['navigation']= $this->load->view($navigation_view, $this->data, true);
        }
        else
        {
        	$this->data['navigation']= '';
        }
        
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

		// Load Views
        $this->data['shared_ajax']		= '';        
		$this->data['comments_view'] 	= '';

		// Load Footer
        if (file_exists(APPPATH.'views/'.$footer_view.'.php'))
        {
        	$this->data['footer']	= $this->load->view($footer_view, $this->data, true);
        }
        else
        {
        	$this->data['footer']	= '';
        }


		// If Modules Exist		
		if ($this->modules_scan)
		{
			foreach ($this->modules_scan as $module)
			{
				if (config_item($module.'_enabled') == 'TRUE')
				{
					$module_head 	= 'modules/'.$module.'/views/partials/head_site.php';
					$module_footer 	= 'modules/'.$module.'/views/partials/footer_site.php';

					// Set Module Asset Path
					$this->data['this_module_assets'] 	= base_url().'application/modules/'.$module.'/assets/';
				
					// Include Heads
				    if (file_exists(APPPATH.$module_head))
				    {
				    	$this->data['head'] .= $this->load->view('../'.$module_head, $this->data, true);
				    }
					
					// Include Footers
				    if (file_exists(APPPATH.$module_footer))
				    {
				    	$this->data['footer'] .= $this->load->view('../'.$module_footer, $this->data, true);
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


	// Renders Layout
    function render($layout=NULL, $content=NULL)
    {
    	// Default Layout
    	if (!$layout)	$layout	 = $this->site_theme->default_layout;
    	if (!$content)	$content = $this->action_name;

<<<<<<< HEAD
 		// Get Widgets
=======
 		// Get Widgets 		
>>>>>>> 94eb2815c3365624306d9586818506cf58034a5a
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

   
    // Renders Widgets
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
    			$this->data['widget_id']		= $site_widget->settings_id;
    			$this->data['widget_region']	= $region;
				$this->data['widget_title']		= $widget->title;
 				$this->data['widget_content'] 	= $widget->content;

				// View or Run Widget
    			if ($widget->method == 'view')
 				{
    				$widgets .= $this->load->view(config_item('site_theme').'/widgets/'.$widget->path, $this->data, true);
    			}
    			elseif ($widget->method == 'run')
    			{
    				// Is Core Widget
    				if (in_array($widget->module, config_item('core_modules')))
    				{
    					$widgets .= modules::run('site/'.$widget->path, $this->data);    					
    				}
    				else
    				{
    					$widgets .= modules::run($widget->module.'/'.$widget->path, $this->data);
    				}
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