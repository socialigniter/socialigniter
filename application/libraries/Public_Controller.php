<?php  if  ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:		Public_Controller Library
* 
* Author:	Brennan Novak
* 		  	contact@social-igniter.com
*         	@brennannovak
* 
* Location: http://github.com/socialigniter
* 
* Created:  06-01-2010
* 
* Description: Library that is extended by all "Public" facing site controllers
*/

class Public_Controller extends MY_Controller
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
        $this->data['sidebar']				= $this->load->view(config_item('site_theme').'/partials/sidebar_site.php', $this->data, true);
		$this->data['footer']				= $this->load->view(config_item('site_theme').'/partials/footer.php', $this->data, true);

        //handles module views
        $this->data['modules_head']   		= '';
        $this->data['modules_navigation']  	= '';
        $this->data['modules_content']  	= '';
        $this->data['modules_sidebar']  	= '';
		$this->data['modules_footer']		= '';
		
		$modules_scan 		= $this->social_igniter->scan_modules();
		
		foreach ($modules_scan as $module):
		
			$module_header 						= '/modules/'.$module.'/views/partials/head_site.php';
			$module_navigation					= '/modules/'.$module.'/views/partials/navigation_site.php';
			$module_sidebar 					= '/modules/'.$module.'/views/partials/sidebar_site.php';
			$module_footer 						= '/modules/'.$module.'/views/partials/footer.php';

			// Set Module Asset Path
			$this->data['this_module_assets'] 	= base_url().'application/modules/'.$module.'/assets/';
		
		    if (file_exists(APPPATH.$module_header))
		    {
		    	$this->data['modules_head'] 		.= $this->load->view('..'.$module_header, $this->data, true);
		    }
		    if (file_exists(APPPATH.$module_navigation))
		    {
		    	$this->data['modules_navigation'] 	.= $this->load->view('..'.$module_navigation, $this->data, true);
		    }
		    if (file_exists(APPPATH.$module_sidebar))
		    {
		    	$this->data['modules_sidebar'] 		.= $this->load->view('..'.$module_sidebar, $this->data, true);
		    }
		    if (file_exists(APPPATH.$module_footer))
		    {
		    	$this->data['modules_footer'] 		.= $this->load->view('..'.$module_footer, $this->data, true);        	
			}
			
		endforeach;
    }
    
    function render($layout='site')
    {    
      	// Is Module
       	if ($this->module_name) 
    	{
        	$navigation_path	= '../modules/'.$this->module_name.'/views/partials/navigation_'.$this->module_controller.'.php';        
    	    $content_path 		= '../modules/'.$this->module_name.'/views/'.$this->module_controller.'/'.$this->action_name.'.php';
		}
		else
		{
	    	$navigation_path 	= config_item('site_theme').'/partials/navigation_'.$this->controller_name.'.php';        
        	$content_path 		= config_item('site_theme').'/'.$this->controller_name.'/'.$this->action_name.'.php';
		}

		// Does Navigation file exist
        if (file_exists(APPPATH.'views/'.$navigation_path))
        {
            $this->data['navigation'] 	.= $this->load->view($navigation_path, $this->data, true);
        }  
    	// Does Content file exist
        if (file_exists(APPPATH.'views/'.$content_path))
        {
            $this->data['content'] 		.= $this->load->view($content_path, $this->data, true);
        }
        
        $this->load->view(config_item('site_theme').'/layouts/'.$layout.'.php', $this->data);  //load the template   
    }    
}