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

			$this->data['content_id']			= $page->content_id;
			$this->data['page_title']			= $page->title;
			$this->data['page_content']			= $page->content;
			$this->data['comments_allow']		= $page->comments_allow;
		}
		else
		{
			redirect(404);
		}					
				
		// Comments
		if ((config_item('comments_enabled') == 'TRUE') && ($page->comments_allow != 'N'))
		{				
			$this->data['comments_view'] = $this->social_tools->make_comments_section($page->content_id, 'page', $this->data['logged_user_id'], $this->data['logged_user_level_id']);
		}	

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
		elseif ($this->uri->segment(1))
		{
			$page = $this->social_igniter->get_page($this->uri->segment(2));
		
			if (!$page)	redirect(404);

			$this->data['content_id']			= $page->content_id;
			$this->data['page_title']			= $page->title;
			$this->data['page_content']			= $page->content;
			$this->data['comments_allow']		= $page->comments_allow;
		}				
		
		// Comments
		if ((config_item('comments_enabled') == 'TRUE') && ($page->comments_allow != 'N'))
		{
			$this->data['comments_view'] = $this->social_tools->make_comments_section($page->content_id, 'page', $this->data['logged_user_id'], $this->data['logged_user_level_id']);
		}		

		$this->render();	
	}	
	
	/* Login Pages */
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
        
        $logout = $this->social_auth->logout();
			    
        redirect($this->session->userdata('previous_page'), 'refresh');
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
	            redirect("login", 'refresh');
			}
			else {
				$this->session->set_flashdata('message', 'The email failed to send, try again.');
	            redirect("forgot_password", 'refresh');
			}
	    }
	    
    	$this->render('wide');
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
    	$this->render('wide');
    }

	function places()
	{
		$this->data['page_title'] = 'Doggy';
		$this->render();
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
    	$this->load->view('site_default/partials/webfinger', $this->data);
    }

    function webfinger_user(){
    	$uri = $this->uri->segment(2);
    	preg_match('/@/', $uri, $matches);
    	if ($matches){
    		preg_match('/(acct:|^)(.*?)@/',$uri, $matches);
    		$username = $matches[2];
    		$this->data['uri'] = $uri;
	    	$this->data['username'] = $username;
	    	$user = $this->social_auth->get_user('username', $username); 
			if ($user)
			{
			  $connections = $this->social_auth->get_connections_user($user->user_id); 		
			}
			foreach($connections as $connection)
			{
				//var_dump($connection);
				if ($connection->module == "twitter"){
					$screen_name = $connection->connection_username;
				}
			}
			//var_dump($screen_name);
			if(isset($screen_name)){
				$this->data['screen_name'] = $screen_name;
			}
	    	$this->load->view('site_default/partials/webfinger_user', $this->data);
		}
		else {
			$this->error_404();
		}
    }    
    function test_me()
 	{
 	    $this->load->library('simplepie');
 	    $simple = new SimplePie();
 	    $feedurl = "http://social.pdxbrain.com/profile/tyler/feed";
 	    $simple->set_feed_url($feedurl);
 	    $simple->init();
 	    $simple->handle_content_type();
 	    //echo $simple->get_author();
 	    $items = $simple->get_items();
 	    foreach($items as $item){
 	        echo $item->get_description()."<br>";
 	        echo $item->get_id()."<br>";
 	    }
 	    //var_dump($simple);
 	    /*
 	    $this->load->library('webfinger');
        /*
        $id = "tyler@social.pdxbrain.com";
        $webfinger = $this->webfinger->webfinger_find_by_email($id);
        var_dump($webfinger);
        $name = $webfinger['webfinger']['display_name'];
        $photo = $webfinger['webfinger']['portrait_url'];
        if (preg_match('/https:\/\/profiles.google.com\/\/(.*?)$/',$photo, $matches)){
         //var_dump($matches);
         $photo = 'http://' . $matches[1];
        }
        echo "Webfinger: $id, Full Name: $name <img src=$photo>";
        //var_dump($webfinger);
        */
        echo 'test';
        
    } 	

}