<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Social Auth
* 
* Modifier: Brennan Novak
*			@brennannovak
*
* Author: 	Ben Edmunds
* 		  	ben.edmunds@gmail.com
*         	@benedmunds
*          
* Added Awesomeness: Phil Sturgeon
* 
* Location: http://github.com/benedmunds/CodeIgniter-Ion-Auth
*          
* Created:  10.01.2009 
* 
* Description:  Modified auth system based on redux_auth with extensive customization. This is basically what Redux Auth 2 should be.  Original redux license is below.
* Original Author name has been kept but that does not mean that the method has not been modified.
* 
* Requirements: PHP5 or above
*/
 
class Social_auth
{
	protected $ci;
	protected $status;
	protected $messages;
	protected $errors = array();
	protected $error_start_delimiter;
	protected $error_end_delimiter;
	public $_extra_where = array();
	public $_extra_set = array();

	function __construct()
	{
		$this->ci =& get_instance();		
		$this->message_start_delimiter = config_item('message_start_delimiter');
		$this->message_end_delimiter   = config_item('message_end_delimiter');
		$this->error_start_delimiter   = config_item('error_start_delimiter');
		$this->error_end_delimiter     = config_item('error_end_delimiter');
		
		// Load Models
		$this->ci->load->model('auth_model');
		$this->ci->load->model('connections_model');		
		
		// Auto-login user if they're remembered
		if (!$this->logged_in() && get_cookie('identity') && get_cookie('remember_code'))
		{
			$this->ci->auth_model->login_remembered_user();
		}
	}
	
	function activate($id, $code=false)
	{
		if ($this->ci->auth_model->activate($id, $code))
		{
			$this->set_message('activate_successful');
			return TRUE;
		}
		else 
		{
			$this->set_error('activate_unsuccessful');
			return FALSE;	
		}
	}
	
	function deactivate($id)
	{
		if ($this->ci->auth_model->deactivate($id))
		{
			$this->set_message('deactivate_successful');
			return TRUE;
		}
		else 
		{
			$this->set_error('deactivate_unsuccessful');
			return FALSE;	
		}
	}
	
	function change_password($identity, $old, $new)
	{
        if ($this->ci->auth_model->change_password($identity, $old, $new))
        {
        	$this->set_message('password_change_successful');
        	return TRUE;
        }
        else
        {
        	$this->set_error('password_change_unsuccessful');
        	return FALSE;
        }
	}

	function forgotten_password($email)
	{
		if ($this->ci->auth_model->forgotten_password($email)) 
		{
			// Get User
			$profile = $this->ci->auth_model->profile($email);

			$data = array('identity'                => $profile->{config_item('identity')},
						  'forgotten_password_code' => $profile->forgotten_password_code
						 );

			$message = $this->ci->load->view(config_item('email_templates').config_item('email_forgot_password'), $data, true);
			$this->ci->email->clear();
			$config['mailtype'] = "html";
			$this->ci->email->initialize($config);
			$this->ci->email->set_newline("\r\n");
			$this->ci->email->from(config_item('admin_email'), config_item('site_title'));
			$this->ci->email->to($profile->email);
			$this->ci->email->subject(config_item('site_title') . ' - Forgotten Password Verification');
			$this->ci->email->message($message);
			
			if ($this->ci->email->send())
			{
				$this->set_error('forgot_password_successful');
				return TRUE;
			}
			else
			{
				$this->set_error('forgot_password_unsuccessful');
				return FALSE;
			}
		}
		else 
		{
			$this->set_error('forgot_password_unsuccessful');
			return FALSE;
		}
	}
	
