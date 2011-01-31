<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 * Comments API : Core : Social-Igniter
 *
 */
class Comments extends Oauth_Controller
{
    function __construct()
    {
        parent::__construct();        
	}
	
    /* GET types */
    // Recent Comments
    function recent_get()
    {
        $comments = $this->social_tools->get_comments_recent('all', 10);
        
        if($comments)
        {
            $this->response($comments, 200);
        }
        else
        {
            $this->response(array('status' => 'error', 'message' => 'Could not find any comments'), 200);
        }
    }

	// Comments for Content
	function content_get()
    {
    	// If No ID return error
        if(!$this->get('id'))
        {
            $this->response(array('status' => 'error', 'message' => 'Specify a content_id'), 200);
        }

        $comments = $this->social_tools->get_comments_content($this->get('id'));
    	
        if($comments)
        {
            $this->response($comments, 200);
        }
        else
        {
            $this->response(array('status' => 'error', 'message' => 'No comments could be found'), 200);
        }
    }
    
    // New Comments for a user
	function new_authd_get()
	{	
		$site_id = config_item('site_id');	
	
		if ($new_comments = $this->social_tools->get_comments_new_count($site_id, $this->oauth_user_id))
		{
         	$this->response(array('status' => 'success', 'message' => $new_comments), 200);	
		}
		else
		{
         	$this->response(array('status' => 'error', 'message' => $new_comments), 200);			
		}
	}
	

	/* POST types */
	// Creates Comment
    function create_authd_post()
    {
		$this->form_validation->set_rules('comment', 'Comment', 'required');

		// Validation
		if ($this->form_validation->run() == true)
		{  
			if ($content = $this->social_igniter->check_content_comments($this->input->post('content_id')))
			{
	        	$comment_data = array(
	    			'reply_to_id'	=> $this->input->post('reply_to_id'),
	    			'content_id'	=> $content->content_id,		
	    			'owner_id'		=> $content->user_id,
					'module'		=> $content->module,
	    			'type'			=> $content->type,
	    			'user_id'		=> $this->oauth_user_id,
	    			'comment'		=> $this->input->post('comment'),
	    			'geo_lat'		=> $this->input->post('geo_lat'),
	    			'geo_long'		=> $this->input->post('geo_long'),
	    			'geo_accuracy'	=> $this->input->post('geo_accuracy'),
	    			'approval'		=> $content->comments_allow
	        	);
	
				// Insert
				if ($comment = $this->social_tools->add_comment($comment_data))
				{
					
					$comment_data['comment_id']		= $comment->comment_id;
					$comment_data['created_at']		= format_datetime(config_item('comments_date_style'), $comment->created_at);
					$comment_data['name']			= $comment->name;
					$comment_data['username']		= $comment->username;
					$comment_data['email']			= $comment->email;
					$comment_data['image']			= $comment->image;
					$comment_data['sub']			= '';
				
					// Set Reply Id For Comments
					if ($comment->reply_to_id)
					{
						$comment_data['sub']			= 'sub_';
						$comment_data['reply_id']		= $comment->reply_to_id;
					}
	
					// Set Display Comment
					if ($content->comments_allow == 'A')
					{
						$comment_data['comment']	= '<i>Your comment is awaiting approval!</i>';
					}
				
		        	$message	= array('status' => 'success', 'message' => 'Comment posted successfully', 'data' => $comment_data);
		        	$response	= 200;
		        }
		        else
		        {
			        $message	= array('status' => 'error', 'message' => 'Oops unable to post your comment');
			        $response	= 200;		        
		        }
			}
			else
			{
		        $message	= array('status' => 'error', 'message' => 'Oops you can not comment on that!');
		        $response	= 200;
			}	
		}
		// Not Valid
		else 
		{	
	        $message	= array('status' => 'error', 'message' => validation_errors());
	        $response	= 200;
		}

        $this->response($message, $response);
    }
    

