<?php
class Pages extends Dashboard_Controller
{

	function __construct()
	{
		parent::__construct();	

		// If not Super or Admin redirect
		if ($this->data['level'] >= 3) redirect('home');

		$this->data['page_title'] = 'Pages';
	}	

	function index()
	{
		$this->data['sub_title']	= 'Manage';
		$this->data['pages']		= $this->social_igniter->get_pages($this->config->item('site_id'));		
		$this->render();
	}

	function edit()
	{
		$page = $this->social_igniter->get_page_id($this->uri->segment(3));
		
		// Validation Rules
	   	$this->form_validation->set_rules('title', 'Title', 'required');	
	   	$this->form_validation->set_rules('content', 'Content', 'required');
	   	$this->form_validation->set_rules('details', 'Layout', 'required');
	   	$this->form_validation->set_rules('access', 'Access', 'required');
	   	$this->form_validation->set_rules('comments_allow', 'Comments', 'required');
	
		// Passes Validation
        if ($this->form_validation->run() == true)
        {
        	$page_data = array(
    			'parent_id'			=> $this->input->post('parent_id'),
    			'category_id'		=> $this->input->post('category_id'),
    			'order'				=> $page->order,
    			'layout'			=> $this->input->post('layout'),
    			'title'				=> $this->input->post('title'),
    			'title_url'			=> url_username($this->input->post('title'), 'dash', TRUE),
    			'content'			=> $this->input->post('content'),
    			'details'			=> $this->input->post('details'),
    			'access'			=> $this->input->post('access'),
    			'comments_allow'	=> $this->input->post('comments_allow'),
    			'status'			=> form_submit_publish($this->input->post('publish'), $this->input->post('save_draft'))  			
        	);
        	
			// Update	
		    $this->social_igniter->update_content($page->content_id, $page_data);
		    
		    // Activity
			$info = array(
				'site_id'		=> config_item('site_id'),
				'user_id'		=> $this->session->userdata('user_id'),
				'verb'			=> 'update',
				'type'			=> 'page'
			);
			$data = array(
				'content_id'	=> $page->content_id,
				'title'			=> $this->input->post('title'),
				'url'			=> base_url().'pages/view/'.$page->content_id,
				'description' 	=> character_limiter(strip_tags($this->input->post('content'), ''), 125)
			);
		
			$activity = $this->social_igniter->add_activity($info, $data);		    
		    
		    // Redirect
			redirect(base_url().'pages/edit/'.$page->content_id, 'refresh');
		}
		else
		{	
			$this->data['page']				= $page;
			$this->data['sub_title'] 		= 'Edit: '.$page->title;
			$this->data['layouts']			= $this->social_igniter->scan_layouts($this->config->item('site_theme'));
			
			$this->data['wysiwyg_name']		= 'content';
			$this->data['wysiwyg_id']		= 'wysiwyg';
			$this->data['wysiwyg_class']	= 'wysiwyg_norm_full';
			$this->data['wysiwyg_width']	= 640;
			$this->data['wysiwyg_height']	= 300;
			$this->data['wysiwyg_resize']	= TRUE;
			$this->data['wysiwyg_value']	= $page->content;	
			$this->data['wysiwyg']	 		= $this->load->view($this->config->item('dashboard_theme').'/partials/wysiwyg', $this->data, true);		
			
			$this->render('dashboard_wide');		
		}
	}
	
	function delete()
	{
		echo $this->uri->segment(3);
	
	}

	function create()
	{		
		// Define Variables, Flags, Etc...	
	   	$user_id				= $this->session->userdata('user_id');	
 	
 		// Validation Rules
	   	$this->form_validation->set_rules('title', 'Title', 'required');	
	   	$this->form_validation->set_rules('content', 'Content', 'required');
	   	$this->form_validation->set_rules('details', 'Layout', 'required');
	   	$this->form_validation->set_rules('access', 'Access', 'required');
	   	$this->form_validation->set_rules('comments_allow', 'Comments', 'required');
	
		// Passes Validation
        if ($this->form_validation->run() == true)
        {
        	$page_data = array(
				'parent_id'			=> 0,
				'category_id'		=> 0,
				'module'			=> 'pages',
				'type'				=> 'page',
				'source'			=> '',
				'order'				=> 0,
				'user_id'			=> $this->session->userdata('user_id'),
    			'title'				=> $this->input->post('title'),
    			'title_url'			=> url_username($this->input->post('title'), 'dash', TRUE),
    			'content'			=> $this->input->post('content'),
    			'details'			=> $this->input->post('details'),
    			'access'			=> $this->input->post('access'),
    			'comments_allow'	=> $this->input->post('comments_allow'),
				'geo_lat'			=> $this->input->post('geo_lat'),
				'geo_long'			=> $this->input->post('geo_long'),
				'geo_accuracy'		=> $this->input->post('geo_accuracy'),			
    			'status'			=> form_submit_publish($this->input->post('publish'), $this->input->post('save_draft'))      			        			
        	);		
        									
			// Insert        		
		    $page = $this->social_igniter->add_content($page_data);
		    
		    // Activity
			$info = array(
				'site_id'		=> config_item('site_id'),
				'user_id'		=> $this->session->userdata('user_id'),
				'verb'			=> 'post',
				'type'			=> 'page'
			);
			$data = array(
				'content_id'	=> $page->content_id,
				'title'			=> $this->input->post('title'),
				'url'			=> base_url().'pages/view/'.$page->content_id,
				'description' 	=> character_limiter(strip_tags($this->input->post('content'), ''), 125)
			);
		
			$activity = $this->social_igniter->add_activity($info, $data);			    
		    
		    // Do larger System Hook Actions
			redirect(base_url().'pages/edit/'.$page->content_id);
		}
		else 
		{			 			 				
			//$this->session->set_flashdata('message', validation_errors());

			$this->data['sub_title'] 		= 'Create';
			$this->data['message']			= validation_errors();
			
			$this->data['layouts']			= $this->social_igniter->scan_layouts($this->config->item('site_theme'));
			$this->data['details']			= 'site';
			
			$this->data['wysiwyg_name']		= 'content';
			$this->data['wysiwyg_id']		= 'wysiwyg';
			$this->data['wysiwyg_class']	= 'wysiwyg_norm_full';
			$this->data['wysiwyg_width']	= 640;
			$this->data['wysiwyg_height']	= 300;
			$this->data['wysiwyg_resize']	= TRUE;
			$this->data['wysiwyg_value']	= $this->input->post('content');	
			$this->data['wysiwyg']	 		= $this->load->view($this->config->item('dashboard_theme').'/partials/wysiwyg', $this->data, true);
			
			$this->data['social_post']		= $this->social_igniter->get_social_post($this->session->userdata('user_id'));
			
			$this->render('dashboard_wide');
		}
	}
	
	function users()
	{
		$this->data['sub_title'] 		= 'Users';
	
		$this->render();
	}

}
