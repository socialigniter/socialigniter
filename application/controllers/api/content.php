<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Content extends Oauth_Controller
{
    function __construct()
    {
        parent::__construct();      
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


	// Content by ID
	function view_get()
    {
   		 	
    	// If No ID return error
        if(!$this->get('id'))
        {
            $this->response(array('status' => 'error', 'message' => 'Specify a content_id'), 200);
        }

        $content = $this->social_igniter->get_content($this->get('id'));
    	
        if($content)
        {
            $this->response($content, 200);
        }
        else
        {
            $this->response(array('status' => 'error', 'message' => 'No comments could be found'), 404);
        }
    }


	/* POST types */
	// Create Content - if module needs content to do more funky things, write an API controller in that module
	function create_authd_post()
	{
		// Validation Rules
	   	$this->form_validation->set_rules('module', 'Module', 'required');
	   	$this->form_validation->set_rules('type', 'Type', 'required');
	   	$this->form_validation->set_rules('title', 'Title', 'required');	
	   	$this->form_validation->set_rules('content', 'Content', 'required');
	
		// Passes Validation
	    if ($this->form_validation->run() == true)
	    {	    	
	    	$viewed			= 'Y';
	    	$approval		= 'A'; // $this->social_tools->has_access_to_create($this->input->post('type'), $this->oauth_user_id); 
	   		$status 		= 'P'; //form_submit_publish($this->input->post('publish'), $this->input->post('save_draft'));
	   		$comments_allow	= $this->input->post('comments_allow');
	   	
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
				'comments_allow'	=> $comments_allow,
				'geo_lat'			=> $this->input->post('geo_lat'),
				'geo_long'			=> $this->input->post('geo_long'),
				'geo_accuracy'		=> $this->input->post('geo_accuracy'),
				'viewed'			=> $viewed,
				'approval'			=> $approval,				
				'status'			=> $status  			
	    	);
	    									
			// Insert
			$content = $this->social_igniter->add_content($content_data, $this->input->post('tags'), '');
			     		
		    if ($content)
		    {	
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
    	$approve = $this->social_tools->update_comment_approve($this->get('id'));	

        if($approve)
        {
            $this->response(array('status' => 'success', 'message' => 'Comment approved'), 200);
        }
        else
        {
            $this->response(array('status' => 'error', 'message' => 'Could not be approved'), 404);
        }
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