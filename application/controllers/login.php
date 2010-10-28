<?php
class Login extends Public_Controller {
 
    function __construct() 
    {
        parent::__construct();
    }
   
    function index() 
    {
    	if ($this->social_auth->logged_in()) redirect(base_url()."home", 'refresh');
    	if (config_item('users_login') == 'FALSE') redirect(base_url(), 'refresh');
    	        
        // Validate form input
    	$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
	    $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == true) { //check to see if the user is logging in
        	
        	// Check "remember me"
        	if ($this->input->post('remember') == 1) {
        		$remember = TRUE;
        	}
        	else {
        		$remember = FALSE;
        	}
        	
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
			$this->data['name']      		= "";			    
			$this->data['email']      		= "";
            $this->data['password']   		= "";
        	$this->data['password_confirm'] = "";
	        $this->data['page_title'] 			= "Login";
	            	
    		$this->render();
		}

    }
    
	function logout() 
	{
        $this->data['page_title'] = "Logout";
        
        $logout = $this->social_auth->logout();
			    
        redirect(base_url(), 'refresh');
    }

	function forgot_password() 
	{
		$this->form_validation->set_rules('email', 'Email Address', 'required');
	    if ($this->form_validation->run() == false)
	    {	
	    	// Setup the input
	    	$this->data['email'] = array('name' => 'email', 'id'      => 'email');
	    	
	    	// Set any errors and display the form
        	$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
    		
    		$this->render();
	    }
	    else
	    {
	        // Run the forgotten password method to email an activation code to the user
			$forgotten = $this->social_auth->forgotten_password($this->input->post('email'));
			
			if ($forgotten)
			{
				$this->session->set_flashdata('message', 'An email has been sent, please check your inbox.');

	            //we should display a confirmation page here instead of the login page	            
	            redirect("login", 'refresh');
			}
			else {
				$this->session->set_flashdata('message', 'The email failed to send, try again.');
	            redirect("login/forgot_password", 'refresh');
			}
	    }
	}
	
	function reset_password($code) 
	{
		$reset = $this->social_auth->forgotten_password_complete($code);
		
		if ($reset)
		{
			$this->session->set_flashdata('message', 'An email has been sent with your new password, check your inbox.');
            redirect("login", 'refresh');
		}
		else
		{
			$this->session->set_flashdata('message', 'The email failed to send, try again.');
            redirect("login/forgot_password", 'refresh');
		}
	}
    
    function signup()
    {
    	if ($this->social_auth->logged_in()) redirect(base_url()."home", 'refresh'); 
    	if (config_item('users_signup') == 'FALSE') redirect(base_url(), 'refresh');    	    
    
        $this->data['page_title'] = "Signup";
              
        // Validation Rules
    	$this->form_validation->set_rules('name', 'Name', 'required');
    	$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
    	$this->form_validation->set_rules('password', 'Password', 'required|min_length['.$this->config->item('min_password_length').']|max_length['.$this->config->item('max_password_length').']|strong_pass['.$this->config->item('password_strength').']|matches[password_confirm]');
    	$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');

		// Validates
        if ($this->form_validation->run() == true)
        {
			$username			= url_username($this->input->post('name'), 'none', true);
        	$email				= $this->input->post('email');
        	$password			= $this->input->post('password');
        	$additional_data 	= array('name' => $this->input->post('name'));
			$level				= config_item('default_group');
        	
        	// Register User
        	if ($this->social_auth->register($username, $password, $email, $additional_data, $level))
        	{
        		$this->session->set_flashdata('message', "User Created");
       			
       			if (!$this->config->item('email_activation'))
       			{
	        		$this->social_auth->login($email, $password, $remember=FALSE);
		        	$this->session->set_flashdata('message', "Logged In Success");
		        	redirect(base_url().'home', 'refresh');
		        }
		        else
		        {
		        	$this->session->set_flashdata('message', "You should be receiving an email shortly with a link to activate your account");
		        	redirect('login/activation', 'refresh');
		        }		              			
       		}
       		else
       		{
        		$this->session->set_flashdata('message', "Error Creating User");
       			redirect("login/signup", 'refresh');       		
       		}
		} 
		else
		{ 
	        // Display The Create User Form
	        $this->data['message'] 			= (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->data['name']      		= "";			    
			$this->data['email']      		= "";
            $this->data['password']   		= "";
        	$this->data['password_confirm'] = "";    		
    		$this->render();
		}
    }

    function signup_ajax()
    {
    	if ($this->social_auth->logged_in()) redirect(base_url()."home", 'refresh');     
        
        $this->data['page_title'] = "Signup";
              
        //validate form input
    	$this->form_validation->set_rules('name', 'Name', 'required');
    	$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
    	$this->form_validation->set_rules('password', 'Password', 'required|min_length['.$this->config->item('min_password_length').']|max_length['.$this->config->item('max_password_length').']|strong_pass['.$this->config->item('password_strength').']|matches[password_confirm]');

        if ($this->form_validation->run() == true) { //check to see if we are creating the user
			$username	= url_username($this->input->post('name'), 'none', true);
        	$email		= $this->input->post('email');
        	$password	= $this->input->post('password');
			$level		= "user";

        	$additional_data = array('name' => $this->input->post('name'));
        	
        	// Register the user
        	if ($this->social_auth->register($username, $password, $email, $additional_data, $level))
        	{
        		$this->session->set_flashdata('message', "User Created");
       			
       			if (!$this->config->item('email_activation'))
       			{
	        		$this->social_auth->login($email, $password, $remember=FALSE);
		        	$this->session->set_flashdata('message', "Logged In Success");
		        	redirect(base_url().'home', 'refresh');
		        }
		        else
		        {
		        	$this->session->set_flashdata('message', "You should be receiving an email shortly with a link to activate your account");
		        	redirect('login/activation', 'refresh');
		        }		              			
       		}
       		else
       		{
        		$this->session->set_flashdata('message', "Error Creating User");
       			redirect("login/signup", 'refresh');       		
       		}
		} 
		else
		{ 
	        // Display the create user form & set the flash data error message if there is one
			$this->data['name']      		= $this->input->post('name');
			$this->data['email']      		= $this->input->post('email');
            $this->data['password']   		= $this->input->post('password');
		}

    	$this->load->view(config_item('site_theme').'/login/signup_ajax', $this->data);

    }

	// Activate the user. This URL is hit by email as theere site links to it
	function activate($user_id=FALSE, $code=FALSE) 
	{        
		$activation = $this->social_auth->activate($user_id, $code);
		
        if ($activation)
        {
	        $this->session->set_flashdata('message', "Account Activated");
	        redirect("login", 'refresh');
        }
        else
        {
	        $this->session->set_flashdata('message', "Unable to Activate");
	        redirect("login/forgot_password", 'refresh');
        }
    }
    
    function activation()
    {    
	    $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
    	$this->render();
    }
    
}
