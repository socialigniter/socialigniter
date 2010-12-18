<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Content extends Oauth_Controller
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
            $this->response($content, 200);
        }
        else
        {
            $this->response(array('status' => 'error', 'data' => 'Could not find any comments'), 404);
        }
    }


	// Content by various
	function view_get()
    {
    	$search_by	= $this->uri->segment(4);
    	$search_for	= $this->uri->segment(5);
    	$content	= $this->social_igniter->get_content_view($search_by, $search_for);    
   		 	
        if($content)
        {
            $message 	= array('status' => 'success', 'data' => $content);
            $response	= 200;
        }
        else
        {
            $message 	= array('status' => 'error', 'message' => 'Could not find any '.$search_by.' content for '.$search_for);
            $response	= 404;        
        }

        $this->response($message, $response);
    }


	/* POST types */
	// Create Content - if module needs content to do more funky things, write an API controller in that module
	function create_authd_post()
	{
		// Validation Rules
	   	$this->form_validation->set_rules('module', 'Module', 'required');
	   	$this->form_validation->set_rules('type', 'Type', 'required');
	   	$this->form_validation->set_rules('content', 'Content', 'required');
	   	
	   	//$this->social_tools->has_access_to_create($this->input->post('type'), $this->oauth_user_id);
	
		// Passes Validation
	    if ($this->form_validation->run() == true)
	    {	    	
	    	$viewed			= 'Y';
	    	$approval		= 'A'; // $this->social_tools->has_access_to_create($this->input->post('type'), $this->oauth_user_id); 
	   		$status 		= form_submit_publish($this->input->post('publish'), $this->input->post('save_draft'));
	   	
	    	$content_data = array(				
				'parent_id'			=> $this->input->post('parent_id'),
				'category_id'		=> $this->input->post('category_id'),
				'module'			=> $this->input->post('module'),
				'type'				=> $this->input->post('type'),
				'source'			=> $this->input->post('source'),
				'order'				=> $this->input->post('order'),
	    		'user_id'			=> $this->oauth_user_id,
				'title'				=> $this->input->post('title'),
				'title_url'			=> form_title_url($this->input->post('title'), $this->input->post('title_url')),
				'content'			=> $this->input->post('content'),
				'details'			=> $this->input->post('details'),
				'access'			=> $this->input->post('access'),
				'comments_allow'	=> $this->input->post('comments_allow'),
				'geo_lat'			=> $this->input->post('geo_lat'),
				'geo_long'			=> $this->input->post('geo_long'),
				'geo_accuracy'		=> $this->input->post('geo_accuracy'),
				'viewed'			=> $viewed,
				'approval'			=> $approval,				
				'status'			=> $status  			
	    	);
	    									
			// Insert
			$content = $this->social_igniter->add_content($content_data, '');
			     		
		    if ($content)
		    {
		    	// Process Tags if exist
				if ($this->input->post('tags')) $this->social_tools->process_tags($this->input->post('tags'), $content->content_id);	
		    
				// API Response
	        	$message	= array('status' => 'success', 'message' => 'Awesome we posted your '.$content_data['type'], 'data' => $content);
	        	$response	= 200;
	        }
	        else
	        {
		        $message	= array('status' => 'error', 'message' => 'Oops we were unable to post your '.$content_data['type']);
		        $response	= 200;		        
	        }	
		}
		else 
		{
			// Does Not Pass Validation
	        $message	= array('status' => 'error', 'message' => validation_errors());
	        $response	= 200;
		}
	
	    $this->response($message, $response);
	}
        
    
    /* PUT types */
    function modify_put()
    {
		// Validation Rules
	   	$this->form_validation->set_rules('content', 'Content', 'required');
	   	
	   	//$this->social_tools->has_access_to_create($this->input->post('type'), $this->oauth_user_id);
	
		// Passes Validation
	    if ($this->form_validation->run() == true)
	    {    	
	    	$viewed			= 'Y';
	    	$approval		= 'A'; // $this->social_tools->has_access_to_create($this->input->post('type'), $this->oauth_user_id); 
	   		$status 		= 'P'; //form_submit_publish($this->input->post('publish'), $this->input->post('save_draft'));
	   	
	    	$content_data = array(
	    		'content_id'		=> $this->get('id'),	
				'parent_id'			=> $this->input->post('parent_id'),
				'category_id'		=> $this->input->post('category_id'),
				'source'			=> $this->input->post('source'),
				'order'				=> $this->input->post('order'),
				'title'				=> $this->input->post('title'),
				'title_url'			=> form_title_url($this->input->post('title'), $this->input->post('title_url')),
				'content'			=> $this->input->post('content'),
				'details'			=> $this->input->post('details'),
				'access'			=> $this->input->post('access'),
				'comments_allow'	=> $this->input->post('comments_allow'),
				'geo_lat'			=> $this->input->post('geo_lat'),
				'geo_long'			=> $this->input->post('geo_long'),
				'geo_accuracy'		=> $this->input->post('geo_accuracy'),
				'viewed'			=> $viewed,
				'approval'			=> $approval,				
				'status'			=> $status  			
	    	);
	    									
			// Insert
			$content = $this->social_igniter->update_content($content_data, $this->oauth_user_id);     		
			 
			// Process Tags
			//$this->input->post('tags');			 
			     		
		    if ($content)
		    {
				// API Response
	        	$message	= array('status' => 'success', 'message' => 'Awesome we updated your '.$content_data['type'], 'data' => $content);
	        	$response	= 200;
	        }
	        else
	        {
		        $message	= array('status' => 'error', 'message' => 'Oops we were unable to post your '.$content_data['type']);
		        $response	= 200;		        
	        }	
		}
		else 
		{
			// Does Not Pass Validation
	        $message	= array('status' => 'error', 'message' => 'You need: '.$this->input->post('content').validation_errors());
	        $response	= 200;
		}
	
	    $this->response($message, $response);
    } 

    /* DELETE types */
    function destroy_authd_delete()
    {		
		// Make sure user has access to do this func
		$access = $this->social_tools->has_access_to_modify('comment', $this->get('id'));
    	
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