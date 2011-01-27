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
	
	
    /* GET types */
    function followers_get()
    {
    	$followers = $this->relantionships_model->get_relationships_user('follows', $this->get('id'));
    	
        if($followers)
        {
            $this->response($followers, 200);
        }
        else
        {
            $this->response(array('error' => 'User does not have followers'), 404);
        }
    }


	/* POST types */
    function follow_post()
    {
    	$user_id = $this->session->userdata('user_id');   
    
		$access = $this->social_igniter->has_access_to_create('category', $user_id);
		
		if ($access)
		{
        	$category_data = array(
        		'parent_id'		=> $this->input->post('parent_id'),
    			'site_id'		=> config_item('site_id'),		
    			'permission'	=> $this->input->post('permission'),
				'module'		=> $this->input->post('module'),
    			'type'			=> $this->input->post('type'),
    			'category'		=> $this->input->post('category'),
    			'category_url'	=> $this->input->post('category_url')
        	);

			// Insert
		    $category = $this->categories_model->add_category($category_data);

			if ($category)
			{
	        	$message	= array('status' => 'success', 'data' => $category);
	        	$response	= 200;
	        }
	        else
	        {
		        $message	= array('status' => 'error', 'message' => 'Oops unable to add your category');
		        $response	= 400;		        
	        }
		}
		else
		{
	        $message	= array('status' => 'error', 'message' => 'Oops unable to add your category');
	        $response	= 400;
		}	

        $this->response($message, $response); // 200 being the HTTP response code
    }
    
    
    /* PUT types */
    function update_put()
    {
		$viewed = $this->social_tools->update_comment_viewed($this->get('id'));			
    	
        if($viewed)
        {
            $this->response(array('status' => 'success', 'message' => 'Comment viewed'), 200);
        }
        else
        {
            $this->response(array('status' => 'error', 'message' => 'Could not mark as viewed'), 404);
        }    
    }  
    

    /* DELETE types */
    function unfollow_delete()
    {		
		// Make sure user has access to do this func
		$access = $this->social_tools->has_access_to_modify('comment', $this->get('id'));
    	
    	// Move this up to result of "user_has_access"
    	if ($access)
        {
			//$comment = $this->social_tools->get_comment($this->get('id'));
        
        	$this->social_tools->delete_comment($this->get('id'));
        
			// Reset comments with this reply_to_id
			$this->social_tools->update_comment_orphaned_children($this->get('id'));
			
			// Update Content
			$this->social_igniter->update_content_comments_count($this->get('id'));
        
        	$this->response(array('status' => 'success', 'message' => 'Comment deleted'), 200);
        }
        else
        {
            $this->response(array('status' => 'error', 'message' => 'Could not delete that comment!'), 404);
        }
        
    }

}