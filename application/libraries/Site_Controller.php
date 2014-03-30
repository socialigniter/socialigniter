<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Site_Controller Library
 * 
 * Library that is extended by all Site or Public facing Controllers
 *
 * @author Brennan Novak <contact@social-igniter.com> @brennannovak
 * @package Social Igniter\Libraries
 */
class Site_Controller extends MY_Controller
{
    function __construct()
    {
        parent::__construct(); 
        
		// Dashboard & Public values for logged
		if ($this->social_auth->logged_in()):
			// OAuth Tokens
			$this->data['oauth_consumer_key'] 	= $this->session->userdata('consumer_key');
			$this->data['oauth_consumer_secret']= $this->session->userdata('consumer_secret');
			$this->data['oauth_token'] 			= $this->session->userdata('token');
			$this->data['oauth_token_secret'] 	= $this->session->userdata('token_secret');

			// Logged Values
			$this->data['logged_is']			= 'yes';
			$this->data['logged_user_id']		= $this->session->userdata('user_id');
			$this->data['logged_user_level_id']	= $this->session->userdata('user_level_id');
			$this->data['logged_username']		= $this->session->userdata('username');
			$this->data['logged_name']			= $this->session->userdata('name');
			$this->data['logged_email']			= $this->session->userdata('email');
			$this->data['logged_phone_number']	= $this->session->userdata('phone_number');			
			$this->data['logged_image'] 		= $this->social_igniter->profile_image($this->session->userdata('user_id'), $this->session->userdata('image'), $this->session->userdata('gravatar'), 'medium');
			$this->data['logged_profile']		= $this->social_igniter->profile_link($this->session->userdata('username'));
			$this->data['logged_location']		= $this->session->userdata('location');
			$this->data['logged_geo_enabled']	= $this->session->userdata('geo_enabled');
			$this->data['logged_privacy']		= $this->session->userdata('privacy');

			// Various Links
			$this->data['link_home']			= base_url()."home";
			$this->data['link_profile']			= base_url()."people/".$this->session->userdata('username');
			$this->data['link_settings']		= base_url()."settings/profile";
			$this->data['link_logout']			= base_url().'logout';	
		else:
			// OAuth Tokens
			$this->data['oauth_consumer_key'] 	= '';
			$this->data['oauth_consumer_secret']= '';
			$this->data['oauth_token'] 			= '';
			$this->data['oauth_token_secret'] 	= '';

			// Logged Values	
			$this->data['logged_is']			= 'no';
			$this->data['logged_user_id']		= '';	
			$this->data['logged_user_level_id']	= '';
			$this->data['logged_username']		= '';
			$this->data['logged_image'] 		= base_url().'application/views/'.config_item('site_theme').'/assets/images/medium_'.config_item('no_profile');
			$this->data['logged_profile']		= $this->social_igniter->profile_link();
			$this->data['logged_name']			= 'Your Name';
			$this->data['logged_email']			= '';
			$this->data['logged_phone_number']	= '';			
			$this->data['logged_location']		= '';
			$this->data['logged_geo_enabled']	= '';
			$this->data['logged_privacy']		= '';
		endif;


        // Load Head
        $head_view = config_item('site_theme').'/partials/head_site';
        if (file_exists(APPPATH.'views/'.$head_view.'.php')):
        	$this->data['head']		= $this->load->view($head_view, $this->data, true);
        else:
        	$this->data['head']		= '';
        endif;


        // Load Logged
        $logged_view = config_item('site_theme').'/partials/logged';
        if (file_exists(APPPATH.'views/'.$logged_view.'.php')):
        	$this->data['logged']	= $this->load->view($logged_view, $this->data, true);
        else:
        	$this->data['logged']	= '';
        endif;

      
        // Set Empty Values
        $this->data['site_image']	= '';
        $this->data['content']		= '';

 		// Widget Regions	 		
 		foreach ($this->site_theme->layouts as $key => $site_layout):
 			if ($key == 'sidebar'):
 				foreach ($site_layout as $region):
					$this->data[$region] = '';			
 				endforeach;			
 			endif;
 		endforeach;


		// Load Views
        $this->data['shared_ajax']		= '';        
		$this->data['comments_view'] 	= '';


		// Load Footer
		$footer_view = config_item('site_theme').'/partials/footer_site';
        if (file_exists(APPPATH.'views/'.$footer_view.'.php')):
        	$this->data['footer']	= $this->load->view('/'.$footer_view.'.php', $this->data, true);
        else:
        	$this->data['footer']	= '';
        endif;

		// If Modules Exist
		if ($this->modules_scan):
			foreach ($this->modules_scan as $module):
				if (config_item($module.'_enabled') == 'TRUE'):
					$module_head 	= 'modules/'.$module.'/views/partials/head_site.php';
					$module_footer 	= 'modules/'.$module.'/views/partials/footer_site.php';

					// Set Module Asset Path
					$this->data['this_module_assets'] 	= base_url().'application/modules/'.$module.'/assets/';

					// If Enabled
					if ($this->site_theme->head_footer == 'yes'):
						// Include Heads
					    if (file_exists(APPPATH.$module_head)):
					    	$this->data['head'] .= $this->load->view('../'.$module_head, $this->data, true);
					    endif;
						
						// Include Footers
					    if (file_exists(APPPATH.$module_footer)):
					    	$this->data['footer'] .= $this->load->view('../'.$module_footer, $this->data, true);
						endif;
					endif;
				endif;
			endforeach;
		endif;


		// This Module Assets
		if ($this->module_name):
			$this->data['this_module_assets'] = base_url().'application/modules/'.$this->module_name.'/assets/';
		endif;
    }

