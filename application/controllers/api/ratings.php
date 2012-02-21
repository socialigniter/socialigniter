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
	
	function view_get()
	{
    	$search_by	= $this->uri->segment(4);
    	$search_for	= $this->uri->segment(5);	
	
		// Insert
		if ($ratings = $this->social_tools->get_ratings_view($search_by, $search_for))
		{
        	$message = array('status' => 'success', 'message' => 'Ratings were found', 'ratings' => $ratings);
        }
        else
        {
	        $message = array('status' => 'error', 'message' => 'Oops could not find any ratings');
        }

        $this->response($message, 200);		
	
	}
	
    function create_post()
    {
		$this->form_validation->set_rules('content_id', 'Content', 'required');
		$this->form_validation->set_rules('type', 'Type', 'required');
		$this->form_validation->set_rules('rating', 'Rating', 'required');

		// Validation
		if ($this->form_validation->run() == true)
		{
			if ($this->input->post('site_id')) $site_id = $this->input->post('site_id');
			else $site_id = config_item('site_id');
			
        	$rating_data = array(
        		'site_id'		=> $site_id,
        		'user_id'		=> $this->input->post('user_id'),
        		'content_id'	=> $this->input->post('content_id'),
        		'type'			=> $this->input->post('type'),
    			'rating'		=> $this->input->post('rating'),
    			'ip_address'	=> $this->input->ip_address()
        	);
			
			// Check If Exists
			if ($check_rating = $this->social_tools->check_rating($rating_data))
			{
				$message = array('status' => 'error', 'message' => 'Oops that rating already exists');
			}
			else
			{
				// Insert
				if ($rating = $this->social_tools->add_rating($rating_data))
				{
		        	$message = array('status' => 'success', 'message' => 'Rating was recorded', 'rating' => $rating);
		        }
		        else
		        {
			        $message = array('status' => 'error', 'message' => 'Oops could not create rating');
		        }
			}
		}
		else 
		{	
	        $message = array('status' => 'error', 'message' => validation_errors());
		}			

        $this->response($message, 200);	
	}


}