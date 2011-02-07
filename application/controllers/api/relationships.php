<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 * Relationships API : Core : Social-Igniter
 *
 */
class Relationships extends Oauth_Controller
{
    function __construct()
    {
        parent::__construct();      
	}
	
    function followers_get()
    {
    	$followers = $this->relantionships_model->get_relationships_user('follows', $this->get('id'));
    	
        if($followers)
        {
            $message = array('status' => 'error', 'message' => 'User has have followers', 'data' => $followers);
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'User does not have followers');
        }

        $this->response($message, 200);        
    }

    function follow_post()
    {
    	$user_id = $this->session->userdata('user_id');   
    
		$access = $this->social_igniter->has_access_to_create('category', $user_id);
		
		if ($access)
		{
        	$follow_data = array(
        		'parent_id'		=> $this->input->post('parent_id'),
    			'site_id'		=> config_item('site_id'),		
    			'permission'	=> $this->input->post('permission'),
				'module'		=> $this->input->post('module'),
    			'type'			=> $this->input->post('type'),
    			'category'		=> $this->input->post('category'),
    			'category_url'	=> $this->input->post('category_url')
        	);

			// Insert
		    $follow = $this->categories_model->add_category($follow_data);

			if ($follow)
			{
	        	$message = array('status' => 'success', 'message' => 'User was successfully followed', 'data' => $follow);
	        }
	        else
	        {
		        $message = array('status' => 'error', 'message' => 'Oops unable to follow user');
	        }
		}
		else
		{
	        $message = array('status' => 'error', 'message' => 'Oops unable to follow user');
		}	

        $this->response($message, 200);
    }
    
    function update_put()
    {
		$relationship = $this->social_tools->update_comment_viewed($this->get('id'));	
    	
        if($relationship)
        {
            $message = array('status' => 'success', 'message' => 'Relationship updated', 'data' => $relationship);
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'Oops, we were unable to update your relationship');
        }    

        $this->response($message, 200);
    }  

    function unfollow_authd_delete()
    {		
		// Make sure user has access to do this func
		$access = $this->social_tools->has_access_to_modify('comment', $this->get('id'));
    	
    	// Move this up to result of "user_has_access"
    	if ($access)
        {        
        	$this->social_tools->delete_comment($this->get('id'));			
        
        	$message = array('status' => 'success', 'message' => 'User unfollowed successfully');
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'Oops, unable to unfollow that user');
        }

        $this->response($message, 200);        
    }

}