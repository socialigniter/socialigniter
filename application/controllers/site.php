<?php
class Site extends Site_Controller
{ 
    function __construct()
    {
        parent::__construct();    
    }

	function index()
	{
		// Is Pages Module Installed
		if (config_item('pages_enabled') == 'TRUE')
		{
			$this->load->model('pages/pages_model');

			$page = $this->pages_model->get_index_page();

			$this->data['content_id']			= $page->content_id;
			$this->data['page_content']			= $page->content;
		}
		// If not show normal site tags
		else
		{
			$this->data['content_id']			= 0;
			$this->data['page_content']			= config_item('site_description');
		}

		$this->render();
	}
	
	/* Authentication Static Pages */
    function login() 
    {
    	// Logged In or Disabled
    	if ($this->social_auth->logged_in()) redirect(base_url()."home", 'refresh');
    	if (config_item('users_login') == 'FALSE') redirect(base_url(), 'refresh');    	        

		$this->data['email']      		= '';
        $this->data['password']   		= "";
    	$this->data['password_confirm'] = "";
        $this->data['page_title'] 		= "Login";	            	
		
    	$this->render('wide');
    }

	function logout() 
	{
        $this->data['page_title'] = "Logout";
        
        $this->social_auth->logout();
		
        redirect();
    }
    
    function signup()
    {
    	// Logged In or Disabled 
    	if ($this->social_auth->logged_in()) redirect(base_url()."home", 'refresh'); 
     	if (config_item('users_signup') == 'FALSE') redirect(base_url(), 'refresh');
                  
        // Display The Create User Form
		$this->data['name']      		= "";			    
		$this->data['email']      		= "";
        $this->data['password']   		= "";
    	$this->data['password_confirm'] = "";    		
        $this->data['page_title'] 		= "Signup";
        
    	$this->render('wide');
    }  
    
    function signup_social()
    {
    	if ($this->session->userdata('signup_user_state') != 'has_connection_data') redirect('signup', 'refresh');

		$this->data['sub_title'] 		= 'Signup';
		$this->data['signup_module']	= $this->session->userdata('connection_signup_module');
		$this->data['name']				= $this->session->userdata('signup_name');
		$this->data['signup_email']		= $this->session->userdata('social_email');
		$this->data['return_url']		= $this->session->userdata('connection_return_url');
    	
    	$this->render('wide');
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
	            redirect("login");
			}
			else {
				$this->session->set_flashdata('message', 'The email failed to send, try again.');
	            redirect("forgot_password");
			}
	    }
	    
    	$this->render('wide');
	}
	
	function reset_password() 
	{
		// Has URL Code
		if (!$this->uri->segment(2)) redirect(base_url().'forgot_password');
		
		if ($user = $this->social_auth->get_user('forgotten_password_code', $this->uri->segment(2), TRUE))
		{
			// Reset Password
			if ($new_password = $this->social_auth->forgotten_password_complete($user))
			{
				// Config Email	
				$this->load->library('email');
				
				$config_email['protocol']  	= config_item('services_email_protocol');
				$config_email['mailtype']  	= 'html';
				$config_email['charset']  	= 'UTF-8';
				$config_email['crlf']		= '\r\n';
				$config_email['newline'] 	= '\r\n'; 			
				$config_email['wordwrap']  	= FALSE;
				$config_email['validate']	= TRUE;
				$config_email['priority']	= 1;
					
				if (config_item('services_email_protocol') == 'smtp')
				{			
					$config_email['smtp_host'] 	= config_item('services_smtp_host');
					$config_email['smtp_user'] 	= config_item('services_smtp_user');
					$config_email['smtp_pass'] 	= config_item('services_smtp_pass');
					$config_email['smtp_port'] 	= config_item('services_smtp_port');
				}
	
				$this->email->initialize($config_email);
	
				$data = array(
					'email' 		=> $user->email, 
					'new_password'	=> $new_password
				);
	
				$message = $this->load->view(config_item('email_templates').config_item('email_forgot_password_complete'), $data, true);
	
				$this->email->from(config_item('site_admin_email'), config_item('site_title'));
				$this->email->to($user->email);
				$this->email->subject(config_item('site_title') . ' - New Password');
				$this->email->message($message);
	
				if ($this->email->send())
				{
					$this->session->set_flashdata('message', 'An email has been sent with your new password, check your inbox.');
		            redirect("login");
				}
				else
				{
					$this->session->set_flashdata('message', 'The email failed to send, try again.');
				}
			}
			else
			{
				$this->session->set_flashdata('message', 'Shoot, we could not reset your password. Please try again.');
			}
		}
		else
		{
			$this->session->set_flashdata('message', 'Shoot, we could not reset your password. Please try again.');
		}

		redirect("forgot_password");
	}

	// Activate the User
	function activate()
	{
		if ((!$this->uri->segment(2)) OR (!$this->uri->segment(3))) redirect(base_url().'forgot_password');	
	      
		$activation = $this->social_auth->activate($this->uri->segment(2), $this->uri->segment(3));
		
        if ($activation)
        {
	        $this->session->set_flashdata('message', "Account Activated");
	        redirect("login");
        }
        else
        {
	        $this->session->set_flashdata('message', "Unable to Activate");
	        redirect("forgot_password");
        }
    }
    
    function activation()
    {    
	    $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
    	$this->render('wide');
    }

	function tags() 
	{	
		$this->data['page_title']	= "Tags";
		$this->data['tags'] 		= $this->social_tools->get_tags('type', 'all', 5);

		$this->render('wide');
	}
	
	function tags_search()
	{
		$this->data['page_title']	= 'Tags';
		$this->data['sub_title']	= $this->uri->segment(2);
		$this->data['tag']			= $this->uri->segment(2);

		$this->render('wide');
	}

    // Page Not Found
    function error_404()
    {
		$this->data['page_title'] = 'Oops, Page Not Found';
    	$this->render('wide');
    }

    /* Webfinger */
    function webfinger(){
    	$this->data['this'] = $this;
    	$this->load->view(config_item('site_theme').'/partials/webfinger', $this->data);
    }

    function webfinger_user()
    {
    	$uri = $this->uri->segment(2);
    	preg_match('/@/', $uri, $matches);
    	
    	if ($matches)
    	{
    		preg_match('/(acct:|^)(.*?)@/',$uri, $matches);
    		$username = $matches[2];
    		$this->data['uri'] = $uri;
	    	$this->data['username'] = $username;
	    	$user = $this->social_auth->get_user('username', $username); 
			
			if ($user)
			{
				$connections = $this->social_auth->get_connections_user($user->user_id); 		
			}
			
			foreach ($connections as $connection)
			{
				if ($connection->module == "twitter")
				{
					$screen_name = $connection->connection_username;
				}
				elseif ($connection->module == "facebook")
				{
					$screen_name = $connection->connection_username;
				}
			}
			
			if (isset($screen_name))
			{
				$this->data['screen_name'] = $screen_name;
			}

	    	$this->load->view(config_item('site_theme').'/partials/webfinger_user', $this->data);
		}
		else
		{
			$this->error_404();
		}
    }
    
    /* Widgets */
	function widgets_tag_cloud($widget_data)
	{
		$widget_data['tags'] = $this->social_tools->get_tags('type', 'all', 5);
		
		$this->load->view(config_item('site_theme').'/widgets/tag_cloud', $widget_data);
	}

}