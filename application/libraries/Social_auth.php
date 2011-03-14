<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* Name:  	Social Auth Library
* 
* Author:  	Brennan Novak severely hacked Ben Edmunds 'Ion Auth Model' which was based on Redux Auth 2 Phil Sturgeon also added some awesomeness
* 		   	contact@social-igniter.com
*			@socialigniter
*
* Location: http://github.com/socialigniter/core
*/
require_once(APPPATH.'libraries/Oauth/OauthRequestVerifier.php');

class Social_auth
{
	protected $ci;
	protected $status;
	protected $messages;
	protected $errors   = array();
	protected $error_start_delimiter;
	protected $error_end_delimiter;
	public $config_email	= array();    	
	public $_extra_where	= array();
	public $_extra_set		= array();

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
		
		// Config Email	
		$this->ci->load->library('email');
		
		$this->config_email['protocol']  	= config_item('site_email_protocol');
		$this->config_email['mailtype']  	= 'html';
		$this->config_email['charset']  	= 'UTF-8';
		$this->config_email['crlf']			= "\r\n";
		$this->config_email['newline'] 		= "\r\n"; 			
		$this->config_email['wordwrap']  	= FALSE;
		$this->config_email['validate']		= TRUE;
		$this->config_email['priority']		= 1;
		
		if (config_item('site_email_protocol') == 'smtp')
		{
			$this->config_email['smtp_host'] 	= config_item('site_smtp_host');
			$this->config_email['smtp_user'] 	= config_item('site_smtp_user');
			$this->config_email['smtp_pass'] 	= config_item('site_smtp_pass');
			$this->config_email['smtp_port'] 	= config_item('site_smtp_port');
		}

		$this->ci->email->initialize($this->config_email);
				
		
		// Auto-login user if they're remembered
		if (!$this->logged_in() && get_cookie('email') && get_cookie('remember_code'))
		{
			if ($user = $this->ci->auth_model->login_remembered_user())
			{
				$this->set_userdata($user);
	 			$this->set_userdata_meta($user->user_id);
	 			$this->set_userdata_connections($user->user_id);
			}
		}
		
  		// Pulls In DB settings from CI to Oauth libs
        $database = array(
        	'server'	=> $this->ci->db->hostname, 
        	'username'	=> $this->ci->db->username, 
        	'password'	=> $this->ci->db->password, 
        	'database'	=> $this->ci->db->database
        );
        
