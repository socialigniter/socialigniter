<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:			Social Igniter : Controller : Ajax
* Author: 		Brennan Novak
* 		  		contact@social-igniter.com
*         		@brennannovak
*          
* Created:		Brennan Novak
* 
* Project:		http://social-igniter.com/
* Source: 		http://github.com/socialigniter/
* 
* Description: 	This funky fresh controller is for 'actions' that meet one of these criteria
*
* - Are only triggered by an AJAX request
* - Done from a public source where a user may be logged in or not
* - Never prints to a normal view- either returns AJAX value or redirects back to request page
*/

class Actions extends MY_Controller 
{
 
    function __construct() 
    {
        parent::__construct();
    }
 
 	function index()
 	{ 
		echo 'Ajax only in this hizzy, best be respectin son!';	
	}
	
	function comment_logged()
	{
		if (!$this->social_auth->logged_in()) redirect(base_url());
				
		$this->form_validation->set_rules('comment_write_text', 'Comment', 'required');

		// Validation
		if ($this->form_validation->run() == true)
		{        
			$content = $this->social_igniter->check_content_comments($this->input->post('content_id'));
			
			if ($content)
			{
	        	$comment_data = array(
	    			'reply_to_id'	=> $this->input->post('reply_to_id'),
	    			'content_id'	=> $content->content_id,        			
					'module'		=> $content->module,
	    			'type'			=> $content->type,
	    			'comment'		=> $this->input->post('comment_write_text'),
	    			'geo_lat'		=> $this->input->post('geo_lat'),
	    			'geo_long'		=> $this->input->post('geo_long'),
	    			'geo_accuracy'	=> $this->input->post('geo_accuracy'),
	    			'approval'		=> $content->comments_allow
	        	);

				// Insert Comment	
			    $comment = $this->social_tools->add_comment($this->session->userdata('user_id'), $comment_data);
	
				$this->data['comment'] 			= $this->social_tools->get_comment($comment->comment_id);
				$this->data['comment_id']		= $comment->comment_id;
		
				// Set Reply Id For Comments
				if ($comment->reply_to_id)
				{
					$this->data['sub']			= 'sub_';
					$this->data['reply_id']		= $comment->reply_to_id;
				}
				else
				{
					$this->data['sub']			= '';
					$this->data['reply_id']		= $comment->comment_id;			
				}

				// Set Display Comment
				if ($content->comments_allow == 'A')
				{
					$this->data['comment_text']	= '<i>Your comment is awaiting approval!</i>';
				}
				else
				{
					$this->data['comment_text']	= $comment->comment;
				}
				
				// Display View
				if (IS_AJAX) echo $this->load->view(config_item('site_theme').'/partials/comments_list', $this->data, true);
				else		 redirect($this->session->userdata('previous_page').'#comment-'.$comment->comment_id);
			}
			else
			{
				echo 'error';
			}
		}
		// Not Valid
		else 
		{	
			redirect($this->session->userdata('previous_page').'#comment_not_valid');
		}
	}

	function comment_public()
	{
		$comment_allow	= TRUE;
		$content 		= $this->social_igniter->check_content_comments($this->input->post('content_id'));
	
		if (!$content) $comment_allow = FALSE;
		
	 	// Validation Rules
    	$this->form_validation->set_rules('comment_name', 'Name', 'required');
    	$this->form_validation->set_rules('comment_email', 'Email Address', 'required|valid_email');
		$this->form_validation->set_rules('comment_write_text', 'Comment', 'required');
		
		// Akismet Enabled
		if (config_item('comments_akismet') == 'TRUE')
		{
			$this->form_validation->set_rules('comment_write_text', 'Comment', 'callback_comment_akismet');
		}

		// ReCAPTCHA Enabled
		if (config_item('comments_recaptcha') == 'TRUE')
		{
			$this->form_validation->set_rules('recaptcha_response_field', 'lang:recaptcha_field_name', 'required|callback_check_recaptcha');
		}

		// Validates
		if (($this->form_validation->run() == TRUE) && ($comment_allow == TRUE))
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
        			'comment'		=> $this->input->post('comment_write_text'),
        			'geo_lat'		=> $this->input->post('geo_lat'),
        			'geo_long'		=> $this->input->post('geo_long'),
        			'geo_accuracy'	=> $this->input->post('geo_accuracy'),
        			'approval'		=> $content->comments_allow	
        	);
        									
			// Insert Comment	
		    $comment = $this->social_tools->add_comment($user->user_id, $comment_data);
			
			if (IS_AJAX)
			{
				$this->data['comment'] 			= $this->social_tools->get_comment($comment->comment_id);
				$this->data['comment_id']		= $comment->comment_id;
		
				// Reply
				if ($comment->reply_to_id)
				{
					$this->data['sub']			= 'sub_';
					$this->data['reply_id']		= $comment->reply_to_id;
				}
				else
				{
					$this->data['sub']			= '';
					$this->data['reply_id']		= $comment->comment_id;			
				}
	
				// Display
				if ($content->comments_allow == 'A')
				{
					$this->data['comment_text']	= '<i>Your comment is awaiting approval!</i>';
				}
				else
				{
					$this->data['comment_text']	= $comment->comment;
				}

				echo $this->load->view(config_item('site_theme').'/partials/comments_list', $this->data, true);
			}
			else
			{		
		 		redirect($this->session->userdata('previous_page').'#comment-'.$comment->comment_id);		
			}
		}
		else
		{			
			if (IS_AJAX)
			{
				echo 'error';
			}
			else
			{
				$this->session->set_flashdata('comment_name', $this->input->post('comment_name'));
				$this->session->set_flashdata('comment_email', $this->input->post('comment_email'));
			 	$this->session->set_flashdata('comment_write_text', $this->input->post('comment_write_text'));			
				$this->session->set_flashdata('comment_error', validation_errors());
		 	
		 		redirect($this->session->userdata('previous_page').'#comments_public_form');
			}
		}
	}

	
	function comment_akismet()
	{
		$this->load->library('akismet');
		
		$comment = array('author' => $this->input->post('comment_name'), 'email' => $this->input->post('comment_email'), 'body' => $this->input->post('comment_write_text'));
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
	}
	
	function check_recaptcha($val)
	{
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
	}
	
	
	function images()
	{
	
		$this->load->view(config_item('dashboard_theme').'/ajax/images');
	
	}
	


}