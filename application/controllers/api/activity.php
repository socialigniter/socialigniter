<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 * Activity API : Core : Social-Igniter
 *
 */
class Activity extends Oauth_Controller
{
    function __construct()
    {
        parent::__construct();      
	}
	
    function recent_get()
    {
    	$activity = $this->social_igniter->get_timeline(NULL, 10);
        
        if($activity)
        {
            $message = array('status' => 'success', 'message' => 'Activity has been found', 'data' => $activity);
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'Could not find activity');
        }
        
        $this->response($message, 200); 
    }

	function view_get()
    {
    	$search_by	= $this->uri->segment(4);
    	$search_for	= $this->uri->segment(5);
		$activity	= $this->social_igniter->get_activity_view($search_by, $search_for);    
   		 	
        if($activity)
        {
            $message = array('status' => 'success', 'message' => 'Activity has been found', 'data' => $activity);
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'Could not find any '.$search_by.' activity for '.$search_for);
        }

        $this->response($message, 200);
    }

    function create_authd_post()
    {    
		$activity_info = array(
			'site_id'		=> config_item('site_id'),
			'user_id'		=> $this->oauth_user_id,
			'verb'			=> 'post',
			'module'		=> $this->input->post('module'),
			'type'			=> $this->input->post('type'),
			'content_id'	=> $this->input->post('content_id')
		);

		// Add Activity
		if ($activity = $this->social_igniter->add_activity($activity_info, $this->input->post('activity_data')))
		{
        	$message = array('status' => 'success', 'message' => 'Activity successfully created', 'data' => $activity);
        }
        else
        {
	        $message = array('status' => 'error', 'message' => 'Oops unable to add activity');
        }
	
        $this->response($message, 200);
    }
    
    function update_authd_put()
    {
		$viewed = $this->social_tools->update_activity_viewed($this->get('id'));			
    	
        if($viewed)
        {
            $message = array('status' => 'success', 'message' => 'Activity viewed');
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'Could not mark as viewed');
        } 

        $this->response($message, 200);           
    }  

    function destroy_authd_delete()
    {		
		$activity = $this->social_igniter->get_activity($this->get('id'));
	
    	if ($access = $this->social_auth->has_access_to_modify('activity', $activity, $this->oauth_user_id))
        {        
        	if ($this->social_igniter->delete_activity($activity->activity_id, $this->oauth_user_id))
        	{
        		if ($activity->type == 'status')
        		{					
					$this->social_tools->delete_comments_content($activity->content_id);        		
        		}
        	
        		$message = array('status' => 'success', 'message' => 'Activity was deleted');
        	}
        	else
        	{
        		$message = array('status' => 'error', 'message' => 'Oops activity was not deleted');        	
        	}
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'You do no have access to delete that activity');
        }
        
        $this->response($message, 200);
    }
}