        OAuthStore::instance('MySQL', $database);
	}
	
	/* Oauth Functions */
    function request_is_signed()
    {
    	try
    	{
        	return OAuthRequestVerifier::requestIsSigned();
    	}
    	catch (OAuthException2 $e)
    	{
    		return FALSE;
    	}
    }
    
    function get_oauth_user_id()
    {
        try
        {
            $verifier = new OAuthRequestVerifier();
            return $verifier->verify();
        }
        catch (OAuthException2 $e)
        {
            return FALSE;
        }
    }
    
    // creates a consumer for the user. if creating, returns the consumer
    // with additional keys: consumer_key, consumer_secret.
    // possible keys for consumer are: requester_name, requester_email,
    // callback_uri, application_uri, application_title, application_descr
    // application_notes, application_commercial 
    function create_or_update_consumer($consumer, $user_id)
    {
        $store = OAuthStore::instance();
        $key = $store->updateConsumer($consumer, $user_id);
        $consumer = $store->getConsumer($key, $user_id);
        return $consumer;
    }
    
    // returns an array with keys 'token' and 'token_secret' which can be used
    // to sign requests.
    function grant_access_token_to_consumer($consumer_key, $user_id)
    {
    	$store = OAuthStore::instance();
        $token_info = $store->addConsumerRequestToken($consumer_key);
        $store->authorizeConsumerRequestToken($token_info['token'], $user_id);
        $token_and_secret = $store->exchangeConsumerRequestForAccessToken($token_info['token']);
        return $token_and_secret;
    }
	
	
	/* Normal Authentication */	
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
	
	function change_password($user_id, $old, $new)
	{
        if ($this->ci->auth_model->change_password($user_id, $old, $new))
        {
        	return TRUE;
        }
        else
        {
        	return FALSE;
        }
	}

	function forgotten_password($email)
	{
		if ($this->ci->auth_model->forgotten_password($email)) 
		{
			$profile = $this->ci->auth_model->profile($email);

			$data = array(
				'email' 					=> $profile->email, 
				'forgotten_password_code'	=> $profile->forgotten_password_code
			);			

			$message = $this->ci->load->view(config_item('email_templates').config_item('email_forgot_password'), $data, true);	

			$this->ci->email->set_newline("\r\n");
			$this->ci->email->from(config_item('site_admin_email'), config_item('site_title'));
			$this->ci->email->to($profile->email);
			$this->ci->email->subject(config_item('site_title') . ' - Forgotten Password Verification');
			$this->ci->email->message($message);
			
			if ($this->ci->email->send())
			{
				log_message('debug', 'emailssss debugger: '. $this->ci->email->print_debugger());
			
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
	    $profile = $this->ci->auth_model->profile($code);

        if (!is_object($profile)) 
        {
            $this->set_error('password_change_unsuccessful');
            return FALSE;
        }

		$new_password = $this->ci->auth_model->forgotten_password_complete($code, $profile->salt);

		if ($new_password) 
		{
			$data = array(
				'email' 		=> $profile->email, 
				'new_password'	=> $new_password
			);
            
			$message = $this->ci->load->view(config_item('email_templates').config_item('email_forgot_password_complete'), $data, true);

			$this->ci->email->set_newline("\r\n");
			$this->ci->email->from(config_item('site_admin_email'), config_item('site_title'));
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

	function register($username, $password, $email, $additional_data, $group_name=false)
	{
		$user_id = $this->ci->auth_model->register($username, $password, $email, $additional_data, $group_name);
		
		if ($user_id) 
		{
			$this->set_message('account_creation_successful');
			
			// Add Oauth Tokens
			$this->oauth_register($email, $user_id, $additional_data['name']);

			// Get User
		    $user = $this->get_user('user_id', $user_id);

			// Send Welcome Email				
			$data = array(
				'name'	   => $user->name,
				'username' => $user->username,
        		'email'    => $user->email
			);

			// If Activation Email
			if (config_item('email_activation') == false)
			{
				$message = $this->ci->load->view(config_item('email_templates').config_item('email_signup'), $data, true);
	
				$this->ci->email->set_newline("\r\n");	            
				$this->ci->email->from(config_item('site_admin_email'), config_item('site_title'));
				$this->ci->email->to($user->email);
				$this->ci->email->subject(config_item('site_title').' thanks you for signing up');
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
				$activation_code = $this->ci->auth_model->activation_code;
	
				$data = array(
					'email'   	 => $user->email,
					'user_id'    => $user->user_id,
					'email'      => $user->email,
					'activation' => $activation_code,
				);
	            
				$message = $this->ci->load->view(config_item('email_templates').config_item('email_activate'), $data, true);
	
				$this->ci->email->set_newline("\r\n");            
				$this->ci->email->from(config_item('site_admin_email'), config_item('site_title'));
				$this->ci->email->to($user->email);
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
		else 
		{
			$this->set_error('account_creation_unsuccessful');
			return FALSE;
		}	            	
	}	

	function social_register($username, $email, $additional_data)
	{
		$user_id = $this->ci->auth_model->social_register($username, $email, $additional_data);

		if ($user_id)
		{
			$this->set_message('account_creation_successful');	
			
			// Add Oauth Tokens
			$this->oauth_register($email, $user_id, $additional_data['name']);
			
			return $user_id;
		}
		else 
		{
			$this->set_error('account_creation_unsuccessful');
			return FALSE;
		}
	}
	
	function oauth_register($email, $user_id, $name=NULL)
	{
		if (($email) && ($user_id))
		{
			// Make OAuth Tokens & debug msgs
			$consumer_keys	= $this->create_or_update_consumer(array('requester_name' => $name, 'requester_email' => $email), $user_id);
			$access_tokens	= $this->grant_access_token_to_consumer($consumer_keys['consumer_key'], $user_id);
	
	    	$update_data = array(
	        	'consumer_key'		=> $consumer_keys['consumer_key'],
	        	'consumer_secret'	=> $consumer_keys['consumer_secret'],
	        	'token'				=> $access_tokens['token'],
	        	'token_secret'		=> $access_tokens['token_secret']
			);
	    	
	    	// Update the user with tokens
	    	return $this->update_user($user_id, $update_data);	
		}
		else
		{
			return FALSE;
		}
	}
	
	function login($email, $password, $remember=false)
	{
		if ($this->ci->auth_model->login($email, $password, $remember))
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
	    $this->ci->session->unset_userdata('email');

		foreach (config_item('user_data') as $item)
		{	
		    $this->ci->session->unset_userdata($item);	    
	    }		
	    
	    if (get_cookie('email')) 
	    {
	    	delete_cookie('email');	
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
		return (bool) $this->ci->session->userdata('email');
	}
		
	function profile()
	{
	    $email = $this->ci->session->userdata('email');
	    
	    return $this->ci->auth_model->profile($email);
	}
	
	function get_users($group_name=false)
	{
	    return $this->ci->auth_model->get_users($group_name)->result();
	}
	
	function get_user($parameter, $value)
	{
	    return $this->ci->auth_model->get_user($parameter, $value);
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

	/* User Meta */
	function get_user_meta($user_id)
	{
		return $this->ci->auth_model->get_user_meta($user_id);
	}

	function get_user_meta_meta($user_id, $meta)
	{
		return $this->ci->auth_model->get_user_meta_meta($user_id, $meta);
	}

	function get_user_meta_row($user_id, $meta)
	{
		return $this->ci->auth_model->get_user_meta_row($user_id, $meta);
	}
	
	function find_user_meta_value($key, $meta_query)
	{
		foreach($meta_query as $meta)
		{			
			if ($meta->meta == $key)
			{
				return $meta->value;
			}			
		}		
		
		return FALSE;
	}
	
	function check_user_meta_exists($user_meta_data)
	{
		return $this->ci->auth_model->check_user_meta_exists($user_meta_data);
	}	
	
	function add_user_meta($meta_data)
	{
		return $this->ci->auth_model->add_user_meta($meta_data);
	}
	
	function update_user_meta($site_id, $user_id, $module, $user_meta_data)
	{
    	$update_total = count($user_meta_data);
    	$update_count = 0;
    	    
		// Loop user_meta_data
		foreach ($user_meta_data as $meta => $value)
		{	
			$update_count++;
			
			// Form Element Name
			$this_user_meta = array(
				'user_id'	=> $user_id,
				'site_id'	=> $site_id,
				'module'	=> $module,
				'meta'		=> $meta
			);

			$current = $this->check_user_meta_exists($this_user_meta);
			
			if ($current)
			{			
				$this->ci->auth_model->update_user_meta($current->user_meta_id, array('value' => $value));
			}
			else
			{
				$this_user_meta['value'] = $value;
				$this->ci->auth_model->add_user_meta($this_user_meta);			
			}
		}
		
		// Were All Updated
		if ($update_total == $update_count)
		{
			return TRUE;
		}
		
    	return FALSE;
	}
	
	
	/* User Levels */
	function get_users_levels()
	{
	    return $this->ci->auth_model->get_users_levels();
	}
		

	/* Sets user_data */
	function set_userdata($user)
	{
		$this->ci->session->set_userdata('email',  $user->email);

		foreach (config_item('user_data') as $item)
		{
		    $this->ci->session->set_userdata($item,  $user->{$item});
	    }
	}

	function set_userdata_meta($user_id)
	{
		$user_meta = $this->get_user_meta($user_id);
	
		foreach ($user_meta as $meta)
		{
			if (in_array($meta->meta, config_item('user_data_meta')))
			{
		    	$this->ci->session->set_userdata($meta->meta,  $meta->value);
	    	}
	    }
	}

	function set_userdata_connections($user_id)
	{	
		$this->ci->session->set_userdata('user_connections', $this->get_connections_user($user_id));
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

	function check_connection_user_id($connection_user_id, $module)
	{
		return $this->ci->connections_model->check_connection_user_id($connection_user_id, $module);
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

	function update_connection($connection_id, $connection_data)
	{
		return $this->ci->connections_model->update_connection($connection_id, $connection_data);
	}
	
	function delete_connection($connection_id)
	{
		return  $this->ci->connections_model->delete_connection($connection_id);
	}

}