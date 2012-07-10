<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 * Content Meta API : Core : Social-Igniter
 *
 */
class Content_meta extends Oauth_Controller
{
    function __construct()
    {
        parent::__construct(); 
    
    	$this->form_validation->set_error_delimiters('', '');
	}
	
	function view_get()
    {
    	$search_by	= $this->uri->segment(4);
    	$search_for	= $this->uri->segment(5);
		$content	= $this->social_igniter->get_content_view($search_by, $search_for);    
   		 	
        if($content)
        {
            $message = array('status' => 'success', 'data' => $content);
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'Could not find any '.$search_by.' content for '.$search_for);
        }

        $this->response($message, 200);
    }

	// Create Content Meta
	function create_authd_post()
	{
		if ($content = $this->social_igniter->get_content($this->get('id')))
		{
			if ($content->user_id == $this->oauth_user_id)
			{
			   	if ($this->input->post('site_id')) $site_id = $this->input->post('site_id');
			   	else $site_id = config_item('site_id');
		   
		    	$meta_data = array(
		    		'site_id'		=> $site_id,
					'content_id'	=> $this->get('id'),
					'meta'			=> $this->input->post('meta'),
					'value'			=> $this->input->post('value')
		    	);
		
				$content_meta = $this->content_model->add_meta($meta_data);					
				     		
			    if ($content_meta)
			    {		    
		        	$message = array('status' => 'success', 'message' => 'Posted your content', 'data' => $content);
		        }
		        else
		        {
			        $message = array('status' => 'error', 'message' => 'Oops we were unable to post your content');
		        }
		    }
	        else
	        {
		        $message = array('status' => 'error', 'message' => 'Oops you do not have access to that piece of content');
	        }
	    }
        else
        {
	        $message = array('status' => 'error', 'message' => 'Oops that piece of content does not exist');
        }

	    $this->response($message, 200);
	}
        
    // Accepts a single
    // 'id' represents the content_meta_id
    function modify_authd_post()
    {
		if ($content_meta = $this->social_igniter->get_meta($this->get('id')))
		{		
			if ($content_meta->user_id == $this->oauth_user_id)
			{	
				$update_meta = $this->social_igniter->update_meta($content_meta->content_meta_id, $this->input->post('value'));	

				if ($update_meta)
				{
		        	$message = array('status' => 'success', 'message' => 'Yay, we updated your content', 'content_meta' => $update_meta);
        		}
        		else
        		{
	        		$message = array('status' => 'error', 'message' => 'Oops that content could not be updated');
        		}						
		    }
	        else
	        {
		        $message = array('status' => 'error', 'message' => 'Oops you do not have access to that piece of content');
	        }
	    }
        else
        {
	        $message = array('status' => 'error', 'message' => 'Oops that piece of content does not exist');
        }

	    $this->response($message, 200);
    } 

    // Accepts an array of POST data
    // Each element in the POST gets entered as 
    // Matching key/value pair where key is element name and value is element value
    function modify_multiple_authd_post()
    {
		if ($content = $this->social_igniter->get_content($this->get('id')))
		{
		    // Access Rules
	   		//$this->social_auth->has_access_to_modify($this->input->post('type'), $this->get('id') $this->oauth_user_id);
		
			if ($content->user_id == $this->oauth_user_id)
			{	
				$update_meta = $this->social_igniter->update_meta_multiple(config_item('site_id'), $content->content_id, $_POST);	

				if ($update_meta)
				{
		        	$message = array('status' => 'success', 'message' => 'Yay, we updated your content', 'content_meta' => $update_meta);
        		}
        		else
        		{
	        		$message = array('status' => 'error', 'message' => 'Oops that content could not be updated');
        		}						
		    }
	        else
	        {
		        $message = array('status' => 'error', 'message' => 'Oops you do not have access to that piece of content');
	        }
	    }
        else
        {
	        $message = array('status' => 'error', 'message' => 'Oops that piece of content does not exist');
        }

	    $this->response($message, 200);
    } 

    /* DELETE types */
    function destroy_authd_get()
    {		
		// Make sure user has access to do this func
		$access = $this->social_auth->has_access_to_modify('content', $this->get('id'), $this->oauth_user_id);
    	
    	// Move this up to result of "user_has_access"
    	if ($access)
        {
			if ($comment = $this->social_tools->get_comment($this->get('id')))
			{        
	        	$this->social_tools->delete_comment($comment->comment_id);				
	        
	        	$this->response(array('status' => 'success', 'message' => 'Comment deleted'), 200);
	        }
	        else
	        {
	            $this->response(array('status' => 'error', 'message' => 'Could not delete that comment!'), 404);
	        }
        }
        else
        {
            $this->response(array('status' => 'error', 'message' => 'Could not delete that comment!'), 404);
        }
        
    }   

}