<?php
class Pages extends Dashboard_Controller
{
	function __construct()
	{
		parent::__construct();	

		// If not Super or Admin redirect
		if ($this->data['logged_user_level_id'] >= 3) redirect('home');

		$this->data['page_title'] = 'Pages';
	}
	
	function index()
	{
		redirect('home');	
	}

	function editor()
	{				
		if (($this->uri->segment(3) == 'manage') && ($this->uri->segment(4)))
		{
			// Need is valid & access and such
			$page = $this->social_igniter->get_content($this->uri->segment(4));
							
			// Non Form Fields
			$this->data['sub_title']		= $page->title;
			$this->data['form_url']			= base_url().'api/content/modify/id/'.$this->uri->segment(4);
			
			// Form Fields
			$this->data['title'] 			= $page->title;
			$this->data['title_url'] 		= $page->title_url;
			$this->data['wysiwyg_value']	= $page->content;
			$this->data['category_id']		= $page->category_id;
			$this->data['details'] 			= $page->details;
			$this->data['access']			= $page->access;
			$this->data['comments_allow']	= $page->comments_allow;
			$this->data['status']			= $page->status;
		}
		else
		{		
			// Non Form Fields
			$this->data['sub_title']		= 'Write';
			$this->data['form_url']			= base_url().'api/content/create';
			
			// Form Fields
			$this->data['title'] 			= '';
			$this->data['title_url']		= '';
			$this->data['layouts']			= '';
			$this->data['wysiwyg_value']	= $this->input->post('content');
			$this->data['category_id']		= '';
			$this->data['details'] 			= '';			
			$this->data['access']			= 'E';
			$this->data['comments_allow']	= '';
			$this->data['status']			= 'U';
		}
	
		$this->data['layouts']			= $this->social_igniter->scan_layouts(config_item('site_theme'));

		$this->data['wysiwyg_name']		= 'content';
		$this->data['wysiwyg_id']		= 'wysiwyg_content';
		$this->data['wysiwyg_class']	= 'wysiwyg_norm_full';
		$this->data['wysiwyg_width']	= 640;
		$this->data['wysiwyg_height']	= 300;
		$this->data['wysiwyg_resize']	= TRUE;
		$this->data['wysiwyg_media']	= TRUE;			
		$this->data['wysiwyg']	 		= $this->load->view($this->config->item('dashboard_theme').'/partials/wysiwyg', $this->data, true);
		$this->data['categories'] 		= $this->social_tools->get_categories_dropdown('module', 'pages', $this->session->userdata('user_id'), $this->session->userdata('user_level_id'));
							
 		$this->render('dashboard_wide');
	}
	
}
