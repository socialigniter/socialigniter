<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 * Locations API : Core : Social-Igniter
 *
 */
class Places extends Oauth_Controller
{
    function __construct() 
    {
        parent::__construct();
                
    	$this->form_validation->set_error_delimiters('', '');
    }

    function index_get()
    {
    
        $locations = $this->locations_model->get_locations();
        
        if($locations)
        {
            $message 	= array('status' => 'success', 'data' =>$locations)
            $response	= 200;
        }
        else
        {
            $message	= array('error' => 'Could not find any locations');
            $response	= 404;
        }
        
        $this->response($message, $response);
    }


    
 	function create_authd_post()
	{
		// Validation Rules
	   	$this->form_validation->set_rules('module', 'Module', 'required');
	   	$this->form_validation->set_rules('type', 'Type', 'required');
	   	$this->form_validation->set_rules('content', 'Content', 'required');

		// Passes Validation
	    if ($this->form_validation->run() == true)
	    {
	    	$viewed			= 'Y';
	    	$approval		= 'N'; 

	    	$content_data = array(
	    		'site_id'			=> config_item('site_id'),
				'parent_id'			=> $this->input->post('parent_id'),
				'category_id'		=> $this->input->post('category_id'),
				'module'			=> 'locations',
				'type'				=> 'place',
				'source'			=> $this->input->post('source'),
				'order'				=> 0,
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
				'status'			=> form_submit_publish($this->input->post('publish'), $this->input->post('save_draft'))  			
	    	);

			// Insert
			$result = $this->social_igniter->add_content($content_data);	    	

	    	if ($result)
		    {
		    	// Process Tags if exist
				if ($this->input->post('tags')) $this->social_tools->process_tags($this->input->post('tags'), $result['content']->content_id);				
				
				// API Response
	        	$message = array('status' => 'success', 'message' => 'Awesome we posted your '.$content_data['type'], 'data' => $result['content'], 'activity' => $result['activity']);
	        }
	        else
	        {
		        $message = array('status' => 'error', 'message' => 'Oops we were unable to post your '.$content_data['type']);
	        }	
		}
		else 
		{
	        $message = array('status' => 'error', 'message' => validation_errors());
		}

	    $this->response($message, 200);
	}
         
    
    function modify_authd_post()
    {    
        $message 	= array('id' => $this->get('id'), 'name' => $this->post('name'), 'email' => $this->post('email'), 'message' => 'ADDED!');
        
        $this->response($message, 200);
    }
    
    function destroy_delete()
    {
        $message 	= array('id' => $this->get('id'), 'message' => 'DELETED!');
        $response	= 200;
        
        $this->response($message, $response);
    }
    

}