	/**
	 * render() – Renders a view to the output
	 * 
	 * @param string $layout The layout to render
	 * @param string $content
	 */
    function render($layout=NULL, $content=NULL)
    {
    	// Default Layout
 		if (!property_exists($this->site_theme->layouts, $layout)) $layout = $this->site_theme->default_layout;
    	if (!$content)	$content = $this->action_name;	

 		// Do Widgets
		foreach ($this->site_theme->layouts->$layout as $region):
			$this->data[$region] = $this->render_widgets($region, $layout);	
		endforeach;


      	// Is Module
       	if ($this->module_name):
    	    $content_path = '../modules/'.$this->module_name.'/views/'.$this->module_controller.'/'.$content;    	    
		else:
        	$content_path = config_item('site_theme').'/'.$this->controller_name.'/'.$content;
		endif;


    	// Content file exists
        if (file_exists(APPPATH.'views/'.$content_path.'.php')):
            $this->data['content'] .= $this->load->view($content_path, $this->data, true);
        else:
        	$this->data['content'] .= 'Oops that content file is mising';
        endif;

	
		// Render View
        $this->load->view(config_item('site_theme').'/layouts/'.$layout, $this->data);
    }

	/**
	 * render_custom_layout() – Renders a custom view 
	 * 
	 * @param string $layout The full application path to the layout '../../../modules/app_name/views/layouts/custom'
	 */
    function render_custom_layout($layout)
    {
        $this->load->view($layout, $this->data);
    }

	/**
	 * render_widgets() – Renders widgets on a page
	 * 
	 * @param string $layout The full application path to the layout '../../../modules/app_name/views/layouts/custom'
	 */
    function render_widgets($region, $layout=NULL)
    {
    	$widgets = '';
    	$site_widgets = $this->social_igniter->make_widgets_order($this->site_widgets, $layout);
    	    	   
   		// Loop Through All Widgets
    	foreach ($site_widgets as $site_widget):
    		$widget = json_decode($site_widget->value);
    		
    		// If Region & Layout Are Correct
    		if ($site_widget->setting == $region AND $widget->layout == $layout):
    			$this->data['widget_id']		= $site_widget->settings_id;
    			$this->data['widget_region']	= $region;
				$this->data['widget_title']		= $widget->title;
 				$this->data['widget_content'] 	= $widget->content;

				// View or Run Widget
    			if ($widget->method == 'view'):
    				$widgets .= $this->load->view('widgets/'.$widget->path, $this->data, true);
    			elseif ($widget->method == 'run'):
    				// Is Core Widget
    				if (in_array($widget->module, config_item('core_modules'))):
    					$widgets .= modules::run('site/'.$widget->path, $this->data);    					
    				else:
    					$widgets .= modules::run($widget->module.'/'.$widget->path, $this->data);
    				endif;
				else:
					$widgets .= '';
				endif;
    		endif;
    	endforeach;

		return $widgets;
    }

}