	function public_post()
	{	
	 	// Validation Rules
    	$this->form_validation->set_rules('comment_name', 'Name', 'required');
    	$this->form_validation->set_rules('comment_email', 'Email Address', 'required|valid_email');
		$this->form_validation->set_rules('comment', 'Comment', 'required');

		// Akismet Enabled
		//if (config_item('comments_akismet') == 'TRUE')
		
		//$this->form_validation->set_rules('comment', 'Comment', 'callback_akismet');
		

		// ReCAPTCHA Enabled
		//if (config_item('comments_recaptcha') == 'TRUE')
		
		//$this->form_validation->set_rules('recaptcha_response_field', 'lang:recaptcha_field_name', 'required|callback_recaptcha');

	
	    $this->load->library('recaptcha');	
	
		if ($this->recaptcha->check_answer($this->input->ip_address(), $this->input->post('recaptcha_challenge_field'), $val))
		{
			$recaptcha = TRUE;
		} 
		else
		{
			//$this->form_validation->set_message('check_captcha', $this->lang->line('recaptcha_incorrect_response'));
			$recaptcha = FALSE;
		}	
			
		

		// Validates
		if ($this->form_validation->run())
		{
			if ($content = $this->social_igniter->check_content_comments($this->input->post('content_id')))
			{
				$user = $this->social_auth->get_user_by_email($this->input->post('comment_email'));
				
				if (!$user)
				{
					$username			= url_username($this->input->post('comment_name'), 'none', true);
		        	$email				= $this->input->post('comment_email');
		        	$password			= random_string('unique');
		        	$additional_data 	= array('name' => $this->input->post('comment_name'));
					$level				= config_item('comments_group');
		        	
		        	// Register User
		        	if ($this->social_auth->register($username, $password, $email, $additional_data, $level))
		        	{
						$user = $this->social_auth->get_user_by_email($this->input->post('comment_email'));
		       		}
				}
				
	        	$comment_data = array(
	    			'reply_to_id'	=> $this->input->post('reply_to_id'),
	    			'content_id'	=> $content->content_id,		
					'module'		=> $content->module,
	    			'type'			=> $content->type,
	    			'user_id'		=> $user->user_id,
	    			'comment'		=> $this->input->post('comment'),
	    			'geo_lat'		=> $this->input->post('geo_lat'),
	    			'geo_long'		=> $this->input->post('geo_long'),
	    			'geo_accuracy'	=> $this->input->post('geo_accuracy'),
	    			'approval'		=> $content->comments_allow
	        	);
	
				// Insert
				if ($comment = $this->social_tools->add_comment($comment_data))
				{
					
					$comment_data['comment_id']		= $comment->comment_id;
					$comment_data['created_at']		= format_datetime(config_item('comments_date_style'), $comment->created_at);
					$comment_data['name']			= $comment->name;
					$comment_data['username']		= $comment->username;
					$comment_data['profile_link']	= base_url().'profiles/'.$comment->username;
					$comment_data['profile_image']	= $this->social_igniter->profile_image($comment->user_id, $comment->image, $comment->email);;
					$comment_data['sub']			= '';
				
					// Set Reply Id For Comments
					if ($comment->reply_to_id)
					{
						$comment_data['sub']			= 'sub_';
						$comment_data['reply_id']		= $comment->reply_to_id;
					}
	
					// Set Display Comment
					if ($content->comments_allow == 'A')
					{
						$comment_data['comment']	= '<i>Your comment is awaiting approval!</i>';
					}
				
		        	$message	= array('status' => 'success', 'data' => $comment_data);
		        	$response	= 200;
		        }
		        else
		        {
			        $message	= array('status' => 'error', 'message' => 'Oops unable to post your comment');
			        $response	= 200;		        
		        }
			}
			else
			{
		        $message	= array('status' => 'error', 'message' => 'Oops you can not comment on that!');
		        $response	= 200;
			}
		}
		// Not Valid
		else 
		{	
	        $message	= array('status' => 'error', 'message' => validation_errors());
	        $response	= 200;
		}

        $this->response($message, $response);
		
	}    
    
    
    /* PUT types */
    function viewed_authd_put()
    {				
        if($this->social_tools->update_comment_viewed($this->get('id')))
        {
            $this->response(array('status' => 'success', 'message' => 'Comment viewed'), 200);
        }
        else
        {
            $this->response(array('status' => 'error', 'message' => 'Could not mark as viewed'), 404);
        }    
    }   
    
    function approve_authd_put()
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
    
	// Validation
	function akismet()
	{
		return FALSE;
/*	
		$this->load->library('akismet');
		
		$comment = array('author' => $this->input->post('comment_name'), 'email' => $this->input->post('comment_email'), 'body' => $this->input->post('comment'));
		$config  = array('blog_url' => config_item('site_url'), 'api_key' => config_item('site_akismet_key'), 'comment' => $comment);
		
		$this->akismet->init($config);
		 
		if ($this->akismet->errors_exist())
		{				
			if ($this->akismet->is_error('AKISMET_INVALID_KEY')) 			
			{
				return FALSE;
			}
			elseif ($this->akismet->is_error('AKISMET_RESPONSE_FAILED'))
			{
				return FALSE;
			}
			elseif ($this->akismet->is_error('AKISMET_SERVER_NOT_FOUND'))
			{
				return FALSE;
			}
			else															
			{
				return TRUE;
			}
		}
		else
		{
			if ($this->akismet->is_spam())	
			{
				$this->form_validation->set_message('akistmet_validate', 'We think your comment might be spam!"');		
				return FALSE; 
			}
			else
			{
				return TRUE;
			}
		}
*/			
	}
	
	function recaptcha($val)
	{
		return FALSE;
/*
	    $this->load->library('recaptcha');	
	
		if ($this->recaptcha->check_answer($this->input->ip_address(), $this->input->post('recaptcha_challenge_field'), $val))
		{
			return TRUE;
		} 
		else
		{
			$this->form_validation->set_message('check_captcha', $this->lang->line('recaptcha_incorrect_response'));
			return FALSE;
		}
*/		
	}    

}