<?php

class Setup extends MX_Controller {

    function __construct() {
       parent::__construct();    
    }

    function index() {

			$this->load->dbutil();	    
	    
	    // Check DB    
	    if ($this->dbutil->database_exists($this->input->post('database'))) {		   		
		    $this->load->config('install');
		    $this->load->database();	
		    $this->load->dbforge();
		    $this->load->library('migration');
   		
   			// Create Database Tables
				$this->migration->current();

				// Users Level Data
				$this->db->query("INSERT INTO `users_level` VALUES(1, 'superadmin', 'Super Admin', 'Super Admins are the head honchos who have power to do anything they want on your install of Social Igniter')");
				$this->db->query("INSERT INTO `users_level` VALUES(2, 'admin', 'Admin', 'Admins can do most things, not all, but most things needed on a site')");
				$this->db->query("INSERT INTO `users_level` VALUES(3, 'superuser', 'Super User', 'Supers Users help keep the ship on course, they do some things, but not all')");		
				$this->db->query("INSERT INTO `users_level` VALUES(4, 'user', 'User', 'Users are just regular Joes or Joesephines. They use your application as it is intended for the general public')");

				// Load Libraries (doing this before DB is created causes mean errors )
				$this->load->library('session');
				$this->load->library('social_igniter');
				$this->load->library('social_auth');
				$this->load->library('installer');

				// Settings	Data
				$this->installer->install_settings('site', config_item('site_settings'));
    		$this->installer->install_settings('site', array('url' => $this->input->post('base_url')));
				$this->installer->install_settings('design', config_item('design_settings'));
				$this->installer->install_settings('themes', config_item('themes_settings'));
				$this->installer->install_settings('services', config_item('services_settings'));
				$this->installer->install_settings('home', config_item('home_settings'));
				$this->installer->install_settings('users', config_item('users_settings'));
			
				// Output
		    $output = json_encode(array('status' => 'success', 'message' => 'Created database and settings installed'));			
			
			} else {
		  
		    $output = json_encode(array('status' => 'error', 'message' => 'Oops your database does not exist for some reason'));			
			
			}
				
			echo $output;
    
    }


    function admin() {
    	// Check If Superadmin Exists
			// Load Libraries (doing this before DB is created causes mean errors )
	    $this->load->config('install');
	    $this->load->database();
			$this->load->library('session');
			$this->load->library('social_igniter');
			$this->load->library('social_auth');
			$this->load->library('installer');   	

			// Validation
			$username			= url_username($this->input->post('name'), 'none', true);
			$email				= $this->input->post('email');
			$password			= $this->input->post('password');
			$additional_data 	= array(
				'phone_number'	=> '',
				'name'			=> $this->input->post('name'),
				'image'			=> '',
				'language'		=> $this->input->post('language')	    		
			);
		
			if ($user = $this->social_auth->register($username, $password, $email, $additional_data, config_item('super_admin_group')))
		{
		    // Add Admin To Settings
		    $this->installer->install_settings('site', array('admin_email' => $email));
		
		    // Send Emails
			$data = array(
				'name'	   => $user->name,
				'username' => $user->username,
				'email'    => $user->email
			);
		
			/*
			// If Activation Email
			if (config_item('email_activation') == false)
			{
				$message = $this->load->view(config_item('email_templates').config_item('email_signup'), $data, true);
		
				$this->email->from(config_item('site_admin_email'), config_item('site_title'));
				$this->email->to($user->email);
				$this->email->subject(config_item('site_title').' thanks you for signing up');
				$this->email->message($message);
				$this->email->send();
			}
			else
			{		
				$data = array(
					'email'   	 => $user->email,
					'user_id'    => $user->user_id,
					'email'      => $user->email,
					'activation' => $user->activation_code,
				);
		        
				$message = $this->load->view(config_item('email_templates').config_item('email_activate'), $data, true);
		
				$this->email->from(config_item('site_admin_email'), config_item('site_title'));
				$this->email->to($user->email);
				$this->email->subject(config_item('site_title') . ' - Account Activation');
				$this->email->message($message);
				$this->email->send();
			}
			*/
		
			// Check "remember me"
			if ($this->input->post('remember') == 1) $remember = TRUE;
			else $remember = FALSE;
			
			// Store Session Data
			if ($this->input->post('session') == 1) $session = TRUE;
			else $session = FALSE;
			
			// Login User
			if ($this->social_auth->login($user->email, $this->input->post('password'), $remember, $session))
			{
				// Get User Data
				$meta 		 = $this->social_auth->get_user_meta($user->user_id);
				$user->image = $this->social_igniter->profile_image($user->user_id, $user->image, $user->gravatar);
			
		        $message = array('status' => 'success', 'message' => 'Success you will now be logged in', 'user' => $user, 'meta' => $meta);
		    }
		    else
		    {
		        $message = array('status' => 'error', 'message' => 'Oops could not log you in');
		    }				
			}
			else
			{
		    $message = array('status' => 'error', 'message' => 'Oops could not create user');
			}     

		
		echo json_encode($message);
	}
	

    function site() {
	    $this->load->config('install');
	    $this->load->database();
		$this->load->library('session');
		$this->load->library('social_igniter');
		$this->load->library('social_auth');
		$this->load->library('installer');    
    
		// Sites Data
		$this->installer->install_sites(array(array(
			'url'		=> $this->input->post('base_url'), 
			'module'	=> 'site', 
			'type' 		=> 'default', 
			'title'		=> 'Twitter', 
			'favicon'	=> $this->input->post('base_url').'favicon.ico'
		)));

		$site_settings = array(
			'title' 		=> $this->input->post('title'),
			'tagline' 		=> $this->input->post('tagline'),
			'keywords' 		=> $this->input->post('keywords'),
			'description' 	=> $this->input->post('description')
		);

		$this->installer->install_settings('site', $site_settings);
	    
    }	
	
}