	function forgotten_password_complete($code)
	{
	    $identity     = config_item('identity');
	    $profile      = $this->ci->auth_model->profile($code);
		
        if (!is_object($profile)) 
        {
            $this->set_error('password_change_unsuccessful');
            return FALSE;
        }
		
		$new_password = $this->ci->auth_model->forgotten_password_complete($code, $profile->salt);

		if ($new_password) 
		{
			$data = array(
				'identity'     => $profile->{$identity},
				'new_password' => $new_password
			);
            
			$message = $this->ci->load->view(config_item('email_templates').config_item('email_forgot_password_complete'), $data, true);
				
			$this->ci->email->clear();
			$config['mailtype'] = "html";
			$this->ci->email->initialize($config);
			$this->ci->email->set_newline("\r\n");
			$this->ci->email->from(config_item('admin_email'), config_item('site_title'));
			$this->ci->email->to($profile->email);
			$this->ci->email->subject(config_item('site_title') . ' - New Password');
			$this->ci->email->message($message);

			if ($this->ci->email->send())
			{
				$this->set_error('password_change_successful');
				return TRUE;
			}
			else
			{
				$this->set_error('password_change_unsuccessful');
				return FALSE;
			}
		}
		else
		{
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}
	}

	function register($username, $password, $email, $additional_data, $group_name = false)
	{
	    $email_activation = config_item('email_activation');

		if ($email_activation == false)
		{		
			$user_id = $this->ci->auth_model->register($username, $password, $email, $additional_data, $group_name);
		
			if ($user_id) 
			{
				$this->set_message('account_creation_successful');

	    		$user = $this->ci->auth_model->get_user($user_id)->row();

				// Send Welcome Email				
				$data = array(
					'name'	   => $user->name,
					'username' => $user->username,
	        		'email'    => $email
				);
	            
				$message = $this->ci->load->view(config_item('email_templates').config_item('email_signup'), $data, true);
	            
				$this->ci->email->clear();
				$config['mailtype'] = "html";
				$this->ci->email->initialize($config);
				$this->ci->email->set_newline("\r\n");
				$this->ci->email->from(config_item('admin_email'), config_item('site_title'));
				$this->ci->email->to($email);
				$this->ci->email->subject(config_item('site_title') . ' thanks you for signing up');
				$this->ci->email->message($message);
				
				if ($this->ci->email->send() == TRUE) 
				{
					$this->set_message('activation_email_successful');
					return TRUE;
				}
				else 
				{
					$this->set_error('activation_email_unsuccessful');
					return FALSE;
				}
			}
			else 
			{
				$this->set_error('account_creation_unsuccessful');
				return FALSE;
			}
		}		
		else
		{
			// Attempts to add User to database
			$user_id = $this->ci->auth_model->register($username, $password, $email, $additional_data, $group_name);
            
			if (!$user_id) 
			{ 
				$this->set_error('account_creation_unsuccessful');
				return FALSE; 
			}

			$deactivate = $this->ci->auth_model->deactivate($user_id);

			if (!$deactivate) 
			{ 
				$this->set_error('deactivate_unsuccessful');
				return FALSE; 
			}

			$activation_code = $this->ci->auth_model->activation_code;
			$identity        = config_item('identity');
	    	$user            = $this->ci->auth_model->get_user($user_id);

			$data = array(
				'identity'   => $user->{$identity},
				'user_id'    => $user->user_id,
				'email'      => $email,
				'activation' => $activation_code,
			);
            
			$message = $this->ci->load->view(config_item('email_templates').config_item('email_activate'), $data, true);
            
			$this->ci->email->clear();
			$config['mailtype'] = "html";
			$this->ci->email->initialize($config);
			$this->ci->email->set_newline("\r\n");
			$this->ci->email->from(config_item('admin_email'), config_item('site_title'));
			$this->ci->email->to($email);
			$this->ci->email->subject(config_item('site_title') . ' - Account Activation');
			$this->ci->email->message($message);
			
			if ($this->ci->email->send() == TRUE) 
			{
				$this->set_message('activation_email_successful');
				return TRUE;
			}
			else 
			{
				$this->set_error('activation_email_unsuccessful');
				return FALSE;
			}
		}				
	}	

