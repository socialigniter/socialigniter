<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends REST_Controller
{
    function __construct()
    {
        parent::__construct();      
	}
	
    /* GET types */
    function all_get()
    {
    	$categories = $this->categories_model->get_categories();
        
        if($categories)
        {
            $this->response($categories, 200);
        }

        else
        {
            $this->response(array('error' => 'Could not find any categories'), 404);
        }
    }


    /* GET types */
    function search_get()
    {
    	$search_by	= $this->uri->segment(4);
    	$categories = $this->categories_model->get_categories_by($search_by, $this->get($search_by));
    	
        if($categories)
        {
            $this->response($categories, 200);
        }
        else
        {
            $this->response(array('error' => 'Could not find any '.$search_by.' categories for '.$this->get($search_by)), 404);
        }
    }



	/* POST types */
    function create_post()
    {   
		$content = $this->social_igniter->check_content_comments($this->get('id'));
		
		if ($content)
		{
        	$comment_data = array(
    			'reply_to_id'	=> $this->input->post('reply_to_id'),
    			'content_id'	=> $content->content_id,        			
				'module'		=> $content->module,
    			'type'			=> $content->type,
    			'user_id'		=> $this->input->post('user_id'),
    			'comment'		=> $this->input->post('comment'),
    			'geo_lat'		=> $this->input->post('geo_lat'),
    			'geo_long'		=> $this->input->post('geo_long'),
    			'geo_accuracy'	=> $this->input->post('geo_accuracy'),
    			'approval'		=> $content->comments_allow
        	);

			// Insert
		    $comment = $this->social_tools->add_comment($comment_data);

			if ($comment)
			{	
				$comment_data['comment_id']		= $comment->comment_id;
				$comment_data['created_at']		= format_datetime(config_item('comments_date_style'), $comment->created_at);
				$comment_data['name']			= $comment->name;
				$comment_data['username']		= $comment->username;
				$comment_data['profile_link']	= base_url().'profiles/'.$comment->username;
				$comment_data['profile_image']	= $this->social_igniter->profile_image($comment->user_id, $comment->image, $comment->email);;
			
				// Set Reply Id For Comments
				if ($comment->reply_to_id)
				{
					$comment_data['sub']			= 'sub_';
					$comment_data['reply_id']		= $comment->reply_to_id;
				}
				else
				{
					$comment_data['sub']			= '';
					$comment_data['reply_id']		= $comment->comment_id;			
				}

				// Set Display Comment
				if ($content->comments_allow == 'A')
				{
					$comment_data['comment_text']	= '<i>Your comment is awaiting approval!</i>';
				}
				else
				{
					$comment_data['comment_text']	= $comment->comment;
				}

	        	$message	= array('status' => 'success', 'data' => $comment_data);
	        	$response	= 200;
	        }
	        else
	        {
		        $message	= array('status' => 'error', 'message' => 'Oops unable to post your comment');
		        $response	= 400;		        
	        }
		}
		else
		{
	        $message	= array('status' => 'error', 'message' => 'Oops unable to post your comment');
	        $response	= 400;
		}	

        $this->response($message, $response); // 200 being the HTTP response code
    }
    
    /* PUT types */
    function viewed_put()
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
    function destroy_delete()
    {		
		// Make sure user has access to do this func
		$access = $this->social_tools->does_user_have_access('comment', $this->get('id'));
    	
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