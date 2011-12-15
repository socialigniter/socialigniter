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
	        		'content_id'	=> $this->input->post('content_id'),
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
    	if ($category = $this->social_tools->get_category($this->get('id')))
    	{
			// Access Rules
		   	//$this->social_auth->has_access_to_modify($this->input->post('type'), $this->get('id') $this->oauth_user_id);
		   	
	    	$viewed			= 'Y';
	    	$approval		= 'A'; 
	   
	    	$category_data = array(
				'parent_id'			=> $this->input->post('parent_id'),
				'access'			=> $this->input->post('access'),
				'category'			=> $this->input->post('category'),
				'category_url'		=> form_title_url($this->input->post('category'), $this->input->post('category_url'), $category->category_url),
				'description'		=> $this->input->post('description'),
				'details'			=> $this->input->post('details')
	    	);
	    									
			// Update			 		     		
		    if ($update = $this->social_tools->update_category($this->get('id'), $category_data, $this->oauth_user_id))
		    {
	        	$message = array('status' => 'success', 'message' => 'Awesome, we updated your category', 'data' => $update);
	        }
	        else
	        {
		        $message = array('status' => 'error', 'message' => 'Oops, we were unable to save your category update');
	        }
	    }
	    else
	    {
			$message = array('status' => 'error', 'message' => 'Damn that category does not update');    
	    }

	    $this->response($message, 200);
    }
    
    function picture_upload_post()
    {
   		if ($upload = $this->social_tools->get_upload($this->input->post('upload_id')))
    	{
	    	// If File Exists
			if ($upload->file_hash == $this->input->post('file_hash'))
			{	
				// Delete Expectation
				$this->social_tools->delete_upload($this->input->post('upload_id'));

				// Upload Settings
				$create_path				= config_item('categories_images_folder').$this->get('id').'/';
				$config['upload_path'] 		= $create_path;
				$config['allowed_types'] 	= config_item('categories_images_formats');		
				$config['overwrite']		= true;
				$config['max_size']			= config_item('categories_images_max_size');
				$config['max_width']  		= config_item('categories_images_max_dimensions');
				$config['max_height']  		= config_item('categories_images_max_dimensions');
			
				$this->load->helper('file');
				$this->load->library('upload', $config);

				// Delete / Make Folder
				delete_files($create_path);				
				make_folder($create_path);
				
				// Upload
				if (!$this->upload->do_upload('file'))
				{				
					$message = array('status' => 'error', 'message' => 'Ahhhh '.$this->upload->display_errors('', ''));
				}
				else
				{
					// Load Image Model
					$this->load->model('image_model');

					// Upload Data
					$file_data = $this->upload->data();

					// Make Sizes
					$this->image_model->make_images($create_path, $file_data, 'site', array('large', 'medium', 'small'));

					$category = $this->social_tools->get_category($this->get('id'));

					$details = json_decode($category->details);

					$details->thumb = $file_data['file_name'];

			    	$category_data = array(
						'details' => json_encode($details)
			    	);

					// Update
		    		if ($update = $this->social_tools->update_category($this->get('id'), $category_data, $this->oauth_user_id))
				    {
			        	$message = array('status' => 'success', 'message' => 'Awesome we posted your '.$content_data['type'], 'data' => $result['content'], 'activity' => $result['activity']);		    
			        }
			        else
			        {
				        $message = array('status' => 'error', 'message' => 'Oops, uploaded but unable to add image to site');
			        }
				}
			}
			else
			{
				$message = array('status' => 'error', 'message' => 'No image file was sent or the hash was bad');
			}
		}
		else
		{
			$message = array('status' => 'error', 'message' => 'No matching upload token was found');
		}			

	    $this->response($message, 200);    
    }

    function destroy_get()
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