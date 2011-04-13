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
		$this->data['navigation_menu']		= $this->social_igniter->get_menu();

        // Load Views
        $this->data['head']					= $this->load->view(config_item('site_theme').'/partials/head_site.php', $this->data, true);
        $this->data['logged']				= $this->load->view(config_item('site_theme').'/partials/logged.php', $this->data, true);
        $this->data['navigation']			= $this->load->view(config_item('site_theme').'/partials/navigation_site.php', $this->data, true);
        $this->data['content']				= '';
        $this->data['shared_ajax']			= '';        
        $this->data['sidebar']				= '';
		$this->data['footer']				= $this->load->view(config_item('site_theme').'/partials/footer.php', $this->data, true);
		$this->data['message']				= $this->session->userdata('message');
		$this->data['comments_view'] 		= '';

		// If Modules Exist		
		if ($this->modules_scan)
		{
			foreach ($this->modules_scan as $module)
			{
				if (config_item($module.'_enabled') == 'TRUE')
				{			
					$module_header 		= '/modules/'.$module.'/views/partials/head_site.php';
					$module_navigation	= '/modules/'.$module.'/views/partials/navigation_site.php';
					$module_sidebar 	= '/modules/'.$module.'/views/partials/sidebar_site.php';
					$module_footer 		= '/modules/'.$module.'/views/partials/footer.php';
		
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
    }

    function render($layout='site')
    {
      	// Is Module
       	if ($this->module_name)
    	{
    	    $content_path = '../modules/'.$this->module_name.'/views/'.$this->module_controller.'/'.$this->action_name.'.php';
		}
		else
		{
        	$content_path = config_item('site_theme').'/'.$this->controller_name.'/'.$this->action_name.'.php';
		}

    	// Content file exists
        if (file_exists(APPPATH.'views/'.$content_path))
        {
            $this->data['content'] .= $this->load->view($content_path, $this->data, true);
        }
        else
        {
        	$this->data['content'] .= 'Oops that content file is mising';
        }

        $this->load->view(config_item('site_theme').'/layouts/'.$layout.'.php', $this->data);
    }
    
    function render_widgets($section)
    {
    	$widgets = '';
    	
    	$site_widgets = $this->social_igniter->make_widgets_order($this->site_widgets);
    
    	foreach ($site_widgets as $site_widget)
    	{
    		if ($site_widget->setting == $section)
    		{
    			$widget = json_decode($site_widget->value);
    		
    			if ($widget->method == 'view')
 				{   		
    				$widgets .= $this->load->view(config_item('site_theme').'/widgets/'.$widget->path, $this->data, true);
    			}
    			elseif ($widget->method == 'run')
    			{
    				$widgets .= modules::run($widget->module.'/'.$widget->path);
    			}
    			elseif ($widget->method == 'text')
				{
    				$widgets .= $widget->content;				
				}
    		}
    	}

		return $widgets;
    }
}