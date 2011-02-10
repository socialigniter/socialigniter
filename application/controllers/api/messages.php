<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 * messages API : Core : Social-Igniter
 *
 */
class Messages extends Oauth_Controller
{
    function __construct()
    {
        parent::__construct();      
	}
	
    /* GET types */
    function all_get()
    {
    	$messages = $this->messages_model->get_messages();
        
        if($messages)
        {
            $message 	= array('status' => 'success', 'data' => $messages);
            $response	= 200;
        }
        else
        {
            $message 	= array('status' => 'error', 'message' => 'Could not find any messages');
            $response	= 404;
        }
        
        $this->response($message, $response);        
    }

    /* GET types */
    function view_get()
    {
    	$search_by	= $this->uri->segment(4);
    	$search_for	= $this->uri->segment(5);
    	$messages = $this->messages_model->get_messages_view($search_by, $search_for);

        $this->response($messages, $response);
    }

	/* POST types */
    function create_authd_post()
    {
		$this->form_validation->set_rules('message', 'message', 'required');
		$this->form_validation->set_rules('message_url', 'message URL', 'required');
		$this->form_validation->set_rules('module', 'Module', 'required');
		$this->form_validation->set_rules('type', 'Type', 'required');
		$this->form_validation->set_rules('access', 'Access', 'required');

		// Validation
		if ($this->form_validation->run() == true)
		{
			$access = TRUE; //$this->social_igniter->has_access_to_create('message', $user_id);
			
			if (!$this->input->post('site_id')) $site_id = config_item('site_id');
			else $site_id = $this->input->post('site_id');
			
			if ($access)
			{
	        	$message_data = array(
	        		'parent_id'		=> $this->input->post('parent_id'),
	    			'site_id'		=> $site_id,
	    			'user_id'		=> $this->oauth_user_id,	
	    			'access'		=> $this->input->post('access'),
					'module'		=> $this->input->post('module'),
	    			'type'			=> $this->input->post('type'),
	    			'message'		=> $this->input->post('message'),
	    			'message_url'	=> $this->input->post('message_url'),
	    			'description'	=> $this->input->post('description'),
	    			'details'		=> $this->input->post('details')	    			
	        	);
	        	
				// Insert
			    $message = $this->social_tools->add_message($message_data);
	
				if ($message)
				{
		        	$message	= array('status' => 'success', 'data' => $message);
		        	$response	= 200;
		        }
		        else
		        {
			        $message	= array('status' => 'error', 'message' => 'Oops unable to add your message');
			        $response	= 200;		        
		        }
			}
			else
			{
		        $message	= array('status' => 'error', 'message' => 'You do not have access to add a message');
		        $response	= 200;
			}
		}
		else 
		{	
	        $message	= array('status' => 'error', 'message' => 'hrmm'.validation_errors());
	        $response	= 200;
		}			

        $this->response($message, $response);
    }
      
    /* DELETE types */
    function destroy_delete()
    {		
		// Make sure user has access to do this func
		$access = $this->social_tools->has_access_to_modify('comment', $this->get('id'));
    	
    	if ($access)
        {   
        	$this->social_tools->delete_comment($this->get('id'));
        	        
        	$this->response(array('status' => 'success', 'message' => 'Comment deleted'), 200);
        }
        else
        {
            $this->response(array('status' => 'error', 'message' => 'Could not delete that comment'), 404);
        }
        
    }

}