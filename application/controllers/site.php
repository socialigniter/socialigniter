<?php
class Site extends Site_Controller
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

			$this->data['content_id']		= $page->content_id;
			$this->data['page_title']		= $page->title;
			$this->data['page_content']		= $page->content;
			$this->data['comments_allow']	= $page->comments_allow;
		}
		else
		{
			redirect(404);
		}					
		
		// Comments Widget
		if ((config_item('comments_enabled') == 'TRUE') && ($page->comments_allow != 'N'))
		{
			// Get Comments
			$comments 						= $this->social_tools->get_comments_content($page->content_id);
			$comments_count					= $this->social_tools->get_comments_content_count($page->content_id);
			
			if ($comments_count)	$comments_title = $comments_count;
			else					$comments_title = 'Write';

			$this->data['comments_title']	= $comments_title;
			$this->data['comments_list'] 	= $this->social_tools->render_comments_children($comments, '0', $this->data['logged_user_id'], $this->data['logged_user_level_id']);

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

		// Load Login Is Enabled
		$this->data['sidebar'] = $this->render_widgets('sidebar');		

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
	
	function pages()
	{
		if (($this->uri->segment(2) == 'view'))
		{
			$page = $this->social_igniter->get_page_id($this->uri->segment(3));
			
			if ($page->details == 'index')	redirect();
			else							redirect(base_url().'pages/'.$page->title_url, 'refresh');
		}
		// Pages
		elseif ($this->uri->segment(1))
		{
			$page = $this->social_igniter->get_page($this->uri->segment(2));
		
			if (!$page)	redirect(404);

			$this->data['content_id']		= $page->content_id;
			$this->data['page_title']		= $page->title;
			$this->data['page_content']		= $page->content;
			$this->data['comments_allow']	= $page->comments_allow;
		}				
		
		// Comments Widget
		if ((config_item('comments_enabled') == 'TRUE') && ($page->comments_allow != 'N'))
		{
			// Get Comments
			$comments 						= $this->social_tools->get_comments_content($page->content_id);
			$comments_count					= $this->social_tools->get_comments_content_count($page->content_id);
			
			if ($comments_count)	$comments_title = $comments_count;
			else					$comments_title = 'Write';

			$this->data['comments_title']	= $comments_title;
			$this->data['comments_list'] 	= $this->social_tools->render_comments_children($comments, '0');

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

		// Load Login Is Enabled
		if (config_item('users_login') == 'TRUE')
		{
			$this->data['sidebar'] .= $this->load->view(config_item('site_theme').'/partials/widget_login', $this->data, true);	
		}

		$this->render();	
	}
	
	
	/* Login Pages */
    function login() 
    {
    	if ($this->social_auth->logged_in()) redirect(base_url()."home", 'refresh');
    	if (config_item('users_login') == 'FALSE') redirect(base_url(), 'refresh');
    	        
        // Validate form input
    	$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
	    $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == true)
        {	
        	// Check "remember me"
        	if ($this->input->post('remember') == 1)
        	{
        		$remember = TRUE;
        	}
        	else
        	{
        		$remember = FALSE;
        	}
        	
        	// Attempt Login
        	if ($this->social_auth->login($this->input->post('email'), $this->input->post('password'), $remember))
        	{
	        	$this->session->set_flashdata('message', "Logged In Successfully");
	        	redirect(base_url().'home', 'refresh');
	        }
	        else
	        {
	        	$this->session->set_flashdata('message', "Login In-Correct");
	        	redirect("login", 'refresh');
	        }
        }
		else
		{
			// The user is not logging in so display the login page	    
	        $this->data['message'] 			= (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->data['email']      		= $this->input->post('email');
            $this->data['password']   		= "";
        	$this->data['password_confirm'] = "";
	        $this->data['page_title'] 		= "Login";	            	
		}
		
    	$this->render();
    }
    
	function logout() 
	{
        $this->data['page_title'] = "Logout";
        
        $logout = $this->social_auth->logout();
			    
        redirect(base_url(), 'refresh');
    }
    
    function signup()
    {
    	if ($this->social_auth->logged_in()) redirect(base_url()."home", 'refresh'); 
                  
        // Display The Create User Form
		$this->data['name']      		= "";			    
		$this->data['email']      		= "";
        $this->data['password']   		= "";
    	$this->data['password_confirm'] = "";    		
        $this->data['page_title'] 		= "Signup";
    	$this->render();
    }  
    
    function signup_social()
    {
    	if ($this->session->userdata('signup_user_state') != 'has_connection_data') redirect('signup', 'refresh');

		$this->data['sub_title'] 		= 'Signup';
		$this->data['signup_module']	= $this->session->userdata('connection_signup_module');
		$this->data['name']				= $this->session->userdata('signup_name');
		$this->data['signup_email']		= $this->session->userdata('social_email');
		$this->data['return_url']		= $this->session->userdata('connection_return_url');
		$this->render();  
    }  

	function forgot_password() 
	{	
		$this->form_validation->set_rules('email', 'Email Address', 'required');
		
	    if ($this->form_validation->run() == false)
	    {	
        	$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');    		
	    }
	    else
	    {	
			if ($forgotten = $this->social_auth->forgotten_password($this->input->post('email')))
			{
				$this->session->set_flashdata('message', 'An email has been sent, please check your inbox.');
	            redirect("login", 'refresh');
			}
			else {
				$this->session->set_flashdata('message', 'The email failed to send, try again.');
	            redirect("forgot_password", 'refresh');
			}
	    }
	    
    	$this->render();
	}
	
	function reset_password() 
	{
		if (!$this->uri->segment(2)) redirect(base_url().'forgot_password');
	
		$reset = $this->social_auth->forgotten_password_complete($this->uri->segment(2));
		
		if ($reset)
		{
			$this->session->set_flashdata('message', 'An email has been sent with your new password, check your inbox.');
            redirect("login", 'refresh');
		}
		else
		{
			$this->session->set_flashdata('message', 'The email failed to send, try again.');
            redirect("forgot_password", 'refresh');
		}
	}    

	// Activate the user. This URL is hit by email as theere site links to it
	function activate() 
	{  
		if ((!$this->uri->segment(2)) OR (!$this->uri->segment(3))) redirect(base_url().'forgot_password');	
	      
		$activation = $this->social_auth->activate($this->uri->segment(2), $this->uri->segment(3));
		
        if ($activation)
        {
	        $this->session->set_flashdata('message', "Account Activated");
	        redirect("login", 'refresh');
        }
        else
        {
	        $this->session->set_flashdata('message', "Unable to Activate");
	        redirect("forgot_password", 'refresh');
        }
    }
    
    function activation()
    {    
	    $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
    	$this->render();
    }
    
    // Page Not Found
    function error_404()
    {
		$this->data['page_title'] = 'Oops, Page Not Found';
    	$this->render();
    }
    
    
    /* Widgets */
	function widgets_sidebar()
	{
		if ($this->uri->segment(1) != 'login')
		{
			$this->load->view('partials/widget_sidebar', $this->data);
		}
	
	}
}