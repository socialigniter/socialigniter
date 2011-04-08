<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 * Tags API : Core : Social-Igniter
 *
 */
class Tags extends Oauth_Controller
{
    function __construct()
    {
        parent::__construct();      
	}
	
    function all_get()
    {   
        if($tags = $this->social_tools->get_tags())
        {
            $message = array('status' => 'success', 'message' => 'Found some tags', 'data' => $tags);
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'Could not find any tags');
        }
        
        $this->response($message, 200);        
    }
    
    function create_authd_post()
    {
        if($tags = $this->social_tools->process_tags($this->input->post('tags'), $this->input->post('content_id')))
        {
            $message = array('status' => 'success', 'message' => 'Created your tags', 'data' => $tags);
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'Could not create tags');
        }
        
        $this->response($message, 200);    
    }
      

    function modify_authd_post()
    {
    	$content = $this->social_tools->get_tag($this->get('id'));
    
		// Access Rules
	   	//$this->social_auth->has_access_to_modify($this->input->post('type'), $this->get('id') $this->oauth_user_id);
	   	
    	$viewed			= 'Y';
    	$approval		= 'A'; 
   
    	$content_data = array(
			'parent_id'			=> $this->input->post('parent_id'),
			'access'			=> $this->input->post('access'),
			'category'			=> $this->input->post('category'),
			'category_url'		=> form_title_url($this->input->post('title'), $this->input->post('title_url'), $content->title_url),
			'content'			=> $this->input->post('content'),
			'details'			=> $this->input->post('details'),
			'viewed'			=> $viewed,
			'approval'			=> $approval,
    	);
    									
		// Insert
		$update = $this->social_tools->update_tag($this->get('id'), $tag_data);     		
		 		     		
	    if ($update)
	    {
        	$message = array('status' => 'success', 'message' => 'Awesome, we updated your '.$this->input->post('type'), 'data' => $update);
        }
        else
        {
	        $message = array('status' => 'error', 'message' => 'Oops, we were unable to post your '.$this->input->post('type'));
        }

	    $this->response($message, 200);
    }
    
    function destroy_authd_delete()
    {  
    	// Determine delete
    	if ($this->get('tag_id'))
    	{
			$action = $this->social_tools->delete_tag($this->get('tag_id'));
    	}
    	elseif ($this->get('tag_link_id'))
    	{
			$action = $this->social_tools->delete_tag_link($this->get('tag_link_id'));
    	}
    	elseif ($this->get('object_id'))
    	{
			$action = $this->social_tools->delete_tag_link_object($this->get('object_id'));    	
    	}
    	else
    	{
    		$action = FALSE;
    	}
    
    	// Perform delete
    	if ($action)
    	{
    		$message = array('status' => 'success', 'message' => 'Tag deleted');
    	}
    	else
    	{
    		$message = array('status' => 'error', 'message' => 'Tag was not deleted');        	
    	}
        
        $this->response($message, 200);
    }    
        
}