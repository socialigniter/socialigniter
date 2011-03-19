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
       
        	$update = $this->social_igniter->update_setting($this->get('id'), $widget_data);
														
            $message = array('status' => 'success', 'message' => 'Widget has been updated', 'data' => $update);
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
		$access = $this->social_tools->has_access_to_modify('comment', $this->get('id'));
    	
    	if ($access)
        {   
        	$this->social_tools->delete_comment($this->get('id'));
        
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