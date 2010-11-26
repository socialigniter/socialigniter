<?php
class Index extends Public_Controller 
{ 
    function __construct() 
    {
        parent::__construct();        
    }
    
	function index() 
	{
		if (!$this->uri->segment(1))
		{
			$page = $this->social_igniter->get_index_page();
		}
		elseif ($this->uri->segment(1) == 'pages')
		{
			$page = $this->social_igniter->get_page($this->uri->segment(2));
		}

		$this->data['content_id']			= $page->content_id;		
		$this->data['page'] 				= $page;
		
		if ($page->type != 'index')
		{
			$this->data['page_title'] 		= $page->title;
		}
		
		// Comments
		if ((config_item('comments_enabled') == 'TRUE') && ($page->comments_allow != 'N'))
		{
			// Get Comments
			$comments 						= $this->social_tools->get_comments_content($page->content_id);		
			$comments_count					= $this->social_tools->get_comments_content_count($page->content_id);
			
			if ($comments_count)	$comments_title = $comments_count;
			else					$comments_title = 'Write';
			
			$this->data['comments_title']	= $comments_title;			
			$this->data['comments_list'] 	= $this->social_tools->render_children_comments($comments, '0');

			// Write
			$this->data['comment_name']			= $this->session->flashdata('comment_name');
			$this->data['comment_email']		= $this->session->flashdata('comment_email');
			$this->data['comment_write_text'] 	= $this->session->flashdata('comment_write_text');
			$this->data['reply_to_id']			= $this->session->flashdata('reply_to_id');
			$this->data['comment_type']			= 'page';
			$this->data['geo_lat']				= $this->session->flashdata('geo_lat');
			$this->data['geo_long']				= $this->session->flashdata('geo_long');
			$this->data['geo_accuracy']			= $this->session->flashdata('geo_accuracy');
			$this->data['comment_error']		= $this->session->flashdata('comment_error');
			
			// ReCAPTCHA Enabled
			if ((config_item('comments_recaptcha') == 'TRUE') && (!$this->social_auth->logged_in()))
			{			
				$this->load->library('recaptcha');
				$this->data['recaptcha']		= $this->recaptcha->get_html();
			}
			else
			{
				$this->data['recaptcha']		= '';
			}
			
			$this->data['comments_write']		= $this->load->view(config_item('site_theme').'/partials/comments_write', $this->data, true);
		}

		$this->data['sidebar'] 	.= modules::run('blog/widgets_sidebar');
		$this->data['sidebar']	.= modules::run('events/widgets_sidebar');		
		
		$this->render();
	}
	

	function view()
	{
		$page 			= $this->social_igniter->get_content($this->uri->segment(3));
		$page_link		= base_url().'pages/'.$page->title_url;
		$page_comment	= NULL;
		
		if ($this->uri->segment(4))
		{
			$page_comment = '#comment-'.$this->uri->segment(4);
		}
		
		redirect($page_link.$page_comment);			
	}		

}