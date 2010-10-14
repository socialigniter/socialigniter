<?php
class Media_manager extends Dashboard_Controller 
{
 
    function __construct() 
    {
        parent::__construct();
    }
 
 	function index()
 	{ 	

	}

	function create()
	{
		// Load Models, Do Queries, Libraries, Etc.
		$this->data['categories']		= $this->social_tools->get_categories_type('blog');				
		
		// Define Variables, Flags, Etc...	
	   	$user_id				= $this->session->userdata('user_id');	
 	
 		// Validation Rules
	   	$this->form_validation->set_rules('title', 'Title', 'required');	
	   	$this->form_validation->set_rules('body', 'Post', 'required');
	   	$this->form_validation->set_rules('comments', 'Comments', 'required');
	
		// Passes Validation
        if ($this->form_validation->run() == true)
        {

        	$post_data = array(
        			'title'			=> $this->input->post('title'),
        			'title_url'		=> url_username($this->input->post('title'), 'dash', TRUE),    
        			'body'			=> $this->input->post('body'),
        			'comments'		=> $this->input->post('comments'),
        			);		
        									
			// Insert        		
		    $post = $this->blog_igniter->add_post($this->session->userdata('user_id'), $post_data);
		    
		    // Do larger System Hook Actions
			redirect(base_url().'home/blog/posted/'.$post->post_id);
				
		}
		// Does Not Pass Validation
		else 
		{			 			 				
			$this->data['sub_title']		= 'Write';
	        $this->data['message'] 			= $this->session->flashdata('message');

			$this->data['page_title']			= $this->input->post('title');			
			$this->data['comments']			= $this->input->post('comments');
					
								
	 		$this->render();
		}

	}

	
}
