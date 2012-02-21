<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 * Ratings API : Core : Social-Igniter
 *
 */
class Ratings extends Oauth_Controller
{
    function __construct()
    {
        parent::__construct(); 
    
    	$this->form_validation->set_error_delimiters('', '');
	}
	
    function create_authd_post()
    {
    	// Checks for MD5 filename hash
		$this->form_validation->set_rules('content_id', 'Content', 'required');
		$this->form_validation->set_rules('type', 'Type', 'required');
		$this->form_validation->set_rules('rating', 'Rating', 'required');

		// Validation
		if ($this->form_validation->run() == true)
		{			
        	$upload_data = array(
        		'user_id'		=> $this->oauth_user_id,
        		'consumer_key'	=> $user->consumer_key,
    			'file_hash'		=> $this->input->post('file_hash')	    			
        	);
        	
			// Insert	
			if ($upload = $this->social_tools->add_rating($upload_data))
			{
	        	$message = array('status' => 'success', 'message' => 'Upload key created', 'data' => $upload);
	        }
	        else
	        {
		        $message = array('status' => 'error', 'message' => 'Oops could not create upload key');
	        }
		}
		else 
		{	
	        $message = array('status' => 'error', 'message' => validation_errors());
		}			

        $this->response($message, 200);	
	}
}