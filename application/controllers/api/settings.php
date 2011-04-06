<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 * Settings API : Core : Social-Igniter
 *
 */
class Settings extends Oauth_Controller
{
    function __construct()
    {
        parent::__construct(); 
        
    	$this->form_validation->set_error_delimiters('', '');             
	}
	
    /* GET types */
    function module_get()
    {    	
        if ($settings = $this->social_igniter->get_settings_module($this->uri->segment(4)))
        {
            $message = array('status' => 'success', 'message' => 'Yay we found some settings for the parameter '.$this->uri->segment(4), 'data' => $settings);
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'Could not find any settings for that module');
        }

        $this->response($message, 200);
    }
    
    function setting_get()
    {    	
        if ($setting = $this->social_igniter->get_setting($this->get('id')))
        {
            $message = array('status' => 'success', 'message' => 'Yay we found that setting', 'data' => $setting);
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'Could not find any settings for that parameter');
        }

        $this->response($message, 200);
    }
    
    function widgets_available_get()
    {
    	$region			 = $this->get('region');
    	$widgets_current = $this->social_igniter->get_settings_setting($region); 
		$widgets		 = array();

    	// Core Widgets
    	$this->load->config('widgets');

		// If Has Widgets
		if ($core_widgets = config_item('core_widgets'))
		{	
			foreach ($core_widgets as $core_widget)
			{
				if (in_array($region, $core_widget['regions']))
				{
					if ($this->social_igniter->check_can_widget_be_used($this->get('region'), $core_widget['widget']))
					{
						$widgets[] = $core_widget['widget'];
					}
				}
			}
    	}

		// Module Widgets				
		foreach ($this->social_igniter->scan_modules() as $module)
		{
    		$this->load->config($module.'/widgets');

			// If Has Widgets
			if ($modules_widgets = config_item($module.'_widgets'))
			{
				foreach ($modules_widgets as $modules_widget)
				{
					if (in_array($region, $modules_widget['regions']))
					{
						if ($this->social_igniter->check_can_widget_be_used($this->get('region'), $modules_widget['widget']))
						{
							$widgets[] = $modules_widget['widget'];
						}
					}
				}
			}
    	} 	
/*
		echo '<pre>';
		//print_r($widgets_current);
		echo '<hr>';
		print_r($widgets);
		echo '</pre>';
*/
	   	$message = array('status' => 'success', 'message' => 'Yay we found some widgets', 'data' => $widgets);
     	$this->response($message, 200);    
    }
    
	function view_get()
    {
    	$search_by	= $this->uri->segment(4);
    	$search_for	= $this->uri->segment(5);
		$content	= $this->social_igniter->get_content_view($search_by, $search_for, 50);    
   		 	
        if($content)
        {
            $message = array('status' => 'success', 'message' => 'Success content has been found', 'data' => $content);
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'Could not find any '.$search_by.' content for '.$search_for);
        }

        $this->response($message, 200);
    }   
    
    function create_authd_post()
    {
		// Validation Rules
	   	$this->form_validation->set_rules('module', 'Module', 'required');
	   	$this->form_validation->set_rules('setting', 'Setting', 'required');
	   	$this->form_validation->set_rules('value', 'Value', 'required');

		// Passes Validation
	    if ($this->form_validation->run() == true)
	    {
			if (!$this->input->post('site_id')) $site_id = config_item('site_id');
			else $site_id = $this->input->post('site_id');
				    
			$setting_data = array(
				'site_id'	=> $site_id,
				'module'	=> $this->input->post('module'),
				'setting'	=> $this->input->post('setting'),
				'value'		=> $this->input->post('value')
			);
	    
	      	
			// Insert
			$result = $this->social_igniter->add_setting($setting_data);	    	

	    	if ($result)
		    {
				// API Response
	        	$message = array('status' => 'success', 'message' => 'Awesome we posted your setting', 'data' => $result);
	        }
	        else
	        {
		        $message = array('status' => 'error', 'message' => 'Oops we were unable to post your setting');
	        }	
		}
		else 
		{
	        $message = array('status' => 'error', 'message' => validation_errors());
		}

	    $this->response($message, 200);
    
    } 
    
    // Only works if there are no duplicate 'setting' values in $_POST data
    function modify_authd_post()
    {
    	$user = $this->social_auth->get_user('user_id', $this->oauth_user_id);
    
		if ($user->user_level_id == 1)
		{
			$settings_update = $_POST;
		
			if ($settings_update)
	        {
				$this->social_igniter->update_settings($this->input->post('module'), $settings_update);
															
	            $message = array('status' => 'success', 'message' => 'Settings have been updated');
			}
			else
			{
	            $message = array('status' => 'error', 'message' => 'Settings could not be updated');
			}				
		}
		else
		{
			$message = array('status' => 'error', 'message' => 'You do not have access to update those settings');		
		}
    	
        $this->response($message, 200);           
    }  
    
    function modify_widget_authd_post()
    {
    	$widget = $this->social_igniter->get_setting($this->get('id'));
		
		if ($widget->module == 'widgets')
        {
        	$widget_data = array(
				'module'	=> 'widgets',
				'setting'	=> $widget->setting,
				'value'		=> $this->input->post('value')
        	);
       
        	$this->social_igniter->update_setting($this->get('id'), $widget_data);
			
			$updated = $this->social_igniter->get_setting($this->get('id'));
														
            $message = array('status' => 'success', 'message' => 'Widget has been updated', 'data' => $updated);
		}
		else
		{
            $message = array('status' => 'error', 'message' => 'Widget could not be updated');
		}
    	
        $this->response($message, 200);      	
    
    }

    /* DELETE types */
    function destroy_delete()
    {		
		$access = TRUE;//$this->social_tools->has_access_to_delete('comment', $this->get('id'));
    	
    	if ($access)
        {   
        	$this->social_igniter->delete_setting($this->get('id'));
        
			// Reset comments with this reply_to_id
			//$this->social_igniter->update_content_comments_count($this->get('id'));
        
        	$message = array('status' => 'success', 'message' => 'Comment deleted');
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'Could not delete that comment');
        }

        $this->response($message, 200);        
    }

}