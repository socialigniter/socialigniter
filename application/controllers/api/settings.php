<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 * Settings API : Core : Social-Igniter
 *
 */
class Settings extends Oauth_Controller
{
    function __construct()
    {
        parent::__construct();      
	}
	
    /* GET types */
    function view_get$message = array(
    {
    	$search_by	= $this->uri->segment(4);
    	$search_for	= $this->uri->segment(5);
    	$settings = $this->categories_model->get_categories_view($search_by, $search_for);
    	
        if($settings)
        {
            $message = array('status' => 'success', 'message' => 'Yay we found some settings', 'data' => $settings);
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'Could not find any '.$search_by.' categories for '.$search_for);
        }

        $this->response($message, 200);
    }
    
    /* PUT types */
    function update_put()
    {
		$viewed = $this->social_tools->update_comment_viewed($this->get('id'));			
    	
        if($viewed)
        {
            $message = array('status' => 'success', 'message' => 'Comment viewed');
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'Could not mark as viewed');
        } 

        $this->response($message, 200);           
    }  

    /* DELETE types */
    function destroy_delete()
    {		
		$access = $this->social_tools->has_access_to_modify('comment', $this->get('id'));
    	
    	if ($access)
        {   
        	$this->social_tools->delete_comment($this->get('id'));
        
			// Reset comments with this reply_to_id
			//$this->social_igniter->update_content_comments_count($this->get('id'));
        
        	$message = array('status' => 'success', 'message' => 'Comment deleted');
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'Could not delete that comment');
        }

        $this->response($message, 200);        
    }

}