	function social_register($username, $email, $additional_data)
	{
		$user_id = $this->ci->auth_model->social_register($username, $email, $additional_data);

		if ($user_id)
		{
			$this->set_message('account_creation_successful');
			return $user_id;
		}
		else 
		{
			$this->set_error('account_creation_unsuccessful');
			return FALSE;
		}
	}
	
	function login($identity, $password, $remember=false)
	{
		if ($this->ci->auth_model->login($identity, $password, $remember))
		{
			$this->set_message('login_successful');
			return TRUE;
		}
		else
		{
			$this->set_error('login_unsuccessful');
			return FALSE;
		}
	}
	
	function social_login($user_id, $connection)
	{
		if ($this->ci->auth_model->social_login($user_id, $connection))
		{
			$this->set_message('login_successful');
			return TRUE;
		}
		else
		{
			$this->set_error('login_unsuccessful');
			return FALSE;
		}
	}	
	
	function logout()
	{	    
	    $this->ci->session->unset_userdata(config_item('identity'));
	    $this->ci->session->unset_userdata('user_level');
	    $this->ci->session->unset_userdata('user_id');
	    $this->ci->session->unset_userdata('username');
	    $this->ci->session->unset_userdata('user_level_id');
	    $this->ci->session->unset_userdata('name');
	    $this->ci->session->unset_userdata('image');
	    $this->ci->session->unset_userdata('language');
	    $this->ci->session->unset_userdata('time_zone');
	    $this->ci->session->unset_userdata('geo_enabled');
	    $this->ci->session->unset_userdata('privacy');
	    
	    // Delete remember me cookies if they exist
	    if (get_cookie('identity')) 
	    {
	    	delete_cookie('identity');	
	    }
		if (get_cookie('remember_code')) 
	    {
	    	delete_cookie('remember_code');	
	    }
	    
		$this->ci->session->sess_destroy();
		
		$this->set_message('logout_successful');
		return TRUE;
	}
	
	function logged_in()
	{
	    $identity = config_item('identity');
	    
		return (bool) $this->ci->session->userdata($identity);
	}
	
	function is_admin()
	{
		$super_admin	= config_item('super_admin_group');
	    $admin_group 	= config_item('admin_group');
	    $user_group  	= $this->ci->session->userdata('user_level');
	    
	    if (($user_group == $super_admin) || ($user_group == $admin_group))
	    {
	    	return true;
	    }

	    return false;
	}
	
	function is_group($check_group)
	{
	    $user_group = $this->ci->session->userdata('user_level');
	    
	    if(is_array($check_group))
	    {
	    	return in_array($user_group, $check_group);
	    }
	    
	    return $user_group == $check_group;
	}
		
	function profile()
	{
	    $session  = config_item('identity');
	    $identity = $this->ci->session->userdata($session);
	    
	    return $this->ci->auth_model->profile($identity);
	}
	
	function get_users($group_name = false)
	{
	    return $this->ci->auth_model->get_users($group_name)->result();
	}
	
	function get_users_array($group_name = false)
	{
	    return $this->ci->auth_model->get_users($group_name)->result_array();
	}
	
	function get_newest_users($limit = 10)
	{
	    return $this->ci->auth_model->get_newest_users($limit)->result();
	}
	
	function get_newest_users_array($limit = 10)
	{
	    return $this->ci->auth_model->get_newest_users($limit)->result_array();
	}
	
	function get_active_users($group_name = false)
	{
	    return $this->ci->auth_model->get_active_users($group_name)->result();
	}
	
	function get_active_users_array($group_name = false)
	{
	    return $this->ci->auth_model->get_active_users($group_name)->result_array();
	}
	
	function get_inactive_users($group_name = false)
	{
	    return $this->ci->auth_model->get_inactive_users($group_name)->result();
	}
	
	function get_inactive_users_array($group_name = false)
	{
	    return $this->ci->auth_model->get_inactive_users($group_name)->result_array();
	}
	
	function get_user($user_id=false)
	{
	    return $this->ci->auth_model->get_user($user_id);
	}
	
