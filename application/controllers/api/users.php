<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* 
* Social-Igniter : Core : Users : API Controller
*
* @package		Social Igniter
* @subpackage	Social Igniter Library
* @author		Brennan Novak
* @link			http://social-igniter.com
* 
*/
class Users extends Oauth_Controller
{
    function __construct()
    {
        parent::__construct();

    	$this->form_validation->set_error_delimiters('', '');
    	
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
	}
    
    function recent_authd_get()
    {    
        $users = $this->social_auth->get_users('active', 1);
        
        if($users)
        {
            $message = array('status' => 'success', 'message' => '1 - 10 recent users', 'data' => $users);
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'Oops could not find any users');
        }
        
        $this->response($message, 200);        
    }

	function find_authd_get()
    {
    	$search_by	= $this->uri->segment(4);
    	$search_for	= $this->uri->segment(5);
        $user		= $this->social_auth->get_user($search_by, $search_for, FALSE);    	
        
        if ($user)
        {
            $message = array('status' => 'success', 'message' => 'User found', 'data' => $user, 'meta' => $this->social_auth->get_user_meta($user->user_id));
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'User could not be found');
        }

        $this->response($message, 200);
    }

    function create_post()
    {
    	$this->form_validation->set_rules('name', 'Name', 'required');
    	$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
    	$this->form_validation->set_rules('password', 'Password', 'required|min_length['.config_item('min_password_length').']|max_length['.$this->config->item('max_password_length').']|strong_pass['.config_item('password_strength').']|matches[password_confirm]');
    	$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');

        if ($this->form_validation->run() == true)
        {
			$username			= url_username($this->input->post('name'), 'none', true);
	    	$email				= $this->input->post('email');
	    	$password			= $this->input->post('password');
	    	$additional_data 	= array(
	    		'phone_number'	=> $this->input->post('phone_number'),
	    		'name'			=> $this->input->post('name'),
	    		'image'			=> '',
	    		'language'		=> $this->input->post('language')
	    	);
	    	        	
	    	if ($user = $this->social_auth->register($username, $password, $email, $additional_data, config_item('default_group')))
	    	{
				$data = array(
					'name'	   => $user->name,
					'username' => $user->username,
	        		'email'    => $user->email
				);
	
				// If Activation Email
				if (config_item('email_activation') == FALSE)
				{
					$message = $this->load->view(config_item('email_templates').config_item('email_signup'), $data, true);
		
					$this->email->from(config_item('site_admin_email'), config_item('site_title'));
					$this->email->to($user->email);
					$this->email->subject(config_item('site_title').' thanks you for signing up');
					$this->email->message($message);
					
					if ($this->email->send() == TRUE) 
					{
						$message = array('status' => 'success', 'message' => 'User successfully created', 'data' => $user);
					}
					else
					{
						$message = array('status' => 'error', 'message' => 'User successfully created, email could not be sent', 'data' => $user);
					}
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
					
					if ($this->ci->email->send() == TRUE) 
					{
						$message = array('status' => 'success', 'message' => 'User successfully created', 'data' => $user);
					}
					else 
					{
						$message = array('status' => 'error', 'message' => 'User successfully created, activation email could not be sent', 'data' => $user);
					}
				}
	   		}
	   		else
	   		{
		        $message = array('status' => 'error', 'message' => 'Oops could not create user');
	   		}     
        } 
		else
		{ 
			$message = array('status' => 'error', 'message' => 'Oops '.validation_errors());
        }
        
        $this->response($message, 200);
    }
    
    function login_post()
    {
        // Validate form input
    	$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
	    $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == true)
        {
        	// Remember User
        	if ($this->input->post('remember') == 1) $remember = TRUE;
        	else $remember = FALSE;
        	
        	// Store Session Data
        	if ($this->input->post('session') == 1) $session = TRUE;
        	else $session = FALSE;

        	// Attempt Login
        	if ($user = $this->social_auth->login($this->input->post('email'), $this->input->post('password'), $remember, $session))
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
			$message = array('message' => validation_errors());
        }
        
        $this->response($message, 200);    
    }
    
    function signup_post()
    {
    	$this->form_validation->set_rules('name', 'Name', 'required');
    	$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
    	$this->form_validation->set_rules('phone_number', 'Phone Number', 'valid_phone_number');
    	$this->form_validation->set_rules('password', 'Password', 'required|min_length['.config_item('min_password_length').']|max_length['.$this->config->item('max_password_length').']|strong_pass['.config_item('password_strength').']|matches[password_confirm]');
    	$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');

        if ($this->form_validation->run() == true)
        {
			$username			= url_username($this->input->post('name'), 'none', true);
	    	$email				= $this->input->post('email');
	    	$password			= $this->input->post('password');
	    	$additional_data 	= array(
	    		'phone_number'	=> $this->input->post('phone_number'),
	    		'name'			=> $this->input->post('name'),
	    		'image'			=> '',
	    		'language'		=> $this->input->post('language')	    		
	    	);
	    	        	
	    	if ($user = $this->social_auth->register($username, $password, $email, $additional_data, config_item('default_group')))
	    	{
				$data = array(
					'name'	   => $user->name,
					'username' => $user->username,
	        		'email'    => $user->email
				);
	
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
        } 
		else
		{ 
			$message = array('status' => 'error', 'message' => validation_errors());
        }
        
        $this->response($message, 200);
    }   
    
    function logout_authd_get()
    {
	    $this->session->unset_userdata('email');

		foreach (config_item('user_data') as $item)
		{	
		    $this->session->unset_userdata($item);	    
	    }		
	    
	    if (get_cookie('email')) 
	    {
	    	delete_cookie('email');	
	    }
	    
		if (get_cookie('remember_code')) 
	    {
	    	delete_cookie('remember_code');	
	    }
	    
		$this->session->sess_destroy();

		$message = array('status' => 'success', 'message' => 'Success you have been logged out');
        
        $this->response($message, 200);
    } 
    
    function set_userdata_signup_email_post()
	{
    	$this->form_validation->set_rules('signup_email', 'Email Address', 'required|valid_email');

        if ($this->form_validation->run() == true)
        {        
        	$email = $this->input->post('signup_email');

			if ($user = $this->social_auth->get_user('email', $email))
			{
			    $message = array('status' => 'error', 'message' => 'Oops that email address is in use by someone else!', 'data' => $user);
			}
        	else
        	{
				$this->session->set_userdata('signup_email', $email);
				$this->session->set_userdata('signup_user_state', 'has_connection_and_email');

			    $message = array('status' => 'success', 'message' => 'Awesome, you will now be redirected to finish setting up your account');
    		}
        } 
		else
		{
			$message = array('message' => 'Oops '.validation_errors());
        }
        
        $this->response($message, 200);	
	}

	// Update User    
    function modify_authd_post()
    { 
    	if ($this->oauth_user_id == $this->get('id'))
    	{
	    	$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');	
    	   	$this->form_validation->set_rules('phone_number', 'Phone Number', 'valid_phone_number');

       		if ($this->form_validation->run() == true)
        	{    	
				// Update Data
				$user = $this->social_auth->get_user('user_id', $this->oauth_user_id, TRUE);
				    	  
		    	$update_data = array(
		        	'email'			=> $this->input->post('email'),
		        	'gravatar'		=> md5($this->input->post('email')),
		        	'phone_number'	=> $this->input->post('phone_number'),
		        	'name'			=> $this->input->post('name'),
		        	'time_zone'		=> $this->input->post('time_zone'),
		        	'privacy'		=> $this->input->post('privacy'),
		        	'language'		=> $this->input->post('language'),
		        	'geo_enabled'	=> $this->input->post('geo_enabled'),
		    	);

				// Maybe Username
				if (($this->input->post('username') != $user->username) AND ($this->input->post('username')))
		    	{
		    		$update_data['username'] = url_username($this->input->post('username'), 'none', true);
		    	}

		    	// Update
		    	if ($this->social_auth->update_user($user->user_id, $update_data))
		    	{
		    		if ($this->input->post('session') == 1)
		    		{
		    			$this->social_auth->set_userdata($user);
		    		}

			        $message = array('status' => 'success', 'message' => 'User changes saved', 'user' => $update_data);
		   		}
		   		else
		   		{
			        $message = array('status' => 'error', 'message' => 'Could not save user changes');
		   		}
		   	}
		   	else
		   	{
				$message = array('status' => 'error', 'message' => validation_errors());
		   	}
	   	}
    	else
    	{
			$message = array('status' => 'error', 'message' => 'Ooops this is not your user account');    	
    	}	   	        
        
        $this->response($message, 200);
    }
    
    function upload_profile_picture_post()
    {
    	if ($upload = $this->social_tools->get_upload($this->input->post('upload_id')))
    	{
	    	// If File Exists
			if ($upload->file_hash == $this->input->post('file_hash'))
			{	
				// Delete Expectation
				$this->social_tools->delete_upload($this->input->post('upload_id'));

				// Upload Settings
				$create_path				= config_item('users_images_folder').$this->get('id').'/';
				$config['upload_path'] 		= $create_path;
				$config['allowed_types'] 	= config_item('users_images_formats');
				$config['overwrite']		= true;
				$config['max_size']			= config_item('users_images_max_size');
				$config['max_width']  		= config_item('users_images_max_dimensions');
				$config['max_height']  		= config_item('users_images_max_dimensions');

				$this->load->helper('file');
				$this->load->library('upload', $config);

				// Delete / Make Folder
				delete_files($create_path);
				make_folder($create_path);
				
				// Upload
				if (!$this->upload->do_upload('file'))
				{
			    	$message = array('status' => 'error', 'message' => $this->upload->display_errors('', ''), 'upload_info' => $this->upload->data());
				}	
				else
				{
					// Image Model
					$this->load->model('image_model');
	
					// Upload Data
					$file_data = $this->upload->data();

					// Update DB & Userdata Image
			    	$this->social_auth->update_user($this->get('id'), array('image' => $file_data['file_name']));
			    	$this->session->set_userdata('image', $file_data['file_name']);

					// Make Thumb
					$this->image_model->make_thumbnail($create_path, $file_data['file_name'], 'users', 'medium');
					$this->image_model->make_thumbnail($create_path, $file_data['file_name'], 'users', 'small');

			    	$message = array('status' => 'success', 'message' => 'Profile picture updated', 'upload_info' => $file_data);
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
    
    function delete_profile_picture_authd_get()
    {
		if ($this->social_auth->update_user($this->get('id'), array('image' => '')))
		{
			$this->load->helper('file');
		
			delete_files(config_item('users_images_folder').$this->get('id').'/');
		
		    $message = array('status' => 'success', 'message' => 'Profile picture deleted');	
		}
		else
		{
			$message = array('status' => 'error', 'message' => 'Could not delete profile picture');
		}

    	$this->response($message, 200);
    }

    function details_authd_post()
    {
    	if ($this->oauth_user_id == $this->get('id'))
    	{
			$user_meta_data = array();
			
			// User
			$user_id = $this->oauth_user_id;
			
			// Site
	    	if ($this->input->post('site_id')) $site_id = $this->input->post('site_id');
	    	else $site_id = config_item('site_id');			
			
			// Build Meta
			foreach (config_item('user_meta_details') as $config_meta)
			{
				$user_meta_data[$config_meta] = $this->input->post($config_meta);
			}
	    	
	    	// Update
	    	if ($update_meta = $this->social_auth->update_user_meta($site_id, $user_id, $this->input->post('module'), $user_meta_data))
	    	{
	    		// Update User Data
	    		$this->social_auth->set_userdata_meta($user_id);
	    	
		        $message = array('status' => 'success', 'message' => 'User details saved', 'data' => $user_meta_data);
	   		}
	   		else
	   		{
		        $message = array('status' => 'error', 'message' => 'Could not save user details at this time');
	   		}
    	}
    	else
    	{
			$message = array('status' => 'error', 'message' => 'Ooops this is not your user account');    	
    	}
    	
    	// STILL NEED AN ELSE FOR ADMINS TO MODIFY
    	$this->response($message, 200);
    }
    
    function password_authd_post()
    {
	    $this->form_validation->set_rules('old_password', 'Old password', 'required');
	    $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length['.config_item('min_password_length').']|max_length['.config_item('max_password_length').']|matches[new_password_confirm]');
	    $this->form_validation->set_rules('new_password_confirm', 'Confirm New Password', 'required');
	   	
	    if ($this->form_validation->run() == true) 
	    {
    		if ($change = $this->social_auth->change_password($this->oauth_user_id, $this->input->post('old_password'), $this->input->post('new_password')))
    		{
		        $message = array('status' => 'success', 'message' => 'Password changed Successfully');
    		}
    		else
    		{
		        $message = array('status' => 'error', 'message' => 'Oops could not change your password');    		
    		}
	    }
	    else
	    {
	        $message = array('status' => 'error', 'message' => validation_errors());
	    }
	    
	    $this->response($message, 200);    
    }
    
    function password_forgot_post()
    {
		$this->form_validation->set_rules('email', 'Email Address', 'required');
		
	    if ($this->form_validation->run() == false)
	    {	
 	        $message = array('status' => 'error', 'message' => 'inside not valid' . validation_errors());
	    }
	    else
	    {
			if ($this->social_auth->forgotten_password($this->input->post('email')))
			{
				$profile = $this->social_auth->get_user('email', $this->input->post('email'), TRUE);
	
				// Email Data
				$data = array(
					'email' 					=> $profile->email, 
					'forgotten_password_code'	=> $profile->forgotten_password_code,
					'site'						=> config_item('site_title'),
					'site_url'					=> base_url()
				);

				$message = $this->load->view(config_item('email_templates').config_item('email_forgot_password'), $data, true);	
	
				$this->email->from(config_item('site_admin_email'), config_item('site_title'));
				$this->email->to($profile->email);
				$this->email->subject(config_item('site_title') . ' - Forgotten Password');
				$this->email->message($message);
				
				if ($this->email->send())
				{			
					$message = array('status' => 'success', 'message' => 'Password has been reset and an email has been sent. check your inbox.');
				}
				else
				{
		        	$message = array('status' => 'error', 'message' => 'The email failed to send, please try again.');    		
				}
			}
			else
			{
		        $message = array('status' => 'error', 'message' => 'We could not find that email address or reset it!');    		
			}
	    }
	
		$this->response($message, 200);
	}    
    
    function mobile_add_authd_post()
    {
   		$this->form_validation->set_rules('phone_number', 'Phone Number', 'required|valid_phone_number');

        if ($this->form_validation->run() == true)
        {
			$phone_data = array(
	        	'phone_verified'=> 'yes',
	        	'verify_code'	=> random_element(config_item('mobile_verify')),
	        	'phone_private'	=> $this->input->post('phone_private'),
	        	'phone_type'	=> $this->input->post('phone_type')
			);

	    	$meta_data = array(
	    		'user_id'		=> $this->oauth_user_id,
	    		'site_id'		=> config_item('site_id'),
	    		'module'		=> 'phone',
	    		'meta'			=> preg_replace("[^0-9]", "", $this->input->post('phone_number')),
	    		'value'			=> json_encode($phone_data)
			);

        	if ($user_meta = $this->social_auth->add_user_meta($meta_data))
        	{
		        $message = array('status' => 'success', 'message' => 'Yay, phone number was added', 'data' => $user_meta);
       		}
       		else
       		{
 	       		$message = array('status' => 'error', 'message' => 'Dang, could not add phone number');
       		}
		}
		else
		{
	        $message = array('status' => 'error', 'message' => validation_errors(), 'data' => $_POST);
		}
    
    	$this->response($message, 200);
    }
    
    function mobile_modify_authd_post()
    {
    	$this->social_auth->update_user_meta($this->get('id'));
    
    	if ($this->social_auth->update_user($this->oauth_user_id, $update_data))
    	{
	        $message = array('status' => 'success', 'message' => 'Phone number updated');
   		}
   		else
   		{
       		$message = array('status' => 'error', 'message' => 'Could not update phone number');
   		}

    	$this->response($message, 200);
    }
    
    function mobile_destroy_authd_get()
    {
 	   	$user = $this->social_auth->get_user('user_id', $this->oauth_user_id);

		// NEEDS TO CHECK FOR USER ACCESS
    	if ($this->social_auth->delete_user_meta($this->get('id')))
    	{
	        $message = array('status' => 'success', 'message' => 'Phone number deleted');
   		}
   		else
   		{
       		$message = array('status' => 'error', 'message' => 'Could not delete phone number');
   		}		
    
    	$this->response($message, 200);
    }
    
    function device_create_authd_post()
    {
    	$device_data = array(
    		'user_id'		=> $this->oauth_user_id,
    		'site_id'		=> config_item('site_id'),
    		'module'		=> 'users',
    		'meta'			=> 'device',
    		'value'			=> $this->input->post('device')
		);

        if (!$this->social_auth->check_user_meta_exists($device_data))
        {
        	if ($user_meta = $this->social_auth->add_user_meta($device_data))
        	{        	
		        $message = array('status' => 'success', 'message' => 'Yay, device was added', 'data' => $user_meta);
       		}
       		else
       		{
 	       		$message = array('status' => 'error', 'message' => 'Dang, could not add device');
       		}
		}
		else
		{
	        $message = array('status' => 'error', 'message' => 'Shucks, that device already exists');
		}
    
    	$this->response($message, 200);
    }   
    
    
    /* Advanced Fields */
    function advanced_authd_post()
    {    
    	$message = array('status' => 'success', 'message' => 'User advanced settings updated');
    
    	$this->response($message, 200);    
    }
    
    
	// Activate User
	function activate_authd_get() 
	{		
        if ($activation = $this->social_auth->activate($this->get('id'), $this->get('code')))
        {
	        $message = array('status' => 'success', 'message' => 'User activated');
        }
        else
        {
	        $message = array('status' => 'error', 'message' => 'User could not be activated');
        }
        
        $this->response($message, 200);        
    }
    
    // Deactivate User
	function deactivate_authd_get($id) 
	{
	    $this->social_auth->deactivate($id);

        $this->response($message, $response);
    }    
    
}