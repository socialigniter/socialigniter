<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 * Categories API : Core : Social-Igniter
 *
 */
class Categories extends Oauth_Controller
{
    function __construct()
    {
        parent::__construct();
        
    	$this->form_validation->set_error_delimiters('', '');              
	}
	
    function all_get()
    {
    	$categories = $this->categories_model->get_categories();
        
        if($categories)
        {
            $message = array('status' => 'success', 'message' => 'Category found', 'data' => $categories);
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'Could not find any categories');
        }
        
        $this->response($message, 200);        
    }

    function view_get()
    {
    	$search_by	= $this->uri->segment(4);
    	$search_for	= $this->uri->segment(5);
    	$categories = $this->categories_model->get_categories_view($search_by, $search_for);
    	
        if($categories)
        {
            $message = array('status' => 'success', 'message' => 'Category were found', 'data' => $categories);
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'Could not find any '.$search_by.' categories for '.$search_for);
        }

        $this->response($message, 200);
    }

    function create_authd_post()
    {
		$this->form_validation->set_rules('category', 'Category', 'required');
		$this->form_validation->set_rules('category_url', 'Category URL', 'required');
		$this->form_validation->set_rules('module', 'Module', 'required');
		$this->form_validation->set_rules('type', 'Type', 'required');
		$this->form_validation->set_rules('access', 'Access', 'required');

		// Validation
		if ($this->form_validation->run() == true)
		{
			$access = TRUE; //$this->social_auth->has_access_to_create('category', $user_id);
			
			if (!$this->input->post('site_id')) $site_id = config_item('site_id');
			else $site_id = $this->input->post('site_id');
			
			if ($access)
			{
	        	$category_data = array(
	        		'parent_id'		=> $this->input->post('parent_id'),
	    			'site_id'		=> $site_id,
	    			'user_id'		=> $this->oauth_user_id,	
	    			'access'		=> $this->input->post('access'),
					'module'		=> $this->input->post('module'),
	    			'type'			=> $this->input->post('type'),
	    			'category'		=> $this->input->post('category'),
	    			'category_url'	=> $this->input->post('category_url'),
	    			'description'	=> $this->input->post('description'),
	    			'details'		=> $this->input->post('details')	    			
	        	);
	        	
				// Insert
			    $category = $this->social_tools->add_category($category_data);
	
				if ($category)
				{
		        	$message = array('status' => 'success', 'data' => $category);
		        }
		        else
		        {
			        $message = array('status' => 'error', 'message' => 'Oops unable to add your category');
		        }
			}
			else
			{
		        $message = array('status' => 'error', 'message' => 'You do not have access to add a category');
			}
		}
		else 
		{	
	        $message = array('status' => 'error', 'message' => 'hrmm'.validation_errors());
		}			

        $this->response($message, 200);
    }
    
    function modify_authd_post()
    {
    	$content = $this->social_igniter->get_content($this->get('id'));
    
		// Access Rules
	   	//$this->social_auth->has_access_to_modify($this->input->post('type'), $this->get('id') $this->oauth_user_id);
	   	
    	$viewed			= 'Y';
    	$approval		= 'A'; 
   
    	$content_data = array(
			'parent_id'			=> $this->input->post('parent_id'),
			'access'			=> $this->input->post('access'),
			'category'			=> $this->input->post('category'),
			'category_url'		=> form_title_url($this->input->post('title'), $this->input->post('title_url'), $content->title_url),
			'content'			=> $this->input->post('content'),
			'details'			=> $this->input->post('details'),
			'viewed'			=> $viewed,
			'approval'			=> $approval,
    	);
    									
		// Insert
		$update = $this->social_tools->update_category($this->get('id'), $category_data, $this->oauth_user_id);     		
		 		     		
	    if ($update)
	    {
        	$message = array('status' => 'success', 'message' => 'Awesome, we updated your '.$this->input->post('type'), 'data' => $update);
        }
        else
        {
	        $message = array('status' => 'error', 'message' => 'Oops, we were unable to post your '.$this->input->post('type'));
        }

	    $this->response($message, 200);
    }

    function destroy_delete()
    {		
		// Make sure user has access to do this func
		$access = $this->social_auth->has_access_to_modify('comment', $this->social_tools->get_comment($this->get('id')));
    	
    	if ($access)
        {   
        	$this->social_tools->delete_comment($this->get('id'));
        
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