	function get_user_by_email($email)
	{
	    return $this->ci->auth_model->get_user_by_email($email);
	}

	function get_user_by_username($username)
	{
	    return $this->ci->auth_model->get_user_by_username($username);
	}
	
	function get_user_array($user_id=false)
	{
	    return $this->ci->auth_model->get_user($user_id)->row_array();
	}
	
	function get_users_group($user_level_id=false)
	{
	    return $this->ci->auth_model->get_users_group($user_level_id);
	}
	
	function get_groups_array($user_level_id=false)
	{
		return $this->ci->auth_model->get_groups_array($user_level_id);
	}

	function update_user($user_id, $data)
	{
		 if ($this->ci->auth_model->update_user($user_id, $data))
		 {
		 	$this->set_message('update_successful');
		 	return TRUE;
		 }
		 else
		 {
		 	$this->set_error('update_unsuccessful');
		 	return FALSE;
		 }
	}
	
	function delete_user($id)
	{
		 if ($this->ci->auth_model->delete_user($id))
		 {
		 	$this->set_message('delete_successful');
		 	return TRUE;
		 }
		 else
		 {
		 	$this->set_error('delete_unsuccessful');
		 	return FALSE;
		 }
	}
	
	function set_lang($lang='en')
	{
		 return $this->ci->auth_model->set_lang($lang);
	}
	
	
	/* Crazy function that allows extra where field to be used for user fetching/unique checking etc.
	 * Basically this allows users to be unique based on one other thing than the identifier which is helpful
	 * for sites using multiple domains on a single database.
	 * @return void
	 * @author Phil Sturgeon
	 */
	function extra_where()
	{
		$where =& func_get_args();
		
		$this->_extra_where = count($where) == 1 ? $where[0] : array($where[0] => $where[1]);
	}
	
	function extra_set()
	{
		$set =& func_get_args();
		
		$this->_extra_set = count($set) == 1 ? $set[0] : array($set[0] => $set[1]);
	}
	
	function set_message_delimiters($start_delimiter, $end_delimiter)
	{
		$this->message_start_delimiter = $start_delimiter;
		$this->message_end_delimiter   = $end_delimiter;
		
		return TRUE;
	}
	
	function set_error_delimiters($start_delimiter, $end_delimiter)
	{
		$this->error_start_delimiter = $start_delimiter;
		$this->error_end_delimiter   = $end_delimiter;
		
		return TRUE;
	}
	
	function set_message($message)
	{
		$this->messages[] = $message;
		
		return $message;
	}
	
	function messages()
	{
		$_output = '';
		foreach ($this->messages as $message) 
		{
			$_output .= $this->message_start_delimiter . $this->ci->lang->line($message) . $this->message_end_delimiter;
		}
		
		return $_output;
	}
	
	function set_error($error)
	{
		$this->errors[] = $error;
		
		return $error;
	}
	
	function errors()
	{
		$_output = '';
		foreach ($this->errors as $error) 
		{
			$_output .= $this->error_start_delimiter . $this->ci->lang->line($error) . $this->error_end_delimiter;
		}
		
		return $_output;
	}
	

	
	/* Connections Model */
	function check_connection_auth($module, $auth_one, $auth_two)
	{
		return $this->ci->connections_model->check_connection_auth($module, $auth_one, $auth_two);
	}

	function check_connection_user($user_id, $module, $type)
	{
		return $this->ci->connections_model->check_connection_user($user_id, $module, $type);
	}

	function get_connection($connection_id)
	{
		return $this->ci->connections_model->get_connection($connection_id);
	}
	
	function get_connections_user($user_id)
	{
		return $this->ci->connections_model->get_connections_user($user_id);
	}
	
	function add_connection($connection_data)
	{
		return $this->ci->connections_model->add_connection($connection_data);
	}
	
	function delete_connection($connection_id)
	{
		return  $this->ci->connections_model->delete_connection($connection_id);
	}
	
}