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
	
    /* GET types */
    function recent_get()
    {
        $content = $this->social_igniter->get_content_recent('all');
        
        if($content)
        {
            $message = array('status' => 'success', 'message' => 'Found some meta content', 'data' => $content);
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'Could not find any meta content');
        }
        
        $this->response($message, 200);        
    }

	// Content by various
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

	/* POST types */
	// Create Content - if module needs content to do more funky things, write an API controller in that module
	function create_authd_post()
	{
   		//$this->social_auth->has_access_to_create($this->input->post('type'), $this->oauth_user_id);
   	
		// Process Content Meta
		// MAKE INTO A $_POST loop that gets all elements sent over
		$meta_data = array('excerpt' => $this->input->post('excerpt'));

		$content_meta = $this->content_model->add_meta(config_item('site_id'), $this->input->post('content_id'), $meta_data);
			
		     		
	    if ($content_meta)
	    {		    
        	$message = array('status' => 'success', 'message' => 'Posted your content', 'data' => $content);
        }
        else
        {
	        $message = array('status' => 'error', 'message' => 'Oops we were unable to post your content');
        }	
	
	    $this->response($message, 200);
	}
        
    
    /* PUT types */
    function modify_authd_post()
    {
    	$content = $this->social_igniter->get_content($this->get('id'));
    
		// Access Rules
	   	//$this->social_auth->has_access_to_modify($this->input->post('type'), $this->get('id') $this->oauth_user_id);
   
    	$meta_data = array(
    		'site_id'		=> config_item('site_id'),
			'content_id'	=> $this->input->post('content_id'),
			'key'			=> $this->input->post('key'),
			'value'			=> $this->input->post('value')
    	);
    									
		// Insert
		$update = $this->social_igniter->update_content($this->get('id'), $content_data, $this->oauth_user_id);     		
		 		     		
	    if ($update)
	    {
			// Process Tags    
			if ($this->input->post('tags')) $this->social_tools->process_tags($this->input->post('tags'), $content->content_id);
	    
        	$message	= array('status' => 'success', 'message' => 'Awesome, we updated your '.$this->input->post('type'), 'data' => $update);
        	$response	= 200;
        }
        else
        {
	        $message	= array('status' => 'error', 'message' => 'Oops, we were unable to post your '.$this->input->post('type'));
	        $response	= 200;		        
        }

	    $this->response($message, $response);
    } 

    /* DELETE types */
    function destroy_authd_delete()
    {		
		// Make sure user has access to do this func
		$access = $this->social_auth->has_access_to_modify('content', $this->get('id'), $this->oauth_user_id);
    	
    	// Move this up to result of "user_has_access"
    	if ($access)
        {
			if ($comment = $this->social_tools->get_comment($this->get('id')))
			{        
	        	$this->social_tools->delete_comment($comment->comment_id);
	        
				// Reset comments with this reply_to_id
				$this->social_tools->update_comment_orphaned_children($comment->comment_id);
				
				// Update Content
				$this->social_igniter->update_content_comments_count($comment->comment_id);
	        
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