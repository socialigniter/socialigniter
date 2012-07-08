<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* Name:  	Social Auth Library
* 
* Author:  	Brennan Novak severely hacked Ben Edmunds 'Ion Auth' which was based on Redux Auth 2 Phil Sturgeon also added some awesomeness
* 		   	contact@social-igniter.com
*			@socialigniter
*
* Location: http://github.com/socialigniter/socialigniter
*/
require_once(APPPATH.'libraries/Oauth/OauthRequestVerifier.php');

class Social_auth
{
	protected $ci;
	protected $status;
	public $config_email	= array();    	
	public $_extra_where	= array();
	public $_extra_set		= array();

	function __construct()
	{
		$this->ci =& get_instance();
			
		// Load Models
		$this->ci->load->model('auth_model');
		$this->ci->load->model('connections_model');				
		
		// Auto-login user if they're remembered
		if (!$this->logged_in() && get_cookie('email') && get_cookie('remember_code'))
		{
			
			if ($user = $this->ci->auth_model->login_remembered_user())
			{
				$this->set_userdata($user);
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

	/* Create / Modify Permissions */
	function has_access_to_create($permission, $user_id, $user_level_id=NULL)
	{
		// Get User
		if (!$user_level_id)
		{
			$user			= $this->get_user('user_id', $user_id);
			$user_level_id	= $user->user_level_id;
		}
		
		// Get Level
		if ($user_level_id <= $permission)
		{
			return 'Y';
		}

		return 'N';
	}

	// Checks if user has access to do task
	// $type 			required string
	// $object			required object of content, activity, or anything
	// $user_id 		required integer
	// $user_level_id	not required
	function has_access_to_modify($type, $object, $user_id, $user_level_id=NULL)
	{
		// Types of objects
		if ($type == 'content')
		{
			if ($user_id == $object->user_id)
			{
				return TRUE;
			}		
		}
		elseif ($type == 'activity')
		{
			if ($user_id == $object->user_id)
			{
				return TRUE;
			}
		}
		elseif ($type == 'comment')
		{
			if ($user_id == $object->user_id)
			{
				return TRUE;
			}	
		}
		else
		{
			if (property_exists($object, 'user_id'))
			{
				if ($user_id == $object->user_id)
				{
					return TRUE;
				}	
			}
			
			return FALSE;
		}
		
		// Get User
		if (!$user_level_id)
		{
			if ($user = $this->get_user('user_id', $user_id))
			{
				$user_level_id	= $user->user_level_id;
			}
			else
			{
				$user_level_id = config_item('default_group');
			}
		}		
		
		// Is Super or Admin
		if ($user_level_id <= 2)
		{
			return TRUE;
		}
				
		return FALSE;
	}	 
	
	
	/* Normal Authentication */	
	function activate($id, $code=false)
	{
		if ($this->ci->auth_model->activate($id, $code))
		{
			return TRUE;
		}
		else 
		{
			return FALSE;
		}
	}
	
	function deactivate($id)
	{
		if ($this->ci->auth_model->deactivate($id))
		{
			return TRUE;
		}
		else 
		{
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
			return TRUE;
		}
		else 
		{
			return FALSE;
		}
	}

	function forgotten_password_complete($user)
	{
        if (!is_object($user)) 
        {
            return FALSE;
        }

		if ($new_password = $this->ci->auth_model->forgotten_password_complete($user->forgotten_password_code, $user->salt)) 
		{
			return $new_password;
		}
		else
		{
			return FALSE;
		}
	}

	function register($username, $password, $email, $additional_data, $group_name=false)
	{
		$user_id = $this->ci->auth_model->register($username, $password, $email, $additional_data, $group_name);
		
		if ($user_id) 
		{
			//$this->set_message('account_creation_successful');
			
			// Add Oauth Tokens
			$this->oauth_register($email, $user_id, $additional_data['name']);

			// Get User
		    $user = $this->get_user('user_id', $user_id, TRUE);

			// Add Activity
			$activity_info = array(
				'site_id'		=> config_item('site_id'),
				'user_id'		=> $user->user_id,
				'verb'			=> 'register',
				'module'		=> 'users',
				'type'			=> 'person',
				'content_id'	=> 0
			);

			$activity_data = array(
				'title'	=> config_item('site_title')
			);

			$activity = $this->ci->social_igniter->add_activity($activity_info, $activity_data);

			return $user;
		}
		else
		{
			return FALSE;
		}
	}

	function social_register($username, $email, $additional_data)
	{
		$user_id = $this->ci->auth_model->social_register($username, $email, $additional_data);

		if ($user_id)
		{			
			// Add Oauth Tokens
			$this->oauth_register($email, $user_id, $additional_data['name']);
			
			// Add Activity
			$activity_info = array(
				'site_id'		=> config_item('site_id'),
				'user_id'		=> $user_id,
				'verb'			=> 'register',
				'module'		=> 'users',
				'type'			=> 'person',
				'content_id'	=> 0
			);
			
			if (array_key_exists('connection', $additional_data))
			{			
				$activity_data = array(
					'title'	=> config_item('site_title').' with '.$additional_data['connection']
				);
		
				$activity = $this->ci->social_igniter->add_activity($activity_info, $activity_data);			
			}				
			
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
	
	function login($email, $password, $remember=FALSE, $session=FALSE)
	{
		if ($user = $this->ci->auth_model->login($email, $password, $remember, $session))
		{        				
			return $user;
		}
		else
		{
			return FALSE;
		}
	}
	
	function social_login($user_id, $connection)
	{
		if ($this->ci->auth_model->social_login($user_id, $connection))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}	
	
	function logout()
	{
	    $this->ci->session->unset_userdata('email');
 
	    if (get_cookie('email')) 
	    {
	    	delete_cookie('email');	
	    }
	    
		if (get_cookie('remember_code')) 
	    {
	    	delete_cookie('remember_code');	
	    }
	    
		$this->ci->session->sess_destroy();
		
		return TRUE;
	}
	
	function logged_in()
	{
		return (bool) $this->ci->session->userdata('email');
	}
		
	function profile($email)
	{
	    if (!$email) $email = $this->ci->session->userdata('email');
	    return $this->ci->auth_model->profile($email);
	}

	function get_users($parameter, $value, $details=FALSE)
	{
	    return $this->ci->auth_model->get_users($parameter, $value, $details);
	}
	
	function get_user($parameter, $value, $details=FALSE)
	{
	    return $this->ci->auth_model->get_user($parameter, $value, $details);
	}
	
	function update_user($user_id, $data)
	{	
		 if ($this->ci->auth_model->update_user($user_id, $data))
		 {
		 	return TRUE;
		 }
		 else
		 {
		 	return FALSE;
		 }
	}
	
	function delete_user($id)
	{
		 if ($this->ci->auth_model->delete_user($id))
		 {
		 	return TRUE;
		 }
		 else
		 {
		 	return FALSE;
		 }
	}	

	/* User Meta */
	function get_user_meta($user_id)
	{
		return $this->ci->auth_model->get_user_meta($user_id);
	}

	function get_user_meta_module($user_id, $module)
	{
		return $this->ci->auth_model->get_user_meta_module($user_id, $module);
	}

	function get_users_meta_module($module)
	{
		return $this->ci->auth_model->get_users_meta_module($module);
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
	
	function delete_user_meta($user_meta_id)
	{
		return $this->ci->auth_model->delete_user_meta($user_meta_id);
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

	function check_connection_username($connection_username, $site_id)
	{
		return $this->ci->connections_model->check_connection_username($connection_username, $